<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\AccessoryOrder;
use App\Models\Expense;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaanAccessoryOrderController extends Controller
{
    public function index()
    {
        $invoice_no = "AINV-" . str_pad(AccessoryOrder::max('id') + 1, 5, '0', STR_PAD_LEFT);
        $accessoriesOrder = AccessoryOrder::with('party', 'accessory', 'accessory.unit')->latest()->paginate(10);
        $accessories = Accessory::latest()->get();
        $parties = Party::with('user')
                    ->where('type', 'supplier')
                    ->latest()->get();

        return view('pages.accessory.order.index', compact('invoice_no','accessories', 'accessoriesOrder', 'parties'));
    }

    /** filter the table list */
    public function maanFilter(Request $request)
    {
        $accessoriesOrder = AccessoryOrder::with('party', 'accessory', 'accessory.unit')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('invoice_no', 'like', '%' . $request->search . '%')
                        ->orWhereHas('party', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('accessory', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('accessory.unit', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.accessory.order.datas', compact('accessoriesOrder'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|string',
            'accessory_id' => 'required|integer',
            'party_id' => 'required|integer',
            'qty_unit' => 'required|numeric',
            'unit_price' => 'required|numeric|min:0|max:999999999999',
            'ttl_amount' => 'required|numeric|min:0|max:999999999999',
        ]);

        $accessory = Accessory::findOrFail($request->accessory_id);
        $ttl_amount = $request->qty_unit * $accessory->unit_price; // request total amount
        $party = Party::findOrFail($request->party_id);

        DB::beginTransaction();
        try {
            $invoice_no = $request->invoice_no;
            $has_already = AccessoryOrder::where('invoice_no', $invoice_no)->exists();
            if ($has_already) {
                $invoice_no = "AINV-" . str_pad(mt_rand(10, 99).AccessoryOrder::max('id') + 1, 5, '0', STR_PAD_LEFT);
            }
            if ($party->type === 'supplier') {
                $party->update([
                    'total_bill' => $party->total_bill + $ttl_amount,
                    'due_amount' => $party->due_amount + $ttl_amount,
                ]);
                $accessories_order = AccessoryOrder::create($request->except('invoice_no','ttl_amount', 'unit_price') + [
                    'invoice_no' => $invoice_no,
                    'user_id' => auth()->id(),
                    'ttl_amount' => $ttl_amount,
                    'unit_price' => $accessory->unit_price,
                ]);

                $expenes = Expense::create([
                    'party_id'      => $party->id,
                    'total_bill'    => $ttl_amount,
                    'total_due'     => $ttl_amount,
                    'category_name' => $accessories_order->invoice_no,
                ]);

                expenseInvoice($accessories_order, $party, $expenes);

                DB::commit();
                sendNotification($accessories_order->id, route('accessory-orders.index'), __('New accessories Order has been created.'));
                return response()->json([
                    'message' => __('Accessories Order created successfully'),
                    'redirect' => route('accessory-orders.index')
                ]);
            } else {
                return response()->json(__('Please select a supplier'), 400);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function edit(AccessoryOrder $accessoryOrder)
    {
        abort_if(!check_visibility($accessoryOrder->created_at, 'accessory-orders', $accessoryOrder->user_id), 403);
        $accessories = Accessory::latest()->get();
        $parties = Party::with('user')
            ->where('type', 'supplier')
            ->latest()->get();
        return view('pages.accessory.order.edit', compact('accessories', 'accessoryOrder', 'accessories', 'parties'));
    }

    public function update(Request $request, AccessoryOrder $accessoryOrder)
    {
        $request->validate([
            'party_id' => 'required|integer',
            'qty_unit' => 'required|numeric',
            'accessory_id' => 'required|integer',
            'unit_price' => 'required|numeric|min:0|max:999999999999',
            'ttl_amount' => 'required|numeric|min:0|max:999999999999',
        ]);

        $permission = check_visibility($accessoryOrder->created_at, 'accessory-orders', $accessoryOrder->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $party = Party::findOrFail($request->party_id);
        $accessory = Accessory::findOrFail($request->accessory_id);
        $ttl_amount = $request->qty_unit * $accessory->unit_price; // request total amount

        DB::beginTransaction();
        try {
            if ($party->type === 'supplier') {
                if ($accessoryOrder->party_id == $request->party_id) {
                    $party->update([
                        'total_bill' => ($party->total_bill - $accessoryOrder->ttl_amount) + $ttl_amount,
                        'due_amount' => ($party->due_amount - $accessoryOrder->ttl_amount) + $ttl_amount,
                    ]);
                } else {
                    $prev_party = Party::findOrFail($accessoryOrder->party_id);
                    $prev_party->update([
                        'total_bill' => $prev_party->total_bill - $accessoryOrder->ttl_amount,
                        'due_amount' => $prev_party->due_amount - $accessoryOrder->ttl_amount,
                    ]);
                    $party->update([
                        'total_bill' => $party->total_bill + $ttl_amount,
                        'due_amount' => $party->due_amount + $ttl_amount,
                    ]);
                }

                $expenes = Expense::where('category_name', $accessoryOrder->invoice_no)->first();
                $prev_expense_id = $expenes->id;

                $expenes->update([
                    'total_bill'    => $ttl_amount,
                    'party_id'      => $request->party_id,
                    'category_name' => $request->invoice_no,
                    'total_due'     => ($ttl_amount - $expenes->total_paid),
                ]);

                $accessoryOrder->update($request->except('ttl_amount', 'unit_price') + [
                    'user_id' => auth()->id(),
                    'ttl_amount' => $ttl_amount,
                    'unit_price' => $accessory->unit_price,
                ]);

                expenseInvoice($accessoryOrder, $party, $expenes, $prev_expense_id);

                DB::commit();
                return response()->json([
                    'message' => __('Accessories Order updated successfully'),
                    'redirect' => route('accessory-orders.index')
                ]);

            } else {
                return response()->json(__('Please select a supplier'), 400);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function destroy(AccessoryOrder $accessoryOrder)
    {
        $permission = check_visibility($accessoryOrder->created_at, 'accessory-orders', $accessoryOrder->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $expense = Expense::where('category_name', $accessoryOrder->invoice_no)->first();
        if ($expense->total_paid) {
            return response()->json(__('You can not delete this order, Because this order already has transactions.'), 403);
        }
        $party = Party::findOrFail($accessoryOrder->party_id);

        DB::beginTransaction();
        try {
            $party->update([
                'total_bill' => $party->total_bill - $accessoryOrder->ttl_amount,
                'due_amount' => $party->due_amount - $accessoryOrder->ttl_amount,
            ]);

            $expesen = Expense::where('category_name', $accessoryOrder->invoice_no)->first();
            $expesen->delete();
            $accessoryOrder->delete();

            DB::commit();

            return response()->json([
                'message' => __('Accessory Order deleted successfully'),
                'redirect' => route('accessory-orders.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(__('Something was wrong.'), 400);
        }
    }
}
