<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cash;
use App\Models\Party;
use App\Models\Cheque;
use App\Models\Income;
use App\Models\Voucher;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DueCollectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'remarks' => 'nullable|string|max:100',
            'party_id' => 'required|exists:parties,id',
            'cheque_no' => 'required_if:payment_method,cheque',
            'issue_date' => 'required_if:payment_method,cheque',
            'amount' => 'required|numeric|gt:0|max:999999999999',
            'bank_id' => 'required_if:payment_method,bank,cheque',
            'payment_method' => 'required|string|max:20|in:bank,cheque,party_balance,cash',
        ]);

        $party = Party::findOrFail($request->party_id);
        
        if (!in_array($party->type, ['buyer', 'customer'])) {
            return response()->json(__('Please select a valid Buyer/Customer.'), 400);
        }

        if ($request->income_id) {
            $income = Income::findOrFail($request->income_id);
            if ($income->total_due < $request->amount) {
                return response()->json(__('Invoice due is ' . $income->due_amount . '. You can not pay more than the invoice due amount.'), 400);
            }
        } else {
            $all_invoice_due = Income::where('party_id', $request->party_id)->sum('total_due');
            if (($all_invoice_due + $request->amount) > $party->due_amount) {
                return response()->json([
                    'message' => __('You can only pay '. currency_format($party->due_amount - $all_invoice_due) .', without selecting an invoice.')
                ], 400);
            }
        }

        DB::beginTransaction();
        try {

            if ($request->payment_method != 'cheque' && $request->income_id) {
                $income = Income::findOrFail($request->income_id);
                $income->update([
                    'total_due' => $income->total_due - $request->amount,
                    'total_paid' => $income->total_paid + $request->amount,
                ]);
            }

            if ($request->payment_method != 'party_balance' && $request->payment_method != 'cheque') {
                $party->update([
                    'due_amount' => $party->due_amount - $request->amount,
                    'pay_amount' => $party->pay_amount + $request->amount,
                ]);
            }

            $bank_balance = Bank::sum('balance');

            $voucher = Voucher::create($request->all() + [
                'type' => 'credit',
                'is_profit' => 1, // Check role number 4 in dev_docs.txt.
                'bill_type' => 'due_bill',
                'prev_balance' => $bank_balance,
                'status' => $request->payment_method == 'cheque' ? 0 : 1,
                'current_balance' => $request->payment_method == 'party_balance' ? $bank_balance : ($bank_balance + $request->amount),
                'meta' => [
                    'cheque_no' => $request->cheque_no,
                    'issue_date' => $request->issue_date,
                    'due_amount' => $party->due_amount,
                ],
            ]);

            if ($request->payment_method == 'bank') {

                $bank = Bank::findOrFail($request->bank_id);
                Transfer::create($request->all() + [
                    'user_id' => auth()->id(),
                    'bank_to' => $bank->id,
                    'adjust_type' => 'credit',
                    'note' => $request->remarks,
                    'voucher_id' => $voucher->id,
                    'transfer_type' => 'adjust_bank',
                ]);

                $bank->update([
                    'balance' => $bank->balance + $request->amount,
                ]);

            } elseif ($request->payment_method == 'cash') {

                Cash::create($request->all() + [
                    'type' => 'credit',
                    'user_id' => auth()->id(),
                    'voucher_id' => $voucher->id,
                    'description' => $request->remarks,
                    'bank_id' => 'party_payment',
                    'cash_type' => 'party_payment',
                ]);

            } elseif ($request->payment_method == 'cheque') {

                Cheque::create($request->all() + [
                    'type' => 'credit',
                    'user_id' => auth()->id(),
                    'voucher_id' => $voucher->id,
                ]);

            } elseif ($request->payment_method == 'party_balance') {
                if ($request->amount <= $party->balance) {
                    if ($request->amount <= $party->due_amount) {
                        $party->update([
                            'balance' => $party->balance - $request->amount,
                            'due_amount' => $party->due_amount - $request->amount,
                            'pay_amount' => $party->pay_amount + $request->amount,
                        ]);
                    } else {
                        return response()->json(__('Party due is ' . $party->due_amount . '. You can\'t pay more than the due balance using the party balance.'), 400);
                    }
                } else {
                    return response()->json(__('Insufficient party balance.'), 400);
                }
            } else {
                return response()->json(__('Please select a valid payment method.'), 400);
            }

            DB::commit();
            sendNotification($voucher->id, route('credit-vouchers.index'), __('New due collection has been created.'));
            return response()->json([
                'message' => __('Due collection added successfully.'),
                'redirect'  => route('credit-vouchers.index'),
                'another_page' => route('invoices.voucher', $voucher->id),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }
}
