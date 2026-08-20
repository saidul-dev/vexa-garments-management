<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Order;
use App\Models\Party;
use App\Models\Production;
use App\Models\Voucher;
use Illuminate\Http\Request;

class MaanReportController extends Controller
{
    public function order()
    {
        $orders = Order::with('party', 'merchandiser', 'bank')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->latest()
            ->get();
        return view('pages.report.orders', compact('orders'));
    }

    public function orderFilter(Request $request)
    {
        $orders = Order::with('party', 'merchandiser', 'bank')
            ->when(request('days') == 'daily', function ($q) {
                $q->whereDate('created_at', now()->format('Y-m-d'));
            })
            ->when(request('days') == 'weekly', function ($q) {
                $q->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')]);
            })
            ->when(request('days') == 'monthly', function ($q) {
                $q->whereMonth('created_at', now()->format('m'));
            })
            ->when(request('days') == 'yearly', function ($q) {
                $q->whereYear('created_at', now()->format('Y'));
            })

            ->latest()
            ->get();
        return response()->json([
            'data' => view('pages.report.order-datas', compact('orders'))->render(),
        ]);
    }

    public function production()
    {
        $productions1 = Production::with('order:id,party_id,order_no', 'order.party:id,name,type')
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->latest()
            ->get();
        $productions = collect($productions1)->groupBy('order_id');

        return view('pages.report.productions', compact('productions'));
    }

    public function productionFilter(Request $request)
    {
        $productions1 = Production::with('order:id,party_id,order_no', 'order.party:id,name,type')
            ->when(request('days') == 'daily', function ($q) {
                $q->whereDate('created_at', now()->format('Y-m-d'));
            })
            ->when(request('days') == 'weekly', function ($q) {
                $q->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')]);
            })
            ->when(request('days') == 'monthly', function ($q) {
                $q->whereMonth('created_at', now()->format('m'));
            })
            ->when(request('days') == 'yearly', function ($q) {
                $q->whereYear('created_at', now()->format('Y'));
            })
            ->latest()
            ->get();

        $productions = collect($productions1)->groupBy('order_id');

        return response()->json([
            'data' => view('pages.report.production-datas', compact('productions'))->render(),
        ]);
    }

    public function collection()
    {
        $incomes = Income::with('party')->whereHas('party')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->latest()
            ->get();

        return view('pages.report.due-collections.index', compact('incomes'));
    }

    public function collectionFilter(Request $request)
    {
        $incomes = Income::with('party')
            ->where('total_bill', '>', 0)
            ->when($request->from_date, function ($q) {
                $q->whereDate('created_at', '>=', request('from_date'));
            })
            ->when($request->to_date, function ($q) {
                $q->whereDate('created_at', '<=', request('to_date'));
            })
            ->latest()
            ->get();

        return response()->json([
            'data' => view('pages.report.due-collections.datas', compact('incomes'))->render(),
        ]);
    }

    public function transaction()
    {
        $transactions = Voucher::whereIn('type', ['debit', 'credit'])
            ->with([
                'income' => function ($query) {
                    $query->select('id', 'category_name');
                },
                'expense' => function ($query) {
                    $query->select('id', 'category_name');
                },
                'party' => function ($query) {
                    $query->select('id', 'name', 'type');
                },
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->latest()
            ->get();

        return view('pages.report.transactions', compact('transactions'));
    }

    public function transactionFilter(Request $request)
    {
        $transactions = Voucher::whereIn('type', ['debit', 'credit'])->with([
            'income' => function ($query) {
                $query->select('id', 'category_name');
            },
            'expense' => function ($query) {
                $query->select('id', 'category_name');
            },
            'party' => function ($query) {
                $query->select('id', 'name', 'type');
            },
            'user' => function ($query) {
                $query->select('id', 'name');
            },
        ])
            ->when($request->from_date, function ($q) {
                $q->whereDate('date', '>=', request('from_date'));
            })
            ->when($request->to_date, function ($q) {
                $q->whereDate('date', '<=', request('to_date'));
            })
            ->latest()
            ->get();

        return response()->json([
            'data' => view('pages.report.transaction-datas', compact('transactions'))->render(),
        ]);
    }

    public function cashbook()
    {
        $vouchers = Voucher::where('payment_method', 'cash')
            ->whereIn('type', ['debit', 'credit'])
            ->whereDate('created_at', today())
            ->with('income', 'expense')
            ->get();

        $yesterday_data = Voucher::where('payment_method', 'cash')
            ->whereIn('type', ['debit', 'credit'])
            ->whereDate('created_at', '<', today())
            ->with('income', 'expense')
            ->latest()
            ->first();

        return view('pages.report.daily-cashbook', compact('vouchers', 'yesterday_data'));
    }

    public function cashbookFilter(Request $request)
    {
        $vouchers = Voucher::where('payment_method', 'cash')
            ->whereIn('type', ['debit', 'credit'])
            ->with('income', 'expense')
            ->when($request->from_date, function ($q) {
                $q->whereDate('date', '>=', request('from_date'));
            })
            ->when($request->to_date, function ($q) {
                $q->whereDate('date', '<=', request('to_date'));
            })
            ->get();

        $yesterday_data = Voucher::where('payment_method', 'cash')
            ->whereIn('type', ['debit', 'credit'])
            ->whereDate('created_at', '<', today())
            ->with('income', 'expense')
            ->latest()
            ->first();

        return response()->json([
            'data' => view('pages.report.daily-cashbook-datas', compact('vouchers', 'yesterday_data'))->render(),
        ]);
    }

    public function payableDues()
    {
        $expenses = Expense::with('party')->whereHas('party')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->latest()
            ->get();

        return view('pages.report.payable-dues.index', compact('expenses'));
    }

    public function payableDuesFilter(Request $request)
    {
        $expenses = Expense::with('party')
            ->where('total_bill', '>', 0)
            ->when($request->from_date, function ($q) {
                $q->whereDate('created_at', '>=', request('from_date'));
            })
            ->when($request->to_date, function ($q) {
                $q->whereDate('created_at', '<=', request('to_date'));
            })
            ->latest()
            ->get();

        return response()->json([
            'data' => view('pages.report.payable-dues.datas', compact('expenses'))->render(),
        ]);
    }

    public function partyDues()
    {
        $due_amount = Party::whereType(request('party_type'))->sum('due_amount');
        $parties = Party::with('user')->whereType(request('party_type'))
            ->where('due_amount', '>', 0)
            ->latest()
            ->paginate();

        return view('pages.report.party-dues.index', compact('parties', 'due_amount'));
    }

    public function partyDuesFilter(Request $request)
    {
        $parties = Party::with('user')->where('due_amount', '>', 0)
            ->when($request->per_page, function ($query) use ($request) {
                $query->where('type', $request->party_type);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('remarks', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.report.party-dues.datas', compact('parties'))->render(),
            ]);
        }

        return redirect(url()->previous());
    }
}