<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cash;
use App\Models\Cheque;
use App\Models\Income;
use App\Models\Party;
use App\Models\Transfer;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaanCreditVoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:credit-vouchers-create')->only('store');
        $this->middleware('permission:credit-vouchers-read')->only('index');
    }

    public function index()
    {
        $vouchers = Voucher::where('type', 'credit')
            ->where(function ($query) {
                $query->where('particulars', '!=', 'different_module')
                    ->orWhereNull('particulars');
            })
            ->with('user:id,name', 'party:id,name', 'income:id,category_name')
            ->when(!auth()->user()->can('credit-vouchers-list'), function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('party_id', auth()->id());
            })
            ->latest()
            ->paginate();

        return view('pages.accounts.general.credit-vouchers.index', compact('vouchers'));
    }

    public function maanFilter(Request $request)
    {
        $vouchers = Voucher::where('type', 'credit')
            ->with('user:id,name', 'party:id,name', 'income:id,category_name')
            ->when($request->has('search'), function ($q) use ($request) {
                $q->Where('payment_method', 'like', '%' . $request->search . '%')
                    ->orWhere('voucher_no', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('party', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('income', function ($q) use ($request) {
                $q->where('category_name', 'like', '%' . $request->search . '%');
            })
            ->when(!auth()->user()->can('credit-vouchers-list'), function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('party_id', auth()->id());
            })
            ->latest()
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.accounts.general.credit-vouchers.datas', compact('vouchers'))->render(),
            ]);
        }
        return redirect(url()->previous());
    }

    public function create()
    {
        $banks = Bank::latest()->get();
        $parties = Party::with('user')->latest()->get();
        return view('pages.accounts.general.credit-vouchers.create', compact('parties', 'banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'required',
            'date' => 'required|date',
            'bill_type' => 'nullable|string',
            'remarks' => 'nullable|string|max:100',
            'voucher_no' => 'nullable|string|max:50',
            'income_id' => 'nullable|exists:incomes,id',
            'payment_method' => 'required|string|max:20',
            'cheque_no' => 'required_if:payment_method,cheque',
            'issue_date' => 'required_if:payment_method,cheque',
            'amount' => 'required|numeric|gt:0|max:999999999999',
            'bank_id' => 'required_if:payment_method,bank,cheque',
        ]);

        $party = Party::find($request->party_id);

        if ($request->payment_method == 'party_balance' && !$party) {
            return response()->json(__('Please select a party or select another payment method.'), 400);
        }
        if (!in_array($request->bill_type, ['advance_payment', 'due_bill', 'balance_withdraw']) && $request->party_id != 'others') {
            return response()->json(__('Please select a valid bill type.'), 400);
        }

        if ($request->party_id != 'others' && $party) {
            if ($request->bill_type == 'due_bill') {
                if ($request->income_id) {
                    $income = Income::findOrFail($request->income_id);
                    if ($income->total_due < $request->amount) {
                        return response()->json(__('Invoice due is ' . $income->total_due . '. You can not pay more than the invoice due amount.'), 400);
                    }
                } else {
                    $all_invoice_due = Income::where('party_id', $request->party_id)->sum('total_due');
                    if (($all_invoice_due + $request->amount) > $party->due_amount) {
                        return response()->json([
                            'message' => __('You can only pay '. currency_format($party->due_amount - $all_invoice_due) .', without selecting an invoice.')
                        ], 400);
                    }
                }
            }
            if ($party->due_amount <= 0 && ($request->bill_type == 'due_bill' || $request->payment_method == 'party_balance')) {
                return response()->json(__('Party due is ' . $party->due_amount . '. Please select another bill type.'), 400);
            }
            if (in_array($party->type, ['supplier']) && $party->balance < $request->amount) {
                return response()->json(__('Party balance is ' . $party->balance . '. The given amount can not be more than the party balance.'), 400);
            }
            if (in_array($party->type, ['supplier']) && $request->payment_method === 'party_balance') {
                return response()->json(__('You can not select party balance when you will withdraw the amount from the supplier balance.'), 400);
            }
            if ((in_array($party->type, ['supplier']) && $request->bill_type !== 'balance_withdraw') || (!in_array($party->type, ['supplier']) && $request->bill_type === 'balance_withdraw')) {
                return response()->json(__('Please change the bill type for this party.'), 400);
            }
        }

        DB::beginTransaction();
        try {
            if ($party && $request->payment_method != 'cheque' && $request->bill_type == 'due_bill' && $request->income_id) {
                $income = Income::findOrFail($request->income_id);
                $income->update([
                    'total_due' => $income->total_due - $request->amount,
                    'total_paid' => $income->total_paid + $request->amount,
                ]);
            }

            if ($request->payment_method != 'party_balance' && $request->payment_method != 'cheque' && $request->party_id != 'others') {
                if ($request->bill_type == 'advance_payment') {
                    $party->update([
                        'balance' => $party->balance + $request->amount,
                        'advance_amount' => $party->advance_amount + $request->amount,
                    ]); 
                } elseif ($request->bill_type == 'due_bill') {
                    $party->update([
                        'due_amount' => $party->due_amount - $request->amount,
                        'pay_amount' => $party->pay_amount + $request->amount,
                    ]);
                } elseif ($request->bill_type == 'balance_withdraw') {
                    $party->update([
                        'balance' => $party->balance - $request->amount,
                        'advance_amount' => $party->advance_amount - $request->amount,
                    ]);
                }
            }

            $company_balance = company_balance();

            $voucher = Voucher::create($request->except('party_id') + [
                'type' => 'credit',
                'prev_balance' => $company_balance,
                'is_profit' => $request->bill_type == 'due_bill' ? 1 : 0, // Check role number 4 in doc.
                'status' => $request->payment_method == 'cheque' ? 0 : 1,
                'party_id' => $request->party_id != 'others' ? $request->party_id : NULL,
                'current_balance' => $request->payment_method == 'party_balance' ? $company_balance : $company_balance + $request->amount,
                'meta' => [
                    'cheque_no' => $request->cheque_no,
                    'issue_date' => $request->issue_date,
                    'due_amount' => $party->due_amount ?? 0,
                ],
            ]);

            if ($request->payment_method == 'cash') {
                Cash::create($request->all() + [
                    'type' => 'credit',
                    'user_id' => auth()->id(),
                    'voucher_id' => $voucher->id,
                    'description' => $request->remarks,
                    'bank_id' => $party && in_array($party->type, ['supplier']) ? 'balance_withdraw' : 'party_payment',
                    'cash_type' => $party && in_array($party->type, ['supplier']) ? 'balance_withdraw' : 'party_payment',
                ]);

            } elseif ($request->payment_method == 'bank') {

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

            } elseif ($request->payment_method == 'cheque') {

                Cheque::create($request->except('party_id') + [
                    'type' => 'credit',
                    'user_id' => auth()->id(),
                    'voucher_id' => $voucher->id,
                    'party_id' => $request->party_id != 'others' ? $request->party_id : NULL,
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
            sendNotification($voucher->id, route('credit-vouchers.index'), __('New credit voucher has been created.'));
            return response()->json([
                'message' => __('Credit voucher created successfully'),
                'redirect'  => route('credit-vouchers.index'),
                'another_page' => route('invoices.voucher', $voucher->id),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function edit($id)
    {
        $banks = Bank::latest()->get();
        $parties = Party::with('user')->latest()->get();
        $voucher = Voucher::with('party', 'income')->findOrFail($id);
        $incomes = Income::when($voucher->party_id, function($q) use($voucher) {
                        $q->where('party_id', $voucher->party_id);
                    })
                    ->when(!$voucher->party_id, function($q) {
                        $q->whereNull('party_id');
                    })
                    ->latest()
                    ->get();
        
        abort_if(!check_visibility($voucher->created_at, 'credit-vouchers', $voucher->user_id), 403);

        return view('pages.accounts.general.credit-vouchers.edit', compact('voucher', 'banks', 'incomes', 'parties'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'bill_type' => 'nullable|string',
            'remarks' => 'nullable|string|max:100',
            'voucher_no' => 'nullable|string|max:50',
            'income_id' => 'nullable|exists:incomes,id',
            'payment_method' => 'required|string|max:20',
            'cheque_no' => 'required_if:payment_method,cheque',
            'issue_date' => 'required_if:payment_method,cheque',
            'amount' => 'required|numeric|gt:0|max:999999999999',
            'bank_id' => 'required_if:payment_method,bank,cheque',
        ]);

        $voucher = Voucher::findOrFail($id);
        $party = Party::find($voucher->party_id);
        $prev_invoice = Income::find($voucher->income_id);

        $permission = check_visibility($voucher->created_at, 'credit-vouchers', $voucher->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        if ($request->payment_method == 'party_balance' && !$party) {
            return response()->json(__('Please select a party or select another payment method.'), 400);
        }
        if (!in_array($request->bill_type, ['advance_payment', 'due_bill', 'balance_withdraw']) && $voucher->party_id != '') {
            return response()->json(__('Please select a valid bill type.'), 400);
        }

        if ($request->party_id != 'others' && $party) {

            $new_invoice = Income::find($request->income_id);
            $party_balance = $party->balance + $voucher->amount;
            $party_due = $party->due_amount + $voucher->amount;

            if ($request->bill_type == 'due_bill') {
                if ($request->income_id && !$voucher->income_id) {
                    if ($new_invoice->total_due < $request->amount) {
                        return response()->json(__('Invoice due is ' . $new_invoice->total_due . '. You can not pay more than the invoice due amount.'), 400);
                    }
                } else {
                    $all_invoice_due = Income::where('party_id', $request->party_id)->sum('total_due');
                    if (($all_invoice_due + $request->amount) > $party_due) {
                        return response()->json([
                            'message' => __('You can only pay '. currency_format(($all_invoice_due + $voucher->amount) - $party_due) .', without selecting an invoice.')
                        ], 400);
                    }
                }
            }

            if ($voucher->bill_type == 'advance_payment' && $request->payment_method == 'party_balance' && $request->amount > ($party->balance - $voucher->amount)) {
                return response()->json([
                    'message' => __('Party balance is ' . ($party->balance - $voucher->amount) . '. The given amount can not be more than the party balance.')
                ], 400);
            }
            if (in_array($party->type, ['supplier']) && $party_balance < $request->amount) {
                return response()->json(__('Party balance is ' . $party_balance . '. The given amount can not be more than the party balance.'), 400);
            }
            if (in_array($party->type, ['supplier']) && $request->payment_method === 'party_balance') {
                return response()->json(__('You can not select party balance when you will withdraw the amount from the supplier balance.'), 400);
            }
            if ((in_array($party->type, ['supplier']) && $request->bill_type !== 'balance_withdraw') || (!in_array($party->type, ['supplier']) && $request->bill_type === 'balance_withdraw')) {
                return response()->json(__('Please change the bill type for this party.'), 400);
            }
        }

        DB::beginTransaction();
        try {
            // IF PREVIOUS OR CURRENT PAYMENT METHOD IS CASH
            if ($voucher->payment_method == 'cash' && $request->payment_method != 'cash') {
                $cash = Cash::where('voucher_id', $voucher->id)->first();
                $cash->forceDelete();
            } elseif ($voucher->payment_method == 'cash' && $request->payment_method == 'cash') {
                $cash = Cash::where('voucher_id', $voucher->id)->first();
                $cash->update($request->all() + [
                    'user_id' => auth()->id(),
                    'description' => $request->remarks,
                ]);
            } elseif ($voucher->payment_method != 'cash' && $request->payment_method == 'cash') {
                Cash::create($request->except('amount') + [
                    'type' => 'credit',
                    'user_id' => auth()->id(),
                    'bank_id' => 'party_payment',
                    'voucher_id' => $voucher->id,
                    'description' => $request->remarks,
                    'cash_type' => ($party && in_array($party->type, ['supplier']) ? 'balance_withdraw' : 'party_payment'),
                ]);
            }

            // IF PREVIOUS OR CURRENT PAYMENT METHOD IS BANK
            if ($voucher->payment_method == 'bank' && $request->payment_method != 'bank') {
                $transfer = Transfer::where('voucher_id', $voucher->id)->first();
                $transfer->forceDelete();
                $bank = Bank::findOrFail($voucher->bank_id);
                $bank->update([
                    'balance' => $bank->balance - $voucher->amount,
                ]);
            } elseif ($voucher->payment_method == 'bank' && $request->payment_method == 'bank') {

                if ($voucher->bank_id != $request->bank_id) {
                    $bank = Bank::findOrFail($voucher->bank_id);
                    $bank->update([
                        'balance' => $bank->balance - $voucher->amount,
                    ]);
                }

                $current_bank = Bank::findOrFail($request->bank_id);
                $current_bank->update([
                    'balance' => $voucher->bank_id == $request->bank_id ? ($current_bank->balance - $voucher->amount) + $request->amount : ($current_bank->balance + $request->amount),
                ]);

                $transfer = Transfer::where('voucher_id', $voucher->id)->first();
                $transfer->update($request->all() + [
                    'user_id' => auth()->id(),
                    'note' => $request->remarks,
                    'bank_to' => $request->bank_id,
                ]);
            } elseif ($voucher->payment_method != 'bank' && $request->payment_method == 'bank') {
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
            }

            // IF PREVIOUS OR CURRENT PAYMENT METHOD IS CHEQUE
            if ($voucher->payment_method == 'cheque' && $request->payment_method != 'cheque') {
                $cheque = Cheque::where('voucher_id', $voucher->id)->first();
                if ($cheque->status == 0) {
                    $cheque->forceDelete();
                } else {
                    return response()->json(__('You can\'t edit this transaction, because this cheque has already been withdrawn.'), 400);
                }
            } elseif ($voucher->payment_method == 'cheque' && $request->payment_method == 'cheque') {
                $cheque = Cheque::where('voucher_id', $voucher->id)->first();
                if ($cheque->status == 0) {
                    $cheque->update($request->except('party_id') + [
                        'user_id' => auth()->id(),
                    ]);
                } else {
                    return response()->json(__('You can\'t edit this transaction, because this cheque has already been withdrawn.'), 400);
                }
            } elseif ($voucher->payment_method != 'cheque' && $request->payment_method == 'cheque') {
                Cheque::create($request->except('party_id') + [
                    'type' => 'credit',
                    'user_id' => auth()->id(),
                    'voucher_id' => $voucher->id,
                    'party_id' => $voucher->party_id,
                ]);

                $is_party_blnc = $voucher->payment_method == 'party_balance' ? true:false;
                $this->othersToCheque($voucher, $party, $voucher->amount, $is_party_blnc);
            }

            // IF PREVIOUS OR CURRENT PAYMENT METHOD IS PARTY BALANCE
            if ($voucher->payment_method == 'party_balance' && $request->payment_method != 'party_balance') {

                $party->balance = $party->balance + $voucher->amount;
                $party->due_amount = $party->due_amount + $voucher->amount;
                $party->pay_amount = $party->pay_amount - $voucher->amount;
                $party->save();

            } elseif ($voucher->payment_method == 'party_balance' && $request->payment_method == 'party_balance') {

                $party_balance = $party->balance + $voucher->amount;
                if ($request->amount <= $party_balance) {
                    $party->update([
                        'balance' => $party_balance - $request->amount,
                        'due_amount' => ($party->due_amount + $voucher->amount) - $request->amount,
                        'pay_amount' => ($party->pay_amount - $voucher->amount) + $request->amount,
                    ]);
                } else {
                    return response()->json(__('Insufficient party balance.'), 400);
                }

            } elseif ($voucher->payment_method != 'party_balance' && $request->payment_method == 'party_balance') {
                $prev_due_paid = $voucher->meta['due_bill'] ?? 0;
                $prev_added_to_wallet = $voucher->amount - $prev_due_paid; // Which is added user wallet when we pay the due_bill < $request->amount
                if ($request->amount <= ($party->balance - $prev_added_to_wallet)) {
                    $is_cheque = $voucher->payment_method === 'cheque' ? true:false;
                    $party->update([
                        'pay_amount' => $is_cheque ? ($party->pay_amount + $request->amount) : (($party->pay_amount - $prev_due_paid) + $request->amount),
                        'due_amount' => $is_cheque ? ($party->due_amount - $request->amount) : (($party->due_amount + $prev_due_paid) - $request->amount),

                        'balance' => ($party->balance - $prev_added_to_wallet) - $request->amount,
                        'advance_amount' => $party->advance_amount - $prev_added_to_wallet,
                    ]);
                } else {
                    return response()->json(__('Insufficient party balance.'), 400);
                }
            }
            
            if ($voucher->payment_method != 'cheque' && $request->payment_method == 'cheque' && $voucher->bill_type == 'due_bill' && $party) {
                $prev_invoice->update([
                    'total_due' => $prev_invoice->total_due + $voucher->amount,
                    'total_paid' => $prev_invoice->total_paid - $voucher->amount,
                ]);
            }

            if (!in_array($request->payment_method, ['party_balance', 'cheque']) && $party) {

                $prev_is_cq = $voucher->payment_method === 'cheque' ? true : false;

                if ($request->bill_type == 'advance_payment') {

                    $prev_is_deu = $voucher->bill_type == 'due_bill' ? true : false; // Previous(Voucher) is due bill?

                    if (!$prev_is_cq && $prev_is_deu && $prev_invoice) {
                        $prev_invoice->update([
                            'total_due' => $prev_invoice->total_due + $voucher->amount,
                            'total_paid' => $prev_invoice->total_paid - $voucher->amount,
                        ]);
                    }

                    $party->update([
                        'pay_amount' => $prev_is_deu && !$prev_is_cq ? ($party->pay_amount - $voucher->amount) : $party->pay_amount,
                        'due_amount' => $prev_is_deu && !$prev_is_cq ? ($party->due_amount + $voucher->amount) : $party->due_amount,
                        'balance' => $prev_is_deu || $prev_is_cq ? ($party->balance + $request->amount) : (($party->balance - $voucher->amount) + $request->amount),
                        'advance_amount' => $prev_is_deu || $prev_is_cq ? ($party->advance_amount + $request->amount) : (($party->advance_amount - $voucher->amount) + $request->amount),
                    ]);

                } elseif ($request->bill_type == 'due_bill') {

                    $prev_is_advnc = $voucher->bill_type === 'advance_payment' ? true : false;

                    if ($request->income_id == $voucher->income_id && !$prev_is_cq) {
                        $prev_invoice->update([
                            'total_due' => $prev_is_advnc ? ($prev_invoice->total_due - $request->amount) : ($prev_invoice->total_due + $voucher->amount) - $request->amount,
                            'total_paid' => $prev_is_advnc ? ($prev_invoice->total_paid + $request->amount) : ($prev_invoice->total_paid - $voucher->amount) + $request->amount,
                        ]);
                    } else {
                        if ($prev_invoice && !$prev_is_advnc) {
                            $prev_invoice->update([
                                'total_due' => $prev_invoice->total_due + $voucher->amount,
                                'total_paid' => $prev_invoice->total_paid - $voucher->amount,
                            ]);
                        }
                        
                        if ($new_invoice) {
                            $new_invoice->update([
                                'total_due' => $new_invoice->total_due - $request->amount,
                                'total_paid' => $new_invoice->total_paid + $request->amount,
                            ]);
                        }
                    }

                    $party->update([
                        'pay_amount' => $prev_is_advnc || $prev_is_cq ? ($party->pay_amount + $request->amount) : (($party->pay_amount - $voucher->amount) + $request->amount),
                        'due_amount' => $prev_is_advnc || $prev_is_cq ? ($party->due_amount - $request->amount) : (($party->due_amount + $voucher->amount) - $request->amount),
                        'balance' => $prev_is_advnc && !$prev_is_cq ? ($party->balance - $voucher->amount) : $party->balance,
                        'advance_amount' => $prev_is_advnc && !$prev_is_cq ? ($party->advance_amount - $voucher->amount) : $party->advance_amount,
                    ]);
                } elseif ($request->bill_type == 'balance_withdraw') {
                    $party->update([
                        'balance' => ($party->balance + $voucher->amount) - $request->amount,
                        'advance_amount' => ($party->advance_amount + $voucher->amount) - $request->amount,
                    ]);
                }
            }

            $current_balance = $request->payment_method == 'party_balance' ? $voucher->prev_balance : ($voucher->prev_balance + $request->amount);

            $voucher->update($request->except('party_id') + [
                'is_profit' => $request->bill_type == 'due_bill' ? 1 : 0,
                'status' => $request->payment_method == 'cheque' ? 0 : 1,
                'current_balance' => $current_balance,
                'meta' => [
                    'cheque_no' => $request->cheque_no,
                    'issue_date' => $request->issue_date,
                ],
            ]);

            DB::commit();
            sendNotification($voucher->id, route('credit-vouchers.index'), __('Credit voucher has been updated.'));
            return response()->json([
                'message' => __('Credit voucher updated successfully'),
                'redirect'  => route('credit-vouchers.index'),
                'another_page' => route('invoices.voucher', $voucher->id),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $voucher = Voucher::findOrFail($id);
            $party = Party::find($voucher->party_id);

            if ($voucher->payment_method == 'cash') {
                $cash = Cash::where('voucher_id', $voucher->id)->first();
                $cash->delete();
            } elseif ($voucher->payment_method == 'bank') {

                $transfer = Transfer::where('voucher_id', $voucher->id)->first();
                $transfer->delete();
                $bank = Bank::findOrFail($voucher->bank_id);
                $bank->update([
                    'balance' => $bank->balance - $voucher->amount,
                ]);

            } elseif ($voucher->payment_method == 'cheque') {
                $cheque = Cheque::withTrashed()->where('voucher_id', $voucher->id)->first();
                if ($cheque->status == 0) {
                    $cheque->delete();
                } else {
                    return response()->json(__('You can\'t delete this transaction, because this cheque has already been withdrawn.'), 400);
                }
            } elseif ($voucher->payment_method == 'party_balance') {
                $party->update([
                    'balance' => $party->balance + $voucher->amount,
                    'due_amount' => $party->due_amount + $voucher->amount,
                    'pay_amount' => $party->pay_amount - $voucher->amount,
                ]);
            }

            if ($party && $voucher->payment_method != 'cheque' && $voucher->bill_type == 'due_bill') {
                $income = Income::findOrFail($voucher->income_id);
                $income->update([
                    'total_due' => $income->total_due + $voucher->amount,
                    'total_paid' => $income->total_paid - $voucher->amount,
                ]);
            }

            if ($party && !in_array($voucher->payment_method, ['party_balance', 'cheque'])) {
                if ($voucher->bill_type == 'advance_payment') {
                    $party->update([
                        'balance' => $party->balance - $voucher->amount,
                        'advance_amount' => $party->advance_amount - $voucher->amount,
                    ]);
                } elseif ($voucher->bill_type == 'due_bill') {
                    $party->update([
                        'due_amount' => $party->due_amount + $voucher->amount,
                        'pay_amount' => $party->pay_amount - $voucher->amount,
                    ]);
                } elseif ($voucher->bill_type == 'balance_withdraw') {
                    $party->update([
                        'balance' => $party->balance + $voucher->amount,
                    ]);
                }
            }

            $voucher->delete();

            DB::commit();
            return response()->json([
                'message' => __('Credit voucher deleted successfully'),
                'redirect' => route('credit-vouchers.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    private function othersToCheque($voucher, $party, $prev_amount, $is_party_blnc)
    {
        if ($party) {
            if ($voucher->bill_type == 'advance_payment') {
                $party->update([
                    'balance' => $party->balance - $prev_amount,
                    'advance_amount' => $party->advance_amount - $prev_amount,
                ]);
            } elseif ($voucher->bill_type == 'due_bill') {
                $party->update([
                    'due_amount' => $party->due_amount + $voucher->amount,
                    'pay_amount' => $party->pay_amount - $voucher->amount,
                    'balance' => $is_party_blnc ? ($party->balance + $voucher->amount) : $party->balance,
                ]);
            } elseif ($voucher->bill_type == 'balance_withdraw') {
                $party->update([
                    'balance' => $party->balance + $voucher->amount,
                ]);
            }
        }
    }

    public function getInvoices($party_id) 
    {
        if ($party_id) {
            if ($party_id == 'others') {
                $invoices = Income::whereNull('party_id')->get();
            } else {
                $invoices = Income::where('party_id', $party_id)->get();
            }
        } else {
            $invoices = [];
        }

        return response()->json($invoices);
    }
}
