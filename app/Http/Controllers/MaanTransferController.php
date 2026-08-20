<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cash;
use App\Models\Transfer;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaanTransferController extends Controller
{
    private $transfer;
    public function __construct()
    {
        $this->middleware('permission:transfers-create')->only('create', 'store');
        $this->middleware('permission:transfers-read')->only('index', 'show');
        $this->middleware('permission:transfers-update')->only('edit', 'update');
        $this->middleware('permission:transfers-delete')->only('destroy');
    }

    public function index()
    {
        $bank = Bank::findOrFail(request('bank'));
        $transfers = Transfer::with('sender_bank', 'receiver_bank')
                        ->where('bank_from', $bank->id)
                        ->orWhere('bank_to', $bank->id)
                        ->when(!auth()->user()->can('transfers-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->latest()
                        ->paginate(10);

        return view('pages.accounts.commercial.bank.transfer.index', compact('transfers', 'bank'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string',
            'transfer_type' => 'required|string',
            'amount' => 'required|numeric|min:1|max:99999999999',
        ]);

        if (in_array($request->transfer_type, ['bank_to_bank', 'bank_to_cash']) || $request->transfer_type == 'adjust_bank' && $request->adjust_type == 'withdraw') {
            $request->validate([
                'bank_from' => 'required|integer|exists:banks,id',
            ]);
        }
        if (in_array($request->transfer_type, ['bank_to_bank', 'cash_to_bank']) || $request->transfer_type == 'adjust_bank' && $request->adjust_type == 'deposit') {
            $request->validate([
                'bank_to' => 'required|integer|exists:banks,id',
            ]);
        }

        $bank_to = Bank::find($request->bank_to);
        $bank_from = Bank::find($request->bank_from);

        DB::beginTransaction();
        try {
            if ($request->transfer_type == 'bank_to_bank') {
                if ($request->bank_from != $request->bank_to) {
                    if ($request->amount <= $bank_from->balance) {

                        $bank_from->update([
                            'balance' => $bank_from->balance - $request->amount
                        ]);
                        $bank_to->update([
                            'balance' => $bank_to->balance + $request->amount
                        ]);
                        $transfer = Transfer::create($request->all() + [
                            'user_id' => auth()->id(),
                            'adjust_type' => 'others'
                        ]);

                    } else {
                        return response()->json(__('Transfer amount can not be more than account balance'), 400);
                    }
                } else {
                    return response()->json(__("You can't transfer money between same bank."), 400);
                }
            } else if ($request->transfer_type == 'bank_to_cash') {
                if ($request->amount <= $bank_from->balance) {

                    $bank_from->update([
                        'balance' => $bank_from->balance - $request->amount
                    ]);

                    Cash::create($request->except('amount') + [
                        'type' => 'credit',
                        'user_id' => auth()->id(),
                        'description' => $request->note,
                        'remarks' => $request->cash_type,
                        'bank_id' => $request->bank_from,
                        'amount' => $request->amount,
                    ]);

                    $transfer = Transfer::create([
                        'user_id' => auth()->id(),
                        'adjust_type' => 'debit',
                        'amount' => $request->amount,
                        'meta' => [
                            'cash_type' => $request->cash_type,
                        ],
                    ] + $request->except('bank_to', 'amount'));

                } else {
                    return response()->json(__('Transfer amount can not be more than account balance'), 400);
                }
            } else if ($request->transfer_type == 'cash_to_bank') {
                if ($request->amount <= cash_balance()) {

                    $bank_to->update([
                        'balance' => $bank_to->balance + $request->amount
                    ]);

                    Cash::create($request->except('amount') + [
                        'type' => 'debit',
                        'user_id' => auth()->id(),
                        'amount' => $request->amount,
                        'bank_id' => $request->bank_to,
                        'description' => $request->note,
                    ]);

                    $transfer = Transfer::create([
                        'user_id' => auth()->id(),
                        'adjust_type' => 'credit',
                    ] + $request->except('bank_from'));

                } else {
                    return response()->json(__('Transfer amount can not be more than account balance'), 400);
                }
            } else if ($request->transfer_type == 'adjust_bank') {

                $prev_balance = Bank::sum('balance');

                if ($request->adjust_type == 'withdraw') {
                    if ($request->amount <= $bank_from->balance) {

                        $bank_from->update([
                            'balance' => $bank_from->balance - $request->amount
                        ]);

                    } else {
                        return response()->json(__('Transfer amount can not be more than account balance'), 400);
                    }
                } elseif ($request->adjust_type == 'deposit') {

                    $bank_to->update([
                        'balance' => $bank_to->balance + $request->amount
                    ]);

                } else {
                    return response()->json(__('Invalid adjustment type.'), 400);
                }

                $voucher = Voucher::create($request->except('type') + [
                    'user_id' => auth()->id(),
                    'payment_method' => 'bank',
                    'remarks' => $request->note,
                    'prev_balance' => $prev_balance,
                    'particulars' => 'different_module',
                    'current_balance' => Bank::sum('balance'),
                    'type' => $request->adjust_type == 'withdraw' ? 'debit':'credit',
                ]);

                $transfer = Transfer::create($request->except($request->adjust_type == 'withdraw' ? 'bank_to':'bank_from') + [
                    'user_id' => auth()->id(),
                    'voucher_id' => $voucher->id,
                    'adjust_type' => $request->adjust_type == 'withdraw' ? 'debit':'credit',
                ]);
            }

            DB::commit();
            sendNotification($transfer->id, route('transfers.index', ['bank' => $bank_to ?? $bank_from]), __('New Transfer has been created.'));

            return response()->json([
                'message'   => __('Balance transfer successfully'),
                'redirect'  => route('transfers.index', ['bank' => $bank_to ?? $bank_from])
            ]);

        } catch (\Exception $e){
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function edit($id)
    {
        $transfer = Transfer::findOrfail($id);
        $bankInfo = Bank::select('id','bank_name','account_number')->latest()->get();
        abort_if(!check_visibility($transfer->created_at, 'transfers', $transfer->user_id), 403);
        return view('pages.accounts.commercial.bank.transfer.edit',compact('bankInfo','transfer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string',
            'amount' => 'required|numeric|min:1|max:99999999999',
        ]);

        DB::beginTransaction();
        try {
            
            $transfer = Transfer::findOrfail($id);
            $permission = check_visibility($transfer->created_at, 'transfers', $transfer->user_id);
            if (!$permission) {
                return response()->json(__('You don\'t have permission.'), 403);
            }

            $bank_to = Bank::find($transfer->bank_to);
            $bank_from = Bank::find($transfer->bank_from);

            if ($transfer->transfer_type == 'bank_to_bank') {
                if ($transfer->bank_from != $transfer->bank_to) {
                    if ($request->amount <= $bank_from->balance) {

                        $bank_from->update([
                            'balance' => ($bank_from->balance + $transfer->amount) - $request->amount
                        ]);

                        $bank_to->update([
                            'balance' => ($bank_to->balance - $transfer->amount) + $request->amount
                        ]);

                        $transfer->update($request->except('transfer_type', 'bank_from', 'bank_to') + [
                            'user_id' => auth()->id(),
                        ]);

                    } else {
                        return response()->json(__('Transfer amount can not be more than account balance'), 400);
                    }
                } else {
                    return response()->json(__("You can't transfer money between same bank."), 400);
                }
            } else if ($transfer->transfer_type == 'bank_to_cash') {
                if ($request->amount <= $bank_from->balance) {

                    $bank_from->update([
                        'balance' => ($bank_from->balance + $transfer->amount) - $request->amount
                    ]);

                    $cash = Cash::where('bank_id', $transfer->bank_from)->whereType('credit')->where('amount', $transfer->amount)->firstOrFail();

                    $cash->update($request->all() + [
                        'user_id' => auth()->id(),
                        'description' => $request->note,
                    ]);

                    $transfer->update($request->except('transfer_type', 'bank_from', 'bank_to') + [
                        'user_id' => auth()->id(),
                    ]);

                } else {
                    return response()->json(__('Transfer amount can not be more than account balance'), 400);
                }
            } else if ($transfer->transfer_type == 'cash_to_bank') {

                $cash_balance = cash_balance() + $transfer->amount;
                if ($request->amount <= $cash_balance) {

                    $bank_to->update([
                        'balance' => ($bank_to->balance - $transfer->amount) + $request->amount
                    ]);

                    $cash = Cash::where('bank_id', $transfer->bank_to)->whereType('debit')->where('amount', $transfer->amount)->firstOrFail();

                    $cash->update($request->all() + [
                        'user_id' => auth()->id(),
                        'description' => $request->note,
                    ]);

                    $transfer->update($request->except('transfer_type', 'bank_from', 'bank_to') + [
                        'user_id' => auth()->id(),
                    ]);

                } else {
                    return response()->json(__('Transfer amount can not be more than account balance'), 400);
                }
            } else if ($transfer->transfer_type == 'adjust_bank') {
                if ($transfer->adjust_type == 'withdraw') {
                    if ($request->amount <= $bank_from->balance) {

                        $bank_from->update([
                            'balance' => ($bank_from->balance + $transfer->amount) - $request->amount
                        ]);

                    } else {
                        return response()->json(__('Transfer amount can not be more than account balance'), 400);
                    }
                } elseif (($transfer->adjust_type == 'deposit')) {

                    $bank_to->update([
                        'balance' => ($bank_to->balance - $transfer->amount) + $request->amount
                    ]);

                } else {
                    return response()->json(__('Invalid adjustment type.'), 400);
                }

                $voucher = Voucher::where('amount', $transfer->amount)->where('type', $transfer->adjust_type == 'withdraw' ? 'debit':'credit')->first();
                $voucher->update($request->except('type') + [
                    'user_id' => auth()->id(),
                    'payment_method' => 'bank',
                    'remarks' => $request->note,
                    'current_balance' => $voucher->prev_balance + $request->amount,
                ]);

                $transfer->update($request->except('transfer_type', 'bank_from', 'bank_to', 'adjust_type') + [
                    'user_id' => auth()->id(),
                ]);

            }

            DB::commit();
            return response()->json([
                'message'   => __('Balance transfer updated successfully'),
                'redirect'  => route('transfers.index', ['bank' => $bank_to ?? $bank_from])
            ]);

        } catch (\Exception $e){
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }
}
