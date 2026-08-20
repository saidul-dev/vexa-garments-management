<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\History;
use App\Models\OrderDetails;
use App\Models\User;
use App\Models\Order;
use App\Models\Party;
use App\Models\Income;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MaanOrderController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:orders-create')->only('create', 'store');
        $this->middleware('permission:orders-read')->only('index', 'show');
        $this->middleware('permission:orders-update')->only('edit', 'update');
        $this->middleware('permission:orders-delete')->only('destroy');
        $this->middleware('permission:orders-status')->only('status');
    }

    public function index()
    {
        $order_no = str_pad(Order::max('id') + 1, 7, '0', STR_PAD_LEFT);
        $banks = Bank::select('id','holder_name','account_number')->latest()->get();
        $parties = Party::with('user:id,phone')->whereNotIn('type', ['supplier'])->latest()->get();
        $merchandisers = User::where('role', 'merchandiser')->select('id','name','phone')->latest()->get();
        $orders = Order::with('orderDetails','party','merchandiser','bank')
                    ->when(!auth()->user()->can('orders-list'), function($query) {
                        $query->where('user_id', auth()->id())
                            ->orWhere('merchandiser_id', auth()->id())
                            ->orWhere('party_id', auth()->id());
                    })
                    ->latest()
                    ->paginate(10);

        return view('pages.order.summery.index',compact('parties','merchandisers','banks','orders', 'order_no'));
    }

    /** filter the table list */
    public function maanFilter(Request $request)
    {
        $orders = Order::with('party','merchandiser','bank')
                    ->when($request->from_date, function($q) {
                        $q->whereDate('created_at', '>=', request('from_date'));
                    })
                    ->when($request->to_date, function($q) {
                        $q->whereDate('created_at', '<=', request('to_date'));
                    })
                    ->when(request('search'), function($q) {
                        $q->where(function($q) {
                            $q->orWhere('order_no', 'like', '%'.request('search').'%')
                                ->orWhere('fabrication', 'like', '%'.request('search').'%')
                                ->orWhere('gsm', 'like', '%'.request('search').'%')
                                ->orWhere('shipment_mode', 'like', '%'.request('search').'%')
                                ->orWhere('payment_mode', 'like', '%'.request('search').'%')
                                ->orWhere('year', 'like', '%'.request('search').'%')
                                ->orWhere('season', 'like', '%'.request('search').'%')
                                ->orWhereHas('merchandiser', function($q) {
                                    $q->where('name', 'like', '%'.request('search').'%');
                                })
                                ->orWhereHas('party', function($q) {
                                    $q->where('name', 'like', '%'.request('search').'%');
                                });

                        });
                    })
                    ->when(!auth()->user()->can('orders-list'), function($query) {
                        $query->where('user_id', auth()->id())
                        ->orWhere('merchandiser_id', auth()->id())
                        ->orWhere('party_id', auth()->id());
                    })
                    ->latest('id')
                    ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.order.summery.datas', compact('orders'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'bank_id' => 'nullable|exists:banks,id',
            'order_no' => 'required|string',
            'title' => 'required|string',
            'image' => 'nullable|image',
            'fabrication' => 'nullable|string',
            'gsm' => 'nullable|string',
            'yarn_count' => 'nullable|string',
            'shipment_mode' => 'nullable|string',
            'payment_mode' => 'nullable|string',
            'year' => 'nullable|string',
            'season' => 'nullable|string',
            'description' => 'nullable|string',
            'meta' => 'nullable|array',
            'invoice_details' => 'nullable|array',
            'style' => 'required|array',
            'style.*' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $duplicates = array_diff_assoc($request->style, array_unique($request->style));
                    if (!empty($duplicates)) {
                        $fail("Duplicate values found in the style field.");
                    }
                },
            ],
            'color' => 'nullable|array',
            'item' => 'nullable|array',
            'shipment_date' => 'required|array',
            'shipment_date.*' => 'required|string',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric',
            'total_price' => 'nullable|array',
        ], [
            'style.*.required' => 'Sorry the style number can not be empty!',
        ]);

        if(auth()->user()->role != 'merchandiser') {
            $request->validate([
                'merchandiser_id' => 'required|exists:users,id',
            ]);
        }

        DB::beginTransaction();
        try {

            $order_no = $request->order_no;
            $has_already = Order::where('order_no', $order_no)->exists();
            if ($has_already) {
                $order_no = str_pad(mt_rand(10, 99).Order::max('id') + 1, 7, '0', STR_PAD_LEFT);
            }

            $order = Order::create($request->except('image', 'merchandiser_id', 'lc', 'order_no') + [
                    'order_no' => $order_no,
                    'user_id' => auth()->id(),
                    'lc' => collect($request->total_price)->sum(),
                    'image' => $request->image ? $this->upload($request, 'image') : null,
                    'merchandiser_id' => auth()->user()->role == 'merchandiser' ? auth()->id() : $request->merchandiser_id,
                ]);

            foreach ($request->style as $key => $style) {
                OrderDetails::create([
                    'order_id'      => $order->id,
                    'style'         => $style,
                    'color'         => $request->color[$key],
                    'item'          => $request->item[$key],
                    'shipment_date' => $request->shipment_date[$key],
                    'qty'           => $request->qty[$key],
                    'unit_price'    => $request->unit_price[$key],
                    'total_price'   => $request->qty[$key] * $request->unit_price[$key],
                ]);
            }

            $totalPriceSum = $order->orderDetails->sum('total_price');
            $order->load('orderDetails');
            $party = $order->party;
            if(auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin') {
                $order->update(['status' => 2]);

                if ($party !== null) {
                    $income = Income::create([
                        'party_id'      => $party->id,
                        'category_name' => $order->order_no,
                        'total_bill'    => $totalPriceSum,
                        'total_due'     => $totalPriceSum,
                    ]);

                    $party->update([
                        'total_bill' => $party->total_bill + $totalPriceSum,
                        'due_amount' => $party->due_amount + $totalPriceSum,
                    ]);
                }

                createIncomeInvoice($order, $party, $income ?? null);
            }

            createHistory($order->load('orderDetails')); // Order history
            sendNotification($order->id, route('orders.index'), __('New order has been created.'));
            DB::commit();
            return response()->json([
                'message'   => __('Order saved successfully'),
                'redirect'  => route('orders.index')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function show(Order $order)
    {
        $order->load('orderDetails');

        return view('pages.order.summery.print', compact('order'));
    }

    public function edit(Order $order)
    {
        abort_if(!check_visibility($order->created_at, 'orders', $order->user_id), 403);
        $banks = Bank::select('id','holder_name','account_number')->latest()->get();
        $parties = Party::with('user:id,phone')->whereNotIn('type', ['supplier'])->latest()->get();
        $merchandisers = User::where('role', 'merchandiser')->select('id','name','phone')->latest()->get();
        $order->load('orderDetails');
        return view('pages.order.summery.edit',compact('parties','merchandisers','order','banks'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'bank_id' => 'nullable|exists:banks,id',
            'order_no' => 'required|string|unique:orders,order_no,' . $order->id,
            'title' => 'required|string',
            'image' => 'nullable|image',
            'fabrication' => 'nullable|string',
            'gsm' => 'nullable|string',
            'yarn_count' => 'nullable|string',
            'shipment_mode' => 'nullable|string',
            'payment_mode' => 'nullable|string',
            'year' => 'nullable|string',
            'season' => 'nullable|string',
            'description' => 'nullable|string',
            'meta' => 'nullable|array',
            'invoice_details' => 'nullable|array',
            'style' => 'required|array',
            'style.*' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    // Check for duplicates within the current request
                    $duplicates = array_diff_assoc($request->style, array_unique($request->style));
                    if (!empty($duplicates)) {
                        $fail("Duplicate values found in the style field.");
                    }
                },
            ],
            'color' => 'nullable|array',
            'item' => 'nullable|array',
            'shipment_date' => 'required|array',
            'shipment_date.*' => 'required|string',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric',
            'total_price' => 'nullable|array',
        ], [
            'style.*.required' => 'Sorry the style number can not be empty!',
        ]);

        $permission = check_visibility($order->created_at, 'orders', $order->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        DB::beginTransaction();
        try {
            $previous_order = $order->load('orderDetails');

            $order->load('orderDetails');
            $party = $order->party;
            $totalPriceSum = $order->orderDetails->sum('total_price'); // orderDetails table sum of total_price
            $income = Income::where('category_name', $order->order_no)->first();
            $prev_income_id = $income->id ?? null;

            $order->update($request->except('image', 'merchandiser_id', 'lc') + [
                    'user_id'           => auth()->id(),
                    'merchandiser_id'   => auth()->user()->role == 'merchandiser' ? auth()->id() : $request->merchandiser_id,
                    'image'             => $request->image ? $this->upload($request, 'image', $order->image) : $order->image,
                    'lc'                => collect($request->total_price)->sum(),
                ]);

            $order->orderDetails()->delete();

            foreach ($request->style as $key => $style) {
                $order->orderDetails()->create([
                    'order_id'      => $order->id,
                    'style'         => $style,
                    'color'         => $request->color[$key],
                    'item'          => $request->item[$key],
                    'shipment_date' => $request->shipment_date[$key],
                    'qty'           => $request->qty[$key],
                    'unit_price'    => $request->unit_price[$key],
                    'total_price'   => $request->qty[$key] * $request->unit_price[$key],
                ]);
            }

            $updated_order = Order::with('orderDetails', 'merchandiser')->findOrFail($order->id); // for updated order
            $updated_order->load('orderDetails');
            $updated_party = $updated_order->party;

            if ($order->status == 2) {

                $requestTotalPriceSum = collect($request->total_price)->sum(); // requested sum of total price

                $income->update([
                    'party_id' => $updated_party->id,
                    'category_name' => $updated_order->order_no,
                    'total_bill' => ($income->total_bill - $totalPriceSum) + $requestTotalPriceSum,
                    'total_due' => ($income->total_due - $totalPriceSum) + $requestTotalPriceSum,
                ]);
                // for unchanged order
                $party->update([
                    'total_bill' => ($party->total_bill - $totalPriceSum) + $requestTotalPriceSum,
                    'due_amount' => ($party->due_amount - $totalPriceSum) + $requestTotalPriceSum,
                ]);
                createIncomeInvoice($order, $party, $income ?? null, $prev_income_id);
            }

            createHistory($previous_order);
            createHistory($updated_order); // Order history
            DB::commit();
            return response()->json([
                'message'   => __('Order updated successfully'),
                'redirect'  => route('orders.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(__('Something was wrong.'), 400);
        }

    }
    public function status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer',
            'reason' => 'required_if:status,0|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            $order->load('orderDetails');
            $party = $order->party;
            $totalPriceSum = $order->orderDetails->sum('total_price');

            if ($request->status == 2) {
                if ($party !== null) {
                    $income = Income::create([
                        'party_id' => $party->id,
                        'category_name' => $order->order_no,
                        'total_bill' => $totalPriceSum,
                        'total_due' => $totalPriceSum,
                    ]);

                    $party->update([
                        'total_bill' => $party->total_bill + $totalPriceSum,
                        'due_amount' => $party->due_amount + $totalPriceSum,
                    ]);
                }

                createIncomeInvoice($order, $party, $income ?? null);
            }

            $order->update([
                'status' => $request->status == 2 ? $request->status : 0,
                'meta' => $order->meta + [
                        'reason' => $request->reason
                    ],
            ]);

            DB::commit();

            return response()->json([
                'message'   => __('Order status updated successfully'),
                'redirect'  => route('orders.index')
            ]);

        } catch (\Exception $e){
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function destroy(Order $order)
    {
        DB::beginTransaction();
        try {
            $permission = check_visibility($order->created_at, 'orders', $order->user_id);
            if (!$permission) {
                return response()->json(__('You don\'t have permission.'), 403);
            }

            if (file_exists($order->image)) {
                Storage::delete($order->image);
            }

            $order->load('orderDetails');
            $party = $order->party;
            $totalPriceSum = $order->orderDetails->sum('total_price');

            if ($order->status == 2) {
                Income::where('category_name', $order->order_no)->first()->delete();
                $party->update([
                    'total_bill' => $party->total_bill - $totalPriceSum,
                    'due_amount' => $party->due_amount - $totalPriceSum,
                ]);
            }

            $order->delete();

            DB::commit();

            return response()->json([
                'message'   => __('Order deleted successfully'),
                'redirect'  => route('orders.index')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }

    }

    public function orderDetails($id)
    {
        $order_details = OrderDetails::where('order_id', $id)->get();
        return view('pages.order.summery.order-details',compact('order_details'));
    }

    public function orderHistory($id)
    {
        $histories = History::where(['table' => 'orders', 'row_id' => $id])->get();
        return view('pages.order.summery.changed-history.index', compact('histories'));
    }

    public function orderHistoryPrint($id)
    {
        $history = History::findOrFail($id);
        return view('pages.order.summery.changed-history.print', compact('history'));
    }

    public function historyFilter(Request $request)
    {
        $histories = History::where(['table' => 'orders', 'row_id' => $request->id])
            ->when($request->to_date, function ($q) {
                $q->whereDate('created_at', '<=', request('to_date'));
            })
            ->get();
        return response()->json([
            'data' => view('pages.order.summery.changed-history.datas', compact('histories'))->render()
        ]);
    }

}
