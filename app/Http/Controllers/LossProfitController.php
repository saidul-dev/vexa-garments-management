<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Models\ShipmentDetail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LossProfitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:loss-profit-read')->only('index');
    }

    public function index()
    {
        $data['revenues'] = ShipmentDetail::whereYear('created_at', request('year') ?? date('Y'))
                            ->selectRaw('MONTHNAME(created_at) AS month, SUM(qty) AS total_qty, SUM(total_sale) AS totals_sale, SUM(total_cm) AS totals_cm')
                            ->orderBy('month', 'desc')
                            ->groupBy('month')
                            ->get();

        $data['total_cm_by_month'] = $data['revenues']->groupBy('month')
                                    ->map(function ($revenues) {
                                        return $revenues->sum('totals_cm');
                                    })
                                    ->toArray();

        $data['monthly_total_revenues'] = $data['revenues']->groupBy('month')
                                            ->map(function ($revenues) {
                                                return $revenues->sum('totals_sale');
                                            })
                                            ->toArray();

        // FOR EXPENSE
        $expense_data = Voucher::whereYear('date', request('year') ?? date('Y'))
                        ->where('status', 1)
                        ->with('expense:id,category_name')->whereType('debit')->selectRaw('monthname(date) month, sum(amount) as amount, expense_id as expense_id')
                        ->orderBy('month', 'desc')
                        ->groupBy('month', 'expense_id')
                        ->get();

        $data['vouchers'] = $expense_data->groupBy('expense_id')->map(function ($expense) {
            return $expense;
        })->values();

        $data['total_by_expenses'] = $expense_data->groupBy('expense_id')->map(function ($expense) {
            return $expense->sum('amount');
        })->values();

        // Total monthly expense
        $data['monthly_total'] = $expense_data->groupBy('month')->map(function ($expense) {
            return $expense->sum('amount');
        });

        $others_expense = Voucher::whereType('debit')->whereNull('party_id')
                            ->whereYear('date', request('year') ?? date('Y'))
                            ->selectRaw('SUM(amount) as total_amount, MONTHNAME(date) AS month')
                            ->groupBy('month')
                            ->orderBy('month', 'desc')
                            ->get();

        $data['expenses_by_month'] = $others_expense->groupBy('month')->map(function ($expense) {
            return $expense->sum('total_amount');
        });

        $salaries_by_month = Voucher::where('bill_type', 'employee_salary')
                            ->whereYear('date', request('year') ?? date('Y'))
                            ->selectRaw('SUM(amount) as total_amount, MONTHNAME(date) AS month')
                            ->groupBy('month')
                            ->orderBy('month', 'desc')
                            ->get();

        $data['salaries_by_month'] = $salaries_by_month->groupBy('month')->map(function ($expense) {
            return $expense->sum('total_amount');
        });

        if (request()->ajax()) {
            return response()->json([
                'data' => view('pages.accounts.general.loss-profit.datas', $data)->render()
            ]);
        }

        return view('pages.accounts.general.loss-profit.index', $data);
    }

    public function getLossProfit()
    {
        $salaries = Salary::whereYear('created_at', request('year') ?? date('Y'))->sum('amount');
        $total_cm = ShipmentDetail::whereYear('created_at', request('year') ?? date('Y'))->sum('total_cm');
        $total_sale = ShipmentDetail::whereYear('created_at', request('year') ?? date('Y'))->sum('total_sale');
        $other_expenses = Voucher::whereYear('created_at', request('year') ?? date('Y'))->whereNull('party_id')->whereNotNull('expense_id')->sum('amount');

        $total_expenses = $total_cm + $other_expenses + $salaries;
        $total_loss = $total_sale - $total_expenses;
        $total_profit = $total_sale - $total_expenses;

        $data['total_sale'] = $total_sale;
        $data['total_expense'] = $other_expenses + $total_cm;
        $data['total_loss'] = abs($total_loss < 0 ? $total_loss : 0);
        $data['total_profit'] = abs($total_profit > 0 ? $total_profit : 0);

        return response()->json($data);
    }

    public function income()
    {
        $monthlyData = DB::table('shipment_details')
                        ->whereYear('shipment_details.created_at', request('year') ?? date('Y'))
                        ->join('shipments', 'shipment_details.shipment_id', '=', 'shipments.id')
                        ->join('orders', 'shipments.order_id', '=', 'orders.id')
                        ->selectRaw('
                            DATE_FORMAT(shipment_details.shipment_date, "%M") AS month,
                            orders.order_no AS order_no,
                            SUM(shipment_details.total_sale) AS total_amount
                        ')
                        ->groupBy('month', 'order_no')
                        ->orderBy('month', 'asc')
                        ->orderBy('order_no', 'asc')
                        ->get();

        $income_data = [];

        $monthlyData->each(function ($data) use (&$income_data) {
            $orderNo = $data->order_no;
            $income_data[$orderNo][] = [
                'month' => $data->month, // Month name
                'total_amount' => $data->total_amount,
            ];
        });

        $monthly_totals = collect($income_data)->flatten(1)->groupBy('month')->map(function ($data) {
            return $data->sum('total_amount');
        });

        return view('pages.accounts.general.loss-profit.income', compact('income_data', 'monthly_totals'));
    }

    public function incomeFilter(Request $request)
    {
        $monthlyData = DB::table('shipment_details')
                        ->whereYear('shipment_details.created_at', request('year') ?? date('Y'))
                        ->join('shipments', 'shipment_details.shipment_id', '=', 'shipments.id')
                        ->join('orders', 'shipments.order_id', '=', 'orders.id')
                        ->selectRaw('DATE_FORMAT(shipment_details.shipment_date, "%M") AS month, orders.order_no AS order_no, SUM(shipment_details.total_sale) AS total_amount')
                        ->groupBy('month', 'order_no')
                        ->orderBy('month', 'asc')
                        ->orderBy('order_no', 'asc')
                        ->get();

        $income_data = [];

        $monthlyData->each(function ($data) use (&$income_data) {
            $orderNo = $data->order_no;
            $income_data[$orderNo][] = [
                'month' => $data->month, // Month name
                'total_amount' => $data->total_amount,
            ];
        });

        $monthly_totals = collect($income_data)->flatten(1)->groupBy('month')->map(function ($data) {
            return $data->sum('total_amount');
        });

        $total_incomes = collect($income_data)->flatten(1)->sum('total_amount');

        return response()->json([
            'total_incomes' => $total_incomes,
            'data' => view('pages.accounts.general.loss-profit.income-datas', compact('income_data', 'monthly_totals'))->render()
        ]);
    }

    public function expense()
    {
        $monthlyData = DB::table('shipment_details')
                        ->whereYear('shipment_details.created_at', request('year') ?? date('Y'))
                        ->join('shipments', 'shipment_details.shipment_id', '=', 'shipments.id')
                        ->join('orders', 'shipments.order_id', '=', 'orders.id')
                        ->selectRaw('
                            DATE_FORMAT(shipment_details.shipment_date, "%M") AS month,
                            orders.order_no AS order_no,
                            SUM(shipment_details.total_cm) AS total_amount
                        ')
                        ->groupBy('month', 'order_no')
                        ->orderBy('month', 'asc')
                        ->orderBy('order_no', 'asc')
                        ->get();

        $expense_data = [];

        $monthlyData->each(function ($data) use (&$expense_data) {
            $orderNo = $data->order_no;
            $expense_data[$orderNo][] = [
                'month' => $data->month, // Month name
                'total_amount' => $data->total_amount,
            ];
        });

        $monthly_totals = collect($expense_data)->flatten(1)->groupBy('month')->map(function ($data) {
            return $data->sum('total_amount');
        });

        $others_expense = Voucher::whereType('debit')->whereNull('party_id')
                            ->whereYear('date', request('year') ?? date('Y'))
                            ->selectRaw('SUM(amount) as total_amount, MONTHNAME(date) AS month')
                            ->groupBy('month')
                            ->get();

        return view('pages.accounts.general.loss-profit.expense', compact('expense_data', 'monthly_totals', 'others_expense'));
    }

    public function expenseFilter(Request $request)
    {
        $monthlyData = DB::table('shipment_details')
                        ->whereYear('shipment_details.created_at', request('year') ?? date('Y'))
                        ->join('shipments', 'shipment_details.shipment_id', '=', 'shipments.id')
                        ->join('orders', 'shipments.order_id', '=', 'orders.id')
                        ->selectRaw('DATE_FORMAT(shipment_details.shipment_date, "%M") AS month, orders.order_no AS order_no, SUM(shipment_details.total_cm) AS total_amount')
                        ->groupBy('month', 'order_no')
                        ->orderBy('month', 'asc')
                        ->orderBy('order_no', 'asc')
                        ->get();

        $expense_data = [];

        $monthlyData->each(function ($data) use (&$expense_data) {
            $orderNo = $data->order_no;
            $expense_data[$orderNo][] = [
                'month' => $data->month, // Month name
                'total_amount' => $data->total_amount,
            ];
        });

        $monthly_totals = collect($expense_data)->flatten(1)->groupBy('month')->map(function ($data) {
            return $data->sum('total_amount');
        });

        $total_expense = collect($expense_data)->flatten(1)->sum('total_amount');

        $others_expense = Voucher::whereType('debit')->whereNull('party_id')
                            ->whereYear('date', request('year') ?? date('Y'))
                            ->selectRaw('SUM(amount) as total_amount, MONTHNAME(date) AS month')
                            ->groupBy('month')
                            ->get();

        return response()->json([
            'total_expense' => $total_expense + $others_expense->sum('total_amount'),
            'data' => view('pages.accounts.general.loss-profit.expense-datas', compact('expense_data', 'monthly_totals', 'others_expense'))->render()
        ]);
    }

    public function incomeCsvPrint(Request $request)
    {
        $tableContent = $request->input('tableContent');
        return Excel::download(function ($excel) use ($tableContent) {
            // Set the content type to Excel
            $excel->sheet('Sheet 1', function ($sheet) use ($tableContent) {
                // Load the HTML content into the Excel sheet
                $sheet->html($tableContent);
            });
        }, 'table.xlsx');
    }

}
