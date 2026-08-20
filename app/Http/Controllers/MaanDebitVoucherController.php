<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cash;
use App\Models\Party;
use App\Models\Cheque;
use App\Models\Expense;
use App\Models\Voucher;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaanDebitVoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:debit-vouchers-create')->only('store');
        $this->middleware('permission:debit-vouchers-read')->only('index');
    }

    public function index()
    {
        $vouchers = Voucher::where('type', 'debit')
            ->where(function ($query) {
                $query->where('particulars', '!=', 'different_module')
                    ->orWhereNull('particulars');
            })
            ->with('user:id,name', 'party:id,name,type', 'expense:id,category_name')
            ->when(!auth()->user()->can('debit-vouchers-list'), function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('party_id', auth()->id());
            })
            ->latest()
            ->paginate();

        return view('pages.accounts.general.debit-vouchers.index', compact('vouchers'));
    }

    public function maanFilter(Request $request)
    {
        $vouchers = Voucher::where('type', 'debit')
            ->with('user:id,name', 'party:id,name', 'expense:id,category_name')
            ->when($request->has('search'), function ($q) use($request) {
                $q->Where('payment_method', 'like', '%'.$request->search.'%')
                    ->orWhere('voucher_no', 'like', '%'.$request->search.'%');
            })
            ->orWhereHas('user', function($q) use($request){
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->orWhereHas('party', function($q) use($request){
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->orWhereHas('expense', function($q) use($request){
                $q->where('category_name', 'like', '%'.$request->search.'%');
            })
            ->when(!auth()->user()->can('debit-vouchers-list'), function($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('party_id', auth()->id());
            })
            ->latest()
            ->get();

        if($request->ajax()){
            return response()->json([
                'data' => view('pages.accounts.general.debit-vouchers.datas',compact('vouchers'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function create()
    {
        $banks = Bank::latest()->get();
        $parties = Party::with('user')->latest()->get();
        return view('pages.accounts.general.debit-vouchers.create', compact('parties', 'banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'required',
            'date' => 'required|date',
            'bill_type' => 'nullable|string',
            'remarks' => 'nullable|string|max:100',
            'voucher_no' => 'nullable|string|max:50',
            'particulars' => 'nullable|string|max:500',
            'payment_method' => 'required|string|max:20',
            'expense_id' => 'nullable|exists:expenses,id',
            'cheque_no' => 'required_if:payment_method,cheque',
            'issue_date' => 'required_if:payment_method,cheque',
            'bank_id' => 'required_if:payment_method,bank,cheque',
            'amount' => ['required', 'numeric', 'gt:0','max:99999999999'],
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
                if ($request->expense_id) {
                    $expense = Expense::findOrFail($request->expense_id);
                    if ($expense->total_due < $request->amount) {
                        return response()->json(__('Invoice due is ' . $expense->total_due . '. You can not pay more than the invoice due amount.'), 400);
                    }
                } else {
                    $all_invoice_due = Expense::where('party_id', $request->party_id)->sum('total_due');
                    if (($all_invoice_due + $request->amount) > $party->due_amount) {
                        return response()->json([
                            'message' => __('You can only pay '. currency_format($party->due_amount - $all_invoice_due) .', without selecting an invoice.')
                        ], 400);
                    }
                }
            }

            if ((!in_array($party->type, ['supplier']) && $request->bill_type !== 'balance_withdraw')
                || (in_array($party->type, ['supplier']) && $request->bill_type === 'balance_withdraw')) {
                return response()->json(__('Please change the bill type for this party.'), 400);
            }

            if (!in_array($party->type, ['supplier']) && $request->payment_method === 'party_balance') {
                return response()->json(__('You cannot select wallet when you will withdraw the amount from the buyer/customer balance.'), 400);
            }

            if (($request->bill_type === 'due_bill' && !in_array($party->type, ['supplier']))
                || ($request->payment_method === 'party_balance' && $request->bill_type !== 'due_bill')) {
                return response()->json(__('Please change the bill type.'), 400);
            }

            if (!in_array($party->type, ['supplier']) && $party->balance < $request->amount) {
                return response()->json(__('Party balance is ' . $party->balance . '. The given amount cannot be more than the party wallet.'), 400);
            }
        }


        DB::beginTransaction();
        try {

            if ($request->payment_method != 'cheque' && $request->bill_type == 'due_bill' && $request->expense_id) {
                $expense = Expense::findOrFail($request->expense_id);
                $expense->update([
                    'total_due' => $expense->total_due - $request->amount,
                    'total_paid' => $expense->total_paid + $request->amount,
                ]);
            }

            if ($request->payment_method != 'party_balance' && $request->payment_method != 'cheque') {
                if ($request->bill_type == 'advance_payment') {
                    $party->update([
                        'balance' => $party->balance + $request->amount,
                        'advance_amount' => $party->advance_amount + $request->amount
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
                'type' => 'debit',
                'prev_balance' => $company_balance,
                'status' => $request->payment_method == 'cheque' ? 0 : 1,
                'party_id' => $request->party_id != 'others' ? $request->party_id : NULL,
                'current_balance' => $request->payment_method == 'party_balance' ? $company_balance : $company_balance - $request->amount,
                'meta' => [
                    'cheque_no' => $request->cheque_no,
                    'issue_date' => $request->issue_date,
                ],
            ]);

            if ($request->payment_method == 'cash') {
                if($request->amount <= cash_balance()) {
                    Cash::create($request->all() + [
                        'type' => 'debit',
                        'date' => $request->date,
                        'user_id' => auth()->id(),
                        'voucher_id' => $voucher->id,
                        'description' => $request->remarks,
                        'bank_id' => ($party && in_array($party->type, ['supplier'])) ? 'party_payment' : 'balance_withdraw',
                        'cash_type' => ($party && in_array($party->type, ['supplier'])) ? 'party_payment' : 'balance_withdraw',
                    ]);

                } else {
                    return response()->json(__('Amount can not be more than cash balance'), 400);
                }

            } elseif ($request->payment_method == 'bank' || $request->payment_method == 'cheque') {
                $bank = Bank::findOrFail($request->bank_id);
                if($request->amount <= $bank->balance) {
                    if ($request->payment_method == 'bank') {
                        Transfer::create($request->all() + [
                            'user_id' => auth()->id(),
                            'bank_from' => $bank->id,
                            'adjust_type' => 'debit',
                            'note' => $request->remarks,
                            'voucher_id' => $voucher->id,
                            'transfer_type' => 'adjust_bank',
                        ]);

                        $bank->update([
                            'balance' => $bank->balance - $request->amount
                        ]);
                    } elseif ($request->payment_method == 'cheque') {
                        Cheque::create($request->except('party_id') + [
                            'type' => 'debit',
                            'user_id' => auth()->id(),
                            'voucher_id' => $voucher->id,
                            'party_id' => $request->party_id != 'others' ? $request->party_id : NULL,
                        ]);
                    }
                } else {
                    return response()->json(__('Amount can not be more than bank balance'), 400);
                }
            } elseif ($request->payment_method == 'party_balance') {
                if ($request->amount <= $party->balance) {
                    // If this party has more balance this balance will be added to his balance
                    if ($request->amount <= $party->due_amount) {
                        $party->update([
                            'balance' => $party->balance - $request->amount,
                            'due_amount' => $party->due_amount - $request->amount,
                            'pay_amount' => $party->pay_amount + $request->amount,
                        ]);
                    } else {
                        return response()->json(__('Party due is '.$party->due_amount. '. You can not pay more than the due balance using the wallet option.'), 400);
                    }
                } else {
                    return response()->json(__('Insufficient balance.'), 400);
                }
            } else {
                return response()->json(__('Please select a valid payment method.'), 400);
            }

            DB::commit();
            sendNotification($voucher->id, route('debit-vouchers.index'), __('New debit voucher has been created.'));
            return response()->json([
                'message'   => __('Debit voucher created successfully'),
                'redirect'  => route('debit-vouchers.index'),
                'another_page' => route('invoices.voucher', $voucher->id),
            ]);

        } catch (\Exception $e){
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function edit($id)
    {
        $banks = Bank::latest()->get();
        $parties = Party::with('user')->latest()->get();
        $voucher = Voucher::with('party')->findOrFail($id);
        $expenses = Expense::where('party_id', $voucher->party_id)->latest()->get();
        abort_if(!check_visibility($voucher->created_at, 'debit-vouchers', $voucher->user_id), 403);

        return view('pages.accounts.general.debit-vouchers.edit', compact('voucher', 'banks', 'expenses', 'parties'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'bill_type' => 'nullable|string',
            'remarks' => 'nullable|string|max:100',
            'voucher_no' => 'nullable|string|max:50',
            'particulars' => 'nullable|string|max:500',
            'payment_method' => 'required|string|max:20',
            'expense_id' => 'nullable|exists:expenses,id',
            'cheque_no' => 'required_if:payment_method,cheque',
            'issue_date' => 'required_if:payment_method,cheque',
            'bank_id' => 'required_if:payment_method,bank,cheque',
            'amount' => ['required', 'numeric', 'gt:0','max:99999999999'],
        ]);

        $voucher = Voucher::findOrFail($id);

        $permission = check_visibility($voucher->created_at, 'debit-vouchers', $voucher->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $party = Party::find($voucher->party_id);

        if (!in_array($request->payment_method, ['cash', 'bank', 'cheque', 'party_balance'])) {
            return response()->json(__('Please select a valid payment method.'), 400);
        }
        if ($request->payment_method == 'party_balance' && !$party) {
            return response()->json(__('Please select a party or select another payment method.'), 400);
        }
        if (!in_array($request->bill_type, ['advance_payment', 'due_bill', 'balance_withdraw']) && $voucher->party_id != 'others') {
            return response()->json(__('Please select a valid bill type.'), 400);
        }

        if ($request->party_id != 'others' && $party) {
            $new_invoice = Expense::find($request->expense_id);
            $prev_invoice = Expense::find($voucher->expense_id);
            $party_balance = $party->balance + $voucher->amount;
            $party_due = $party->due_amount + $voucher->amount;

            if ($request->bill_type == 'due_bill') {
                if ($request->expense_id && !$voucher->expense_id) {
                    if ($new_invoice->total_due < $request->amount) {
                        return response()->json(__('Invoice due is ' . currency_format($new_invoice->total_due) . '. You can not pay more than the invoice due amount.'), 400);
                    }
                } else {
                    $all_invoice_due = Expense::where('party_id', $request->party_id)->sum('total_due');
                    if (($all_invoice_due + $request->amount) > $party_due) {
                        return response()->json([
                            'message' => __('You can only pay '. currency_format(($all_invoice_due + $voucher->amount) - $party_due) .', without selecting an invoice.')
                        ], 400);
                    }
                }
            }

            if (!in_array($party->type, ['supplier']) && $party_balance < $request->amount) {
                return response()->json(__('Party balance is ' . $party_balance . '. The given amount can not be more than the party wallet.'), 400);
            }

            if ($request->bill_type === 'due_bill' && !in_array($party->type, ['supplier'])) {
                return response()->json(__('Please select advance bill type.'), 400);
            }

            if (!in_array($party->type, ['supplier']) && $request->payment_method === 'party_balance') {
                return response()->json(__('You cannot select wallet option when you will withdraw the amount for Buyer/Customer.'), 400);
            }
            if ((!in_array($party->type, ['supplier']) && $request->bill_type !== 'balance_withdraw') ||
                (in_array($party->type, ['supplier']) && $request->bill_type === 'balance_withdraw')) {
                return response()->json(__('Please change the bill type for this party.'), 400);
            }
        }

        DB::beginTransaction();
        try {
            // IF PREVIOUS OR CURRENT PAYMENT METHOD IS CASH
            if ($voucher->payment_method == 'cash' && $request->payment_method != 'cash') {
                $cash = Cash::where('voucher_id', $voucher->id)->first();
                $cash->delete();
            } elseif ($voucher->payment_method == 'cash' && $request->payment_method == 'cash') {

                $cash = Cash::where('voucher_id', $voucher->id)->first();
                $original_cash_balance = cash_balance() + $cash->amount;

                if($request->amount <= $original_cash_balance) {
                    $cash->update($request->all() + [
                        'user_id' => auth()->id(),
                        'description' => $request->remarks,
                    ]);
                } else {
                    return response()->json(__('Amount can not be more than cash balance'), 400);
                }
            } elseif ($voucher->payment_method != 'cash' && $request->payment_method == 'cash') {
                if($request->amount <= cash_balance()) {
                    Cash::create($request->all() + [
                        'type' => 'debit',
                        'user_id' => auth()->id(),
                        'bank_id' => 'party_payment',
                        'voucher_id' => $voucher->id,
                        'cash_type' => 'party_payment',
                        'description' => $request->remarks,
                    ]);
                } else {
                    return response()->json(__('Amount can not be more than cash balance'), 400);
                }
            }

            // IF PREVIOUS OR CURRENT PAYMENT METHOD IS BANK
            if ($voucher->payment_method == 'bank' && $request->payment_method != 'bank') {
                $transfer = Transfer::where('voucher_id', $voucher->id)->first();
                $transfer->delete();
                $bank = Bank::findOrFail($voucher->bank_id);
                $bank->update([
                    'balance' => $bank->balance + $voucher->amount
                ]);
            } elseif ($voucher->payment_method == 'bank' && $request->payment_method == 'bank') {

                $prev_amount = $voucher->amount;
                if ($voucher->bank_id != $request->bank_id) {
                    $prev_amount = 0;
                    $bank = Bank::findOrFail($voucher->bank_id);
                    $bank->update([
                        'balance' => $bank->balance + $voucher->amount
                    ]);
                } else {

                    $current_bank = Bank::findOrFail($request->bank_id);
                    $bank_balance = $current_bank->balance + $prev_amount;

                    if($request->amount <= $bank_balance) {
                        $current_bank->update([
                            'balance' => $bank_balance - $request->amount
                        ]);
                    } else {
                        return response()->json(__('Amount can not be more than bank balance'), 400);
                    }
                }

                $transfer = Transfer::where('voucher_id', $voucher->id)->first();
                $transfer->update($request->all() + [
                    'user_id' => auth()->id(),
                    'note' => $request->remarks,
                    'bank_from' => $request->bank_id,
                ]);
            } elseif ($voucher->payment_method != 'bank' && $request->payment_method == 'bank') {
                $bank = Bank::findOrFail($request->bank_id);
                if($request->amount <= $bank->balance) {
                    Transfer::create($request->all() + [
                        'user_id' => auth()->id(),
                        'bank_from' => $bank->id,
                        'adjust_type' => 'debit',
                        'note' => $request->remarks,
                        'voucher_id' => $voucher->id,
                        'transfer_type' => 'adjust_bank',
                    ]);

                    $bank->update([
                        'balance' => $bank->balance - $request->amount
                    ]);

                } else {
                    return response()->json(__('Amount can not be more than bank balance'), 400);
                }
            }

            // IF PREVIOUS OR CURRENT PAYMENT METHOD IS CHEQUE
            if ($voucher->payment_method == 'cheque' && $request->payment_method != 'cheque') {
                $cheque = Cheque::where('voucher_id', $voucher->id)->first();
                if ($cheque->status == 0) {
                    $cheque->delete();
                } else {
                    return response()->json(__('You can\'t edit this transaction, because this cheque has already been withdrawn.'), 400);
                }
            } elseif ($voucher->payment_method == 'cheque' && $request->payment_method == 'cheque') {

                $bank = Bank::findOrFail($request->bank_id);
                $cheque = Cheque::where('voucher_id', $voucher->id)->first();

                if($request->amount <= $bank->balance) {
                    if ($cheque->status == 0) {
                        $cheque->update($request->except('party_id') + [
                            'user_id' => auth()->id(),
                        ]);
                    } else {
                        return response()->json(__('You can\'t edit this transaction, because this cheque has already been withdrawn.'), 400);
                    }
                } else {
                    return response()->json(__('Amount can not be more than bank balance'), 400);
                }
            } elseif ($voucher->payment_method != 'cheque' && $request->payment_method == 'cheque') {
                $bank = Bank::findOrFail($request->bank_id);
                if($request->amount <= $bank->balance) {
                    Cheque::create($request->except('party_id') + [
                        'type' => 'debit',
                        'user_id' => auth()->id(),
                        'voucher_id' => $voucher->id,
                        'party_id' => $voucher->party_id,
                    ]);
                    $this->othersToCheque($voucher, $party, $voucher->amount);
                } else {
                    return response()->json(__('Amount can not be more than bank balance'), 400);
                }
            }

            // IF PREVIOUS OR CURRENT PAYMENT METHOD IS PARTY BALANCE
            if ($voucher->payment_method == 'party_balance' && $request->payment_method != 'party_balance') {

                $party->balance = $party->balance + $voucher->amount;
                $party->due_amount = $party->due_amount + $voucher->amount;
                $party->pay_amount = $party->pay_amount - $voucher->amount;
                $party->save();

            } elseif ($voucher->payment_method == 'party_balance' && $request->payment_method == 'party_balance') {
                if ($request->amount <= $party_balance) {
                    if ($party_due >= $request->amount) {
                        $party->update([
                            'balance' => $party_balance - $request->amount,
                            'due_amount' => ($party->due_amount + $voucher->amount) - $request->amount,
                            'pay_amount' => ($party->pay_amount - $voucher->amount) + $request->amount,
                        ]);
                    } else {
                        return response()->json(__('Party due is '.$party->due_amount. '. You can not pay more than the due balance using the wallet option.'), 400);
                    }
                } else {
                    return response()->json(__('Insufficient balance.'), 400);
                }
            } elseif ($voucher->payment_method != 'party_balance' && $request->payment_method == 'party_balance') {
                if ($request->amount <= $party->balance) {
                    // If this party has more balance this balance will be added to his balance
                    $prev_due_amount = $party->due_amount + $request->amount;
                    if ($request->amount <= $prev_due_amount) {
                        $party->update([
                            'balance' => $party->balance - $request->amount,
                            'due_amount' => $party->due_amount - $request->amount,
                            'pay_amount' => $party->pay_amount + $request->amount,
                        ]);
                    } else {
                        return response()->json(__('Party due is '.$party->due_amount. '. You can not pay more than the due balance using the wallet option.'), 400);
                    }
                } else {
                    return response()->json(__('Insufficient balance.'), 400);
                }
            }

            if ($party && $voucher->payment_method != 'cheque' && $request->payment_method == 'cheque' && $voucher->bill_type == 'due_bill') {
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

                    if ($request->expense_id == $voucher->expense_id && !$prev_is_cq) {
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

            $current_balance = $request->payment_method == 'party_balance' ? $voucher->prev_balance : ($voucher->prev_balance - $request->amount);

            $voucher->update($request->except('party_id') + [
                'current_balance' => $current_balance,
                'status' => $request->payment_method == 'cheque' ? 0 : 1,
                'meta' => [
                    'cheque_no' => $request->cheque_no,
                    'issue_date' => $request->issue_date,
                ],
            ]);

            DB::commit();
            sendNotification($voucher->id, route('debit-vouchers.index'), __('Debit voucher has been updated.'));
            return response()->json([
                'message'   => __('Debit voucher updated successfully'),
                'redirect'  => route('debit-vouchers.index'),
                'another_page' => route('invoices.voucher', $voucher->id),
            ]);

        } catch (\Exception $e){
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

            if ($party && $party->balance < $voucher->amount && $voucher->bill_type !== 'balance_withdraw' && $voucher->payment_method !== 'cheque') {
                return response()->json(__('You can not delete this voucher because this party has withdrawn his balance.'), 400);
            }

            if ($voucher->payment_method == 'cash') {
                $cash = Cash::where('voucher_id', $voucher->id)->first();
                $cash->delete();
            } elseif ($voucher->payment_method == 'bank') {

                $transfer = Transfer::where('voucher_id', $voucher->id)->first();
                $transfer->delete();
                $bank = Bank::findOrFail($voucher->bank_id);
                $bank->update([
                    'balance' => $bank->balance + $voucher->amount
                ]);

            } elseif ($voucher->payment_method == 'cheque') {
                $cheque = Cheque::where('voucher_id', $voucher->id)->first();
                if ($cheque->status == 0) {
                    $cheque->delete();
                } else {
                    return response()->json(__('You can not delete this transaction, because this cheque has already been withdrawn.'), 400);
                }
            } elseif ($voucher->payment_method == 'party_balance') {
                $party->update([
                    'balance' => $party->balance + $voucher->amount,
                    'due_amount' => $party->due_amount + $voucher->amount,
                    'pay_amount' => $party->pay_amount - $voucher->amount,
                ]);
            }

            if ($voucher->payment_method != 'cheque' && $voucher->bill_type == 'due_bill') {
                $expense = Expense::findOrFail($voucher->expense_id);
                $expense->update([
                    'total_due' => $expense->total_due + $voucher->amount,
                    'total_paid' => $expense->total_paid - $voucher->amount,
                ]);
            }

            if ($party && $voucher->payment_method != 'party_balance' && $voucher->payment_method != 'cheque') {
                if ($voucher->bill_type == 'advance_payment') {
                    $party->update([
                        'balance' => $party->balance - $voucher->amount,
                        'advance_amount' => $party->advance_amount - $voucher->amount,
                    ]);
                } elseif ($voucher->bill_type == 'due_bill') {
                    $party->update([
                        'pay_amount' => $party->pay_amount - $voucher->amount,
                        'due_amount' => $party->due_amount + $voucher->amount,
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
                'message' => __('Debit voucher deleted successfully'),
                'redirect' => route('debit-vouchers.index')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    private function othersToCheque($voucher, $party, $prev_amount) {
        if ($voucher->bill_type == 'advance_payment') {
            $party->update([
                'balance' => $party->balance - $prev_amount,
                'advance_amount' => $party->advance_amount - $prev_amount,
            ]);
        } elseif ($voucher->bill_type == 'due_bill') {
            $is_party_blnc = $voucher->payment_method == 'party_balance' ? true:false; // Because we have already calculated, if the previous payment method is party balance.

            $party->update([
                'due_amount' => $is_party_blnc ? $party->due_amount : ($party->due_amount + $prev_amount),
                'pay_amount' => $is_party_blnc ? $party->pay_amount : ($party->pay_amount - $prev_amount),
            ]);
        } elseif ($voucher->bill_type == 'balance_withdraw') {
            $party->update([
                'balance' => $party->balance + $voucher->amount,
            ]);
        }
    }

    public function getExpenses($party_id) 
    {
        if ($party_id) {
            if ($party_id == 'others') {
                $expenses = Expense::whereNull('party_id')->get();
            } else {
                $expenses = Expense::where('party_id', $party_id)->get();
            }
        } else {
            $expenses = [];
        }

        return response()->json($expenses);
    }
}
