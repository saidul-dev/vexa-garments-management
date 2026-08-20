<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Order;
use App\Models\Party;
use App\Models\Voucher;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-read')->only('index', 'maanIndex');
    }

    public function maanIndex()
    {
        $orders = Order::latest()->take(5)->get();
        // $parties = Party::whereIn('type', ['buyer','customer'])
        //             ->with('user')
        //             ->whereMonth('created_at', '=', date('m'))
        //             ->orderByDesc('total_bill')
        //             ->take(5)
        //             ->get();

        return view('pages.dashboard.index', compact( 'orders'));
    }

    public function getDashboardData()
    {
        $startDate = date('Y-m-d', strtotime('this week')); // Start of the current week
        $endDate = date('Y-m-d', strtotime('this week +6 days')); // End of the current week

        $data['total_order'] = Order::count();
        $data['running_order_qty'] = Order::where('status', 2)->count();
        $data['pending_order'] = Order::where('status', 1)->count();
        $data['weekly_order_value'] = currency_format(Order::whereBetween('created_at', [$startDate, $endDate])->sum('lc'));
        $data['monthly_order_value'] =  currency_format(Order::whereMonth('created_at', '=', date('m'))->sum('lc'));

        $data['current_year_value'] = currency_format(Order::whereYear('created_at', '=', date('Y'))->sum('lc'));

        $data['total_cash'] = currency_format(cash_balance());
        $data['total_bank_balance'] = currency_format(Bank::sum('balance'));
        $data['supplier_due'] = currency_format(Party::where('type', 'supplier')->sum('due_amount'));
        // should be checked for expense calculation
        $monthly_expense = Voucher::where('status', 1)
                            ->whereMonth('created_at', '=', date('m'))
                            ->whereType('debit')
                            ->where('bill_type', '!=', 'balance_withdraw')
                            ->sum('amount');

        $data['monthly_expense'] = currency_format($monthly_expense);

        $debit_transaction = Voucher::where('status', 1)->where('type', 'debit')->whereIn('bill_type', ['due_bill', 'advance_payment', 'balance_withdraw'])->sum('amount');
        $credit_transaction = Voucher::where('status', 1)->where('type', 'credit')->whereIn('bill_type', ['due_bill', 'advance_payment', 'balance_withdraw'])->sum('amount');

        $data['debit_transaction'] = currency_format($debit_transaction);
        $data['credit_transaction'] = currency_format($credit_transaction);

        return response()->json($data);
    }

    public function yearlyLcValue()
    {
        $best_countries = Order::selectRaw('users.country, SUM(orders.lc) as total_lc')
                            ->whereYear('orders.created_at', request('year') ?? date('Y'))
                            ->join('parties', 'orders.party_id', '=', 'parties.id')
                            ->join('users', 'parties.user_id', '=', 'users.id')
                            ->groupBy('users.country')
                            ->orderByDesc('total_lc')
                            ->take(4)
                            ->get();

        $total_lc_values = $best_countries->pluck('total_lc')->toArray();
        $all_countries = $best_countries->pluck('country')->toArray();

        return response()->json([
            'all_countries' => $all_countries,
            'total_lc_values' => $total_lc_values,
        ]);
    }

    public function yearlyStatistics()
    {

       $data['earnings'] = Voucher::where('status', 1)
                            ->where('is_profit', 1)
                            ->whereYear('date', request('year') ?? date('Y'))
                            ->selectRaw('MONTHNAME(date) as month, SUM(amount) as total')
                            ->orderBy('date')
                            ->groupBy('date')
                            ->get();

        $data['expenses'] = Voucher::where('type', 'debit')
                                ->where('status', 1)
                                ->whereYear('date', request('year') ?? date('Y'))
                                ->selectRaw('MONTHNAME(date) as month, SUM(amount) as total')
                                ->orderBy('date')
                                ->groupBy('date')
                                ->get();

        return response()->json($data);
    }

    public function orderRatio()
    {
        $data['orders'] = OrderDetails::whereYear('created_at', request('year') ?? date('Y'))
                            ->selectRaw('MONTHNAME(created_at) as month, SUM(qty) as total_qty')
                            ->orderBy(DB::raw('DAY(created_at)'))
                            ->groupBy('month')
                            ->get();

        return response()->json($data);
    }

    public function maanOrderFilter(Request $request)
    {
        $orders = Order::when($request->status != 'all', function ($query) use ($request) {
            $query->where('status', $request->status);
        })->latest()->take(10)->get();

        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.dashboard.order-datas', compact('orders'))->render()
            ]);
        }
    }

    // public function maanPartyFilter(Request $request)
    // {
    //     $parties = Party::whereIn('type', ['buyer', 'customer'])
    //         ->with('user')
    //         ->whereYear('created_at', '=', $request->selected_year_buyer)
    //         ->orderByDesc('balance')
    //         ->take(5)
    //         ->get();

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'data' => view('pages.dashboard.party-datas', compact('parties'))->render()
    //         ]);
    //     }

    // }
}
