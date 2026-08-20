<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\AccessoryOrder;
use App\Models\Bank;
use App\Models\Booking;
use App\Models\Cash;
use App\Models\Cheque;
use App\Models\Costbudget;
use App\Models\Costing;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\History;
use App\Models\Income;
use App\Models\Order;
use App\Models\Party;
use App\Models\Production;
use App\Models\Salary;
use App\Models\Sample;
use App\Models\Shipment;
use App\Models\ShipmentDetail;
use App\Models\Unit;
use App\Models\User;
use App\Models\Voucher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfGenerateController extends Controller
{
    public function orders()
    {
       $datas =Order::with('orderDetails','party:id,name','merchandiser:id,name')->latest()->get();
       return $this->pdf($datas, 'order','a3', 'landscape');
    }
    
    public function bookings()
    {
        $datas = Booking::with('order:id,order_no,image,party_id,merchandiser_id','order.party:id,name,type','order.merchandiser:id,name')->latest()->get();
        return $this->pdf($datas, 'booking','a3', 'landscape');
    }

    public function costings()
    {
        $datas = Costing::with('order:id,order_no,party_id','order.party:id,name,type')->latest()->get();
        return $this->pdf($datas, 'costing','a3', 'portrait');
    }

    public function budgets()
    {
        $datas = Costbudget::with('order:id,order_no,party_id','order.party:id,name,type')->latest()->get();
        return $this->pdf($datas, 'budget','a3', 'portrait');
    }

    public function samples()
    {
        $datas = Sample::with('order:id,order_no')->latest()->get();
        return $this->pdf($datas, 'sample','a3', 'portrait');
    }

    public function productions()
    {
        $datas = Production::with('order:id,party_id,order_no','order.party:id,name,type')->latest()->get();
        return $this->pdf($datas, 'production','a3', 'landscape');
    }

    public function shipments()
    {
        $datas = Shipment::with('order:id,order_no')->withSum('details', 'qty')->withSum('details', 'total_cm')->latest()->get();
        return $this->pdf($datas, 'shipment','a3', 'portrait');
    }

    public function units()
    {
        $datas = Unit::latest()->get();
        return $this->pdf($datas, 'unit','a4', 'portrait');
    }

    public function accessories()
    {
        $datas = Accessory::with('unit:id,name')->latest()->get();
        return $this->pdf($datas, 'accessory','a3', 'portrait');
    }

    public function accessoriesOrders()
    {
        $datas = AccessoryOrder::with('party:id,name', 'accessory:id,name,unit_id', 'accessory.unit:id,name')->latest()->get();
        return $this->pdf($datas, 'accessory_order','a3', 'portrait');
    }

    public function users()
    {
        $datas = User::where('role', '!=', 'superadmin')->where('role', request('role'))->latest()->get();
        return $this->pdf($datas, 'user','a3', 'portrait');
    }

    public function banks()
    {
        $datas = Bank::latest()->get();
        return $this->pdf($datas, 'bank','a3', 'portrait');
    }

    public function cashes()
    {
        $datas = Cash::with('bank:id,bank_name,holder_name')->latest()->get();
        return $this->pdf($datas, 'cash','a3', 'portrait');
    }

    public function cheques()
    {
        $datas = Cheque::with('bank:id,bank_name', 'party:id,name', 'voucher.income', 'voucher.expense')->latest()->get();
        return $this->pdf($datas, 'cheque','a3', 'portrait');
    }

    public function parties()
    {
        $datas = Party::with('user')->where('type', request('parties'))->latest()->get();
        return $this->pdf($datas, 'party','a3', 'portrait');
    }

    public function incomes()
    {
        $datas = Income::where('total_due', '<=', 0)->latest()->get();
        return $this->pdf($datas, 'income','a4', 'portrait');
    }

    public function expenses()
    {
        $datas = Expense::where('total_due', '<=', 0)->latest()->get();
        return $this->pdf($datas, 'expense','a4', 'portrait');
    }

    public function creditVouchers()
    {
        $datas = Voucher::where('type', 'credit')
            ->where(function ($query) {
                $query->where('particulars', '!=', 'different_module')
                    ->orWhereNull('particulars');
            })
            ->with('user:id,name', 'party:id,name', 'income:id,category_name')
            ->latest()->get();
        return $this->pdf($datas, 'credit_voucher','a3', 'portrait');
    }

    public function debitVouchers()
    {
        $datas = Voucher::where('type', 'debit')
            ->where(function ($query) {
                $query->where('particulars', '!=', 'different_module')
                    ->orWhereNull('particulars');
            })
            ->with('user:id,name', 'party:id,name,type', 'expense:id,category_name')
            ->latest()->get();
        return $this->pdf($datas, 'debit_voucher','a3', 'portrait');
    }

    public function monthlyTransactions()
    {
        $datas = Voucher::whereMonth('date', Carbon::now()->month)
                ->whereIn('type', ['debit', 'credit'])
                ->where('status', 1)
                ->selectRaw('DATE(date) as date, sum(prev_balance) as total_prev_balance, sum(current_balance) as total_current_balance,GROUP_CONCAT(DISTINCT type SEPARATOR \', \') as all_type,GROUP_CONCAT(DISTINCT remarks SEPARATOR \', \') as all_remarks')
                ->groupBy('date')
                ->when(!auth()->user()->can('transactions-list'), function($query) {
                    $query->where('user_id', auth()->id());
                })
                ->latest()
                ->get();

        return $this->pdf($datas, 'monthly_transaction','a3', 'portrait');
    }

    public function partyLedgers()
    {
        $datas = Party::whereType(request('type'))->latest()->get();
        return $this->pdf($datas, 'party_ledger','a3', 'portrait');
    }

    public function dailyCashbooks()
    {
        $datas = Voucher::where('payment_method', 'cash')
            ->whereIn('type', ['debit', 'credit'])
            ->whereDate('created_at', today())
            ->with('income:id,category_name', 'expense:id,category_name')
            ->latest()->get();

        return $this->pdf($datas, 'daily_cashbook','a3', 'portrait');
    }

    public function partyDues()
    {
        $datas = Party::with('currency')
                ->whereType(request('type'))
                ->where('due_amount', '>', 0)
                ->latest()->get();

        return $this->pdf($datas, 'party_due', 'a3', 'portrait');
    }

    public function lossProfits()
    {
        $datas['revenues'] = ShipmentDetail::whereYear('created_at', request('year') ?? date('Y'))
                                        ->selectRaw('MONTHNAME(created_at) AS month, SUM(qty) AS total_qty, SUM(total_sale) AS totals_sale, SUM(total_cm) AS totals_cm')
                                        ->orderBy('month', 'desc')
                                        ->groupBy('month')
                                        ->get();

        $datas['total_cm_by_month'] = $datas['revenues']->groupBy('month')
                            ->map(function ($revenues) {
                                return $revenues->sum('totals_cm');
                            })
                            ->toArray();

        $datas['monthly_total_revenues'] = $datas['revenues']->groupBy('month')
                            ->map(function ($revenues) {
                                return $revenues->sum('totals_sale');
                            })
                            ->toArray();

        $others_expense = Voucher::whereType('debit')->whereNull('party_id')
                            ->whereYear('date', request('year') ?? date('Y'))
                            ->selectRaw('SUM(amount) as total_amount, MONTHNAME(date) AS month')
                            ->groupBy('month')
                            ->orderBy('month', 'desc')
                            ->get();

        $datas['expenses_by_month'] = $others_expense->groupBy('month')->map(function ($expense) {
            return $expense->sum('total_amount');
        });

        $salaries_by_month = Voucher::where('bill_type', 'employee_salary')
                            ->whereYear('date', request('year') ?? date('Y'))
                            ->selectRaw('SUM(amount) as total_amount, MONTHNAME(date) AS month')
                            ->groupBy('month')
                            ->orderBy('month', 'desc')
                            ->get();

        $datas['salaries_by_month'] = $salaries_by_month->groupBy('month')->map(function ($expense) {
            return $expense->sum('total_amount');
        });

        return $this->pdf($datas, 'loss_profit', 'a3', 'landscape');
    }

    public function orderReports()
    {
        $datas = Order::with('party:id,name,type', 'merchandiser:id,name')
                ->whereDate('created_at', now()->format('Y-m-d'))
                ->latest()
                ->get();

        return $this->pdf($datas, 'order_report','a3', 'landscape');
    }

    public function transactionReports()
    {
        $datas = Voucher::whereIn('type', ['debit', 'credit'])->with('income:id,category_name', 'expense:id,category_name', 'party:id,name,type', 'user:id,name')
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->latest()
            ->get();

        return $this->pdf($datas, 'transaction_report','a3', 'landscape');
    }

    public function productionReports()
    {
        $productions = Production::with('order:id,party_id,order_no','order.party:id,name,type')
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->latest()
            ->get();
        $datas = collect($productions)->groupBy('order_id');
        return $this->pdf($datas, 'production_report','a3', 'landscape');
    }

    public function payableDueReports()
    {
        $datas = Expense::with('party:id,name,type')->whereHas('party')
            ->whereDate('created_at', today())
            ->latest()
            ->get();
        return $this->pdf($datas, 'payable_due_report','a3', 'landscape');

    }

    public function dueCollectionReports()
    {
        $datas = Income::with('party:id,name,type')->whereHas('party')
            ->whereDate('created_at', today())
            ->latest()
            ->get();
        return $this->pdf($datas, 'due_collection_report','a3', 'landscape');

    }

    public function designations()
    {
        $datas = Designation::latest()->get();
        return $this->pdf($datas, 'designation','a4', 'portrait');
    }

    public function employees()
    {
        $datas = Employee::with('designation:id,name')->latest()->get();
        return $this->pdf($datas, 'employee','a3', 'portrait');
    }

    public function salaries()
    {
        $datas = Salary::with('employee')->latest()->get();
        return $this->pdf($datas, 'salary','a3', 'portrait');
    }

    public function orderHistories()
    {
        $datas = History::where(['table' => 'orders', 'row_id' => request('id')])->latest()->get();
        return $this->pdf($datas, 'order_history','a3', 'landscape');
    }

    public function pdf($datas, $model, $size, $orientation)
    {
        $pdf = PDF::loadView('pages.pdf.' . $model, compact('datas'))->setPaper($size ?? 'a3', $orientation ?? 'landscape');
        return $pdf->download(date('Y-M-d-') . $model . '.pdf');
    }
}
