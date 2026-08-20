<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MaanTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:transactions-read')->only('index', 'maanDailyTransaction');
    }

    public function index()
    {
        $credit_amount = Voucher::when(!auth()->user()->can('transactions-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->whereMonth('date', Carbon::now()->month)
                        ->where('type', 'credit')
                        ->where('status', 1)
                        ->sum('amount');

        $credit_amount = currency_format($credit_amount);

        $debit_amount = Voucher::when(!auth()->user()->can('transactions-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->whereMonth('date', Carbon::now()->month)
                        ->where('type', 'debit')
                        ->where('status', 1)
                        ->sum('amount');

        $debit_amount = currency_format($debit_amount);

        $bank_balance = currency_format(Bank::sum('balance'));

        $transactions = Voucher::whereMonth('date', Carbon::now()->month)
                            ->whereIn('type', ['debit', 'credit'])
                            ->where('status', 1)
                            ->selectRaw('DATE(date) as date, sum(amount) as total_amount, GROUP_CONCAT(DISTINCT type SEPARATOR \', \') as all_type,GROUP_CONCAT(DISTINCT remarks SEPARATOR \', \') as all_remarks, COUNT(*) as transaction_times')
                            ->groupBy('date')
                            ->when(!auth()->user()->can('transactions-list'), function($query) {
                                $query->where('user_id', auth()->id());
                            })
                            ->latest()
                            ->paginate(10);

        $total_amount = currency_format($transactions->sum('total_amount'));

        return view('pages.accounts.general.transactions.index', compact('transactions', 'credit_amount', 'total_amount', 'debit_amount', 'bank_balance'));
    }

    /** filter the table list */
    public function maanFilter(Request $request)
    {
        $data['total_amount'] = Voucher::when(!auth()->user()->can('transactions-list'), function ($query) {
                                        $query->where('user_id', auth()->id());
                                    })
                                    ->whereIn('type', ['debit', 'credit'])
                                    ->when($request->year, function ($q) use ($request) {
                                        $q->whereYear('date', $request->year);
                                    })
                                    ->when($request->month, function ($q) {
                                        $q->whereMonth('date', request('month'));
                                    })
                                    ->where('status', 1)
                                    ->sum('amount');

        $data['total_amount'] = currency_format($data['total_amount']);

        $data['credit_amount'] = Voucher::when(!auth()->user()->can('transactions-list'), function ($query) {
                                        $query->where('user_id', auth()->id());
                                    })->where('type', 'credit')
                                    ->when($request->year, function ($q) use ($request) {
                                        $q->whereYear('date', $request->year);
                                    })
                                    ->when($request->month, function ($q) {
                                        $q->whereMonth('date', request('month'));
                                    })
                                    ->where('status', 1)
                                    ->sum('amount');
        $data['credit_amount'] = currency_format($data['credit_amount']);

        $data['debit_amount'] = Voucher::when(!auth()->user()->can('transactions-list'), function ($query) {
                                        $query->where('user_id', auth()->id());
                                    })
                                    ->where('type', 'debit')
                                    ->when($request->year, function ($q) use ($request) {
                                        $q->whereYear('date', $request->year);
                                    })
                                    ->when($request->month, function ($q) {
                                        $q->whereMonth('date', request('month'));
                                    })
                                    ->where('status', 1)
                                    ->sum('amount');
        $data['debit_amount'] = currency_format($data['debit_amount']);

        $transactions = Voucher::whereIn('type', ['debit', 'credit'])->where('status', 1)->selectRaw('DATE(date) as date, sum(amount) as total_amount, GROUP_CONCAT(type SEPARATOR \', \') as all_type,GROUP_CONCAT(remarks SEPARATOR \', \') as all_remarks')
                            ->when(!auth()->user()->can('transactions-list'), function($query) {
                                $query->where('user_id', auth()->id());
                            })
                            ->when($request->year, function ($q) use ($request) {
                                $q->whereYear('date', $request->year);
                            })
                            ->when($request->month, function ($q) {
                                $q->whereMonth('date', request('month'));
                            })
                            ->when(request('search'), function ($q) {
                                $q->where(function ($q) {
                                    $q->orWhere('type', 'like', '%' . request('search') . '%')
                                        ->orWhere('remarks', 'like', '%' . request('search') . '%');
                                });
                            })
                            ->groupBy('date')
                            ->latest('id')
                            ->get();

        if ($request->ajax()) {
            return response()->json([
                'tableData' => view('pages.accounts.general.transactions.datas', compact('transactions'))->render(),
                'data' => $data,
            ]);
        }

        return redirect(url()->previous());
    }

    public function maanDailyTransaction()
    {
        $total_amount = Voucher::when(!auth()->user()->can('transactions-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->whereIn('type', ['credit', 'debit'])
                        ->where('date', request('transaction_date') ?? today())
                        ->where('status', 1)
                        ->sum('amount');

        $credit_amount = Voucher::when(!auth()->user()->can('transactions-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->where('date', request('transaction_date'))
                        ->where('type', 'credit')
                        ->where('status', 1)
                        ->sum('amount');

        $debit_amount = Voucher::when(!auth()->user()->can('transactions-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->where('date', request('transaction_date'))
                        ->where('type', 'debit')
                        ->where('status', 1)
                        ->sum('amount');

        $transactions = Voucher::when(!auth()->user()->can('transactions-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->whereIn('type', ['credit', 'debit'])
                        ->with('income', 'expense')
                        ->where('date', request('transaction_date'))
                        ->where('status', 1)
                        ->latest()
                        ->get();

        return view('pages.accounts.general.transactions.daily',compact('transactions', 'total_amount', 'credit_amount', 'debit_amount'));
    }
}
