<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cash;
use App\Models\Transfer;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaanCashController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cashes-create')->only('create', 'store');
        $this->middleware('permission:cashes-read')->only('index', 'show');
        $this->middleware('permission:cashes-update')->only('edit', 'update');
        $this->middleware('permission:cashes-delete')->only('destroy');
    }
    public function index()
    {
        $total_debit = Cash::where('type', 'debit')->sum('amount');
        $total_credit = Cash::where('type', 'credit')->sum('amount');
        $banks = Bank::select('id','bank_name','account_number')->latest()->get();
        $cashes = Cash::with('bank')
                    ->when(!auth()->user()->can('cashes-list'), function($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->latest()->paginate(10);

        return view('pages.accounts.commercial.cash.index', compact('banks', 'cashes', 'total_debit', 'total_credit'));
    }

    public function maanFilter(Request $request)
    {
        $cashes = Cash::with('bank')
            ->when($request->has('search'), function ($q) use($request) {
                $q->Where('cash_type', 'like', '%'.$request->search.'%');
            })
            ->orWhereHas('bank', function($q) use($request){
                $q->where('bank_name', 'like', '%'.$request->search.'%')
                    ->orWhere('holder_name', 'like', '%'.$request->search.'%');
            })
            ->when(!auth()->user()->can('cashes-list'), function($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        if($request->ajax()){
            return response()->json([
                'data' => view('pages.accounts.commercial.cash.datas',compact('cashes'))->render()
            ]);
        }
        return redirect(url()->previous());
    }
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|string',
            'cash_type' => 'required|string',
            'description' => 'nullable|string',
            'amount' => ['required', 'numeric', 'gt:0','max:99999999999'],
        ]);

        DB::beginTransaction();
        try {

            $cash_balance = cash_balance();
            if ($request->bank_id == 'petty_cash') {
                // FOR CASH
                if ($request->type == 'debit') {
                    if ($request->amount > $cash_balance) {
                        return response()->json(__('The amount cannot exceed the current cash balance.'), 400);
                    }
                }

                $voucher = Voucher::create($request->except('bank_id', 'amount') + [
                    'payment_method' => 'cash',
                    'amount' => $request->amount,
                    'particulars' => 'different_module',
                    'prev_balance' => $cash_balance,
                    'remarks' => $request->description,
                    'current_balance' => $request->type == 'debit' ? ($cash_balance - $request->amount) : ($cash_balance + $request->amount),
                ]);

                $cash = Cash::create($request->all() + [
                    'user_id' => auth()->id(),
                    'voucher_id' => $voucher->id,
                ]);

            } else { // FOR BANK

                $bank = Bank::findOrfail($request->bank_id);
                if ($request->type == 'debit') {
                    if($request->amount <= $cash_balance) {
                        $bank->update([
                            'balance' => $bank->balance + $request->amount
                        ]);
                    } else {
                        return response()->json(__('Amount can not more than main balance'), 400);
                    }
                } else {
                    if ($request->amount <= $bank->balance) {
                        $bank->update([
                            'balance' => $bank->balance - $request->amount
                        ]);
                    } else {
                        return response()->json(__('Amount can not more than main balance'), 400);
                    }
                }

                $cash = Cash::create($request->all() + [
                    'user_id' => auth()->id(),
                ]);

                Transfer::create($request->except('amount') + [
                    'user_id' => auth()->id(),
                    'amount' => $request->amount,
                    'note' => $request->description,
                    'adjust_type' => $request->type == 'debit' ? 'credit' : 'debit',
                    'bank_to' => $request->type == 'debit' ? $request->bank_id : NULL,
                    'bank_from' => $request->type == 'credit' ? $request->bank_id : NULL,
                    'transfer_type' => $request->type == 'debit' ? 'cash_to_bank' : 'bank_to_cash',
                ]);
            }

            DB::commit();
            sendNotification($cash->id, route('cashes.index'), __('New cash has been created.'));
            return response()->json([
                'message'   => __('Transaction successfully.'),
                'redirect'  => route('cashes.index')
            ]);

        } catch (\Exception $e){
            DB::rollback();
            return response()->json(__('Something went wrong!'),404);
        }
    }

    public function edit($id)
    {
        $cash = Cash::where('bank_id', '!=', 'new_party_create')->findOrFail($id);
        abort_if(!check_visibility($cash->created_at, 'cashes', $cash->user_id), 403);
        $banks = Bank::select('id','bank_name','account_number')->latest()->get();
        return view('pages.accounts.commercial.cash.edit', compact('cash','banks'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'cash_type' => 'nullable|string',
            'description' => 'nullable|string',
            'amount' => ['required', 'numeric', 'gt:0','max:99999999999'],
        ]);

        DB::beginTransaction();
        try {

            $cash = Cash::where('bank_id', '!=', 'new_party_create')->findOrFail($id);
            $permission = check_visibility($cash->created_at, 'cashes', $cash->user_id);
            if (!$permission) {
                return response()->json(__('You don\'t have permission.'), 403);
            }

            $cash_balance = cash_balance();

            if ($cash->bank_id == 'petty_cash') { // FOR CASH
                if ($cash->type == 'debit') {
                    $original_cash_balance = $cash_balance + $cash->amount;
                    if ($request->amount > $original_cash_balance) {
                        return response()->json(__('Amount can not more than current cash balance'), 400);
                    }
                }

                $voucher = Voucher::findOrFail($cash->voucher_id);
                $voucher->update($request->except('type', 'amount') + [
                    'user_id' => auth()->id(),
                    'amount' => $request->amount,
                    'remarks' => $request->description,
                    'current_balance' => $cash->type == 'debit' ? $voucher->prev_balance - $request->amount : $voucher->prev_balance + $request->amount,
                ]);

                $cash->update($request->except('bank_id', 'type') + [
                    'user_id' => auth()->id(),
                ]);

            } else {
                // FOR BANK
                $bank = Bank::findOrfail($cash->bank_id);
                $transfer = Transfer::where('voucher_id', $cash->voucher_id)->first();

                if ($cash->type == 'debit') {
                    $original_cash_balance = $cash_balance + $cash->amount;

                    if($request->amount <= $original_cash_balance) {
                        $bank->update([
                            'balance' => ($bank->balance - $transfer->amount) + $request->amount,
                        ]);
                    } else {
                        return response()->json(__('Amount can not more than main balance'), 400);
                    }
                } else {
                    if ($request->amount <= $bank->balance) {
                        $bank->update([
                            'balance' => ($bank->balance + $transfer->amount) - $request->amount,
                        ]);
                    } else {
                        return response()->json(__('Amount can not more than main balance'), 400);
                    }
                }

                $transfer->update($request->except('amount') + [
                    'user_id' => auth()->id(),
                    'amount' => $request->amount,
                    'note' => $request->description,
                ]);

                $cash->update($request->except('bank_id', 'type') + [
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();
            return response()->json([
                'message'   => __('Transaction successfully.'),
                'redirect'  => route('cashes.index')
            ]);

        } catch (\Exception $e){
            DB::rollback();
            return response()->json(__('Something went wrong!'), 400);
        }
    }

    public function destroy(Cash $cash)
    {
        $permission = check_visibility($cash->created_at, 'cashes', $cash->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }
        
        if ($cash->type == 'credit' && $cash->amount > cash_balance()) {
            return response()->json(__('You can not delete this for now, because you do not have enough cash balance.'), 403);
        }
        
        DB::beginTransaction();
        try {
            if ($cash->bank_id && $cash->bank_id != 'petty_cash') {
                $transfer = Transfer::where('date', $cash->date)->where('amount', $cash->amount)->where('adjust_type', $cash->type == 'credit' ? 'debit' : 'credit')->first();

                $bank = Bank::findOrFail($cash->bank_id);
                if ($cash->type == 'credit') {
                    $bank->update([
                        'balance' => $bank->balance + $cash->amount
                    ]);
                } else {
                    $bank->update([
                        'balance' => $bank->balance - $cash->amount
                    ]);
                }
                $transfer->delete();
            }

            if ($cash->voucher_id) {
                Voucher::find($cash->voucher_id)->delete();
            }
            $cash->delete();

            DB::commit();
            return response()->json([
                'message'   => __('Cash has been deleted successfully.'),
                'redirect'  => route('cashes.index')
            ]);
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(__('Something went wrong!'), 400);
        }
    }
}
