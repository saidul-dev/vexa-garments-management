<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Cash;
use App\Models\Cheque;
use App\Models\Salary;
use App\Models\Voucher;
use App\Models\Employee;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnooSalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:salaries-create')->only('store');
        $this->middleware('permission:salaries-read')->only('index');
        $this->middleware('permission:salaries-update')->only('update', 'status');
        $this->middleware('permission:salaries-delete')->only('destroy');
    }

    public function index()
    {
        $salaries = Salary::with('employee')->latest()->paginate(10);
        return view('pages.salaries.index', compact('salaries'));
    }

    public function create()
    {
        $banks = Bank::latest()->get();
        $employees = Employee::latest()->get();
        return view('pages.salaries.create', compact('employees', 'banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|string|max:15',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|string|max:20',
            'employee_id' => 'required|exists:employees,id',
            'cheque_no' => 'required_if:payment_method,cheque',
            'issue_date' => 'required_if:payment_method,cheque',
            'amount' => 'required|numeric|gt:0|max:999999999999',
            'bank_id' => 'required_if:payment_method,bank,cheque',
        ]);

        DB::beginTransaction();
        try {

            $employee = Employee::findOrFail($request->employee_id);
            $has_salary_amount = Salary::where('employee_id', $employee->id)->where('year', $request->year)->where('month', $request->month)->sum('amount');
            $due_salary = $employee->salary - $has_salary_amount;

            $salary_month = Carbon::parse($request->month)->format('m');
            $joining_year = Carbon::parse($employee->join_date)->format('Y');
            $joining_month = Carbon::parse($employee->join_date)->format('m');

            if ($request->amount <= $due_salary) {
                if ($joining_year <= $request->year && $joining_month <= $salary_month) {

                    $company_balance = company_balance();

                    $voucher = Voucher::create($request->all() + [
                        'date' => today(),
                        'user_id' => auth()->id(),
                        'type' => 'employee_salary',
                        'remarks' => $request->notes,
                        'bill_type' => 'employee_salary',
                        'prev_balance' => $company_balance,
                        'bill_amount' => $employee->salary,
                        'particulars' => 'different_module',
                        'status' => $request->payment_method == 'cheque' ? 0 : 1,
                        'current_balance' => $company_balance - $request->amount,
                        'meta' => [
                            'cheque_no' => $request->cheque_no,
                            'issue_date' => $request->issue_date,
                        ],
                    ]);
                    
                    $salary = Salary::create($request->all() + [
                        'user_id' => auth()->id(),
                        'voucher_id' => $voucher->id,
                        'due_salary' => $employee->salary - ($request->amount + $has_salary_amount),
                        'meta' => [
                            'cheque_no' => $request->cheque_no,
                            'issue_date' => $request->issue_date,
                        ]
                    ]);                

                    if ($request->payment_method == 'cash') {
                        if($request->amount <= cash_balance()) {
                            Cash::create($request->all() + [
                                'type' => 'debit',
                                'date' => today(),
                                'user_id' => auth()->id(),
                                'voucher_id' => $voucher->id,
                                'description' => $request->notes,
                                'cash_type' => 'employee_salary',
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
                                Cheque::create($request->all() + [
                                    'type' => 'debit',
                                    'user_id' => auth()->id(),
                                    'voucher_id' => $voucher->id,
                                ]);
                            }
                        } else {
                            return response()->json(__('Amount can not be more than bank balance'), 400);
                        }
                    } else {
                        return response()->json(__('Please select a valid payment method.'), 400);
                    }

                    DB::commit();
                    sendNotification($salary->id, route('salaries.index'), __('New salary pay created.'));
                    return response()->json([
                        'message'  => __('Salary pay successfully.'),
                        'redirect' => route('salaries.index'),
                    ]);
                } else {
                    return response()->json([
                        'message' => __('You can not pay salary before the joining date.')
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => __('You can not pay more than the salary amount for this employee.')
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function edit(Salary $salary)
    {
        $banks = Bank::latest()->get();
        $employees = Employee::select('id','name')->latest()->get();
        return view('pages.salaries.edit', compact('employees', 'salary', 'banks'));
    }

    public function update(Request $request, Salary $salary)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|string|max:15',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|string|max:20',
            'employee_id' => 'required|exists:employees,id',
            'cheque_no' => 'required_if:payment_method,cheque',
            'issue_date' => 'required_if:payment_method,cheque',
            'amount' => 'required|numeric|gt:0|max:999999999999',
            'bank_id' => 'required_if:payment_method,bank,cheque',
        ]);

        $voucher = Voucher::findOrFail($salary->voucher_id);

        DB::beginTransaction();
        try {

            $employee = Employee::findOrFail($request->employee_id);
            $has_salary_amount = Salary::where('id', '!=', $salary->id)->where('employee_id', $employee->id)->where('year', $request->year)->where('month', $request->month)->sum('amount');
            $due_salary = $employee->salary - $has_salary_amount;

            $salary_month = Carbon::parse($request->month)->format('m');
            $joining_year = Carbon::parse($employee->join_date)->format('Y');
            $joining_month = Carbon::parse($employee->join_date)->format('m');

            if ($request->amount <= $due_salary) {
                if ($joining_year <= $request->year && $joining_month <= $salary_month) {

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
                            ]);
                        } else {
                            return response()->json(__('Amount can not be more than bank balance'), 400);
                        }
                    }

                    $current_balance = $voucher->prev_balance - $request->amount;

                    $voucher->update($request->all() + [
                        'date' => today(),
                        'user_id' => auth()->id(),
                        'remarks' => $request->notes,
                        'bill_amount' => $employee->salary,
                        'current_balance' => $current_balance,
                        'status' => $request->payment_method == 'cheque' ? 0 : 1,
                        'meta' => [
                            'cheque_no' => $request->cheque_no,
                            'issue_date' => $request->issue_date,
                        ],
                    ]);
                    
                    $salary->update($request->all() + [
                        'user_id' => auth()->id(),
                        'due_salary' => $employee->salary - ($request->amount + $has_salary_amount),
                        'meta' => [
                            'cheque_no' => $request->cheque_no,
                            'issue_date' => $request->issue_date,
                        ]
                    ]);

                    DB::commit();
                    sendNotification($salary->id, route('salaries.index'), __('New salary pay updated.'));
                    return response()->json([
                        'message'  => __('Salary pay updated successfully.'),
                        'redirect' => route('salaries.index'),
                    ]);
                } else {
                    return response()->json([
                        'message' => __('You can not pay salary before the joining date.')
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => __('Please note that the payment amount cannot exceed the employee salary.')
                ], 400);
            }
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function destroy(Salary $salary)
    {
        DB::beginTransaction();
        try {
            $voucher = Voucher::findOrFail($salary->voucher_id);

            if ($voucher) {
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
                        return response()->json(__('You can not delete this, because this cheque has already been withdrawn.'), 400);
                    }
                }

                $voucher->delete();
            }
            
            $salary->delete();
            
            DB::commit();
            return response()->json([
                'message'  => __('Salary deleted successfully.'),
                'redirect' => route('salaries.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }
}
