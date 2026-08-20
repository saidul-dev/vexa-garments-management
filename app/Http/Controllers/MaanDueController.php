<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Party;
use Illuminate\Http\Request;

class MaanDueController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dues-read')->only('index');
    }

    public function index()
    {
        $banks = Bank::whereStatus(1)->latest()->get();
        $total_bill = Party::whereType(request('type'))->sum('total_bill');
        $advance_amount = Party::whereType(request('type'))->sum('advance_amount');
        $pay_amount = Party::whereType(request('type'))->sum('pay_amount');
        $due_amount = Party::whereType(request('type'))->sum('due_amount');
        $parties = Party::with('currency')
            ->whereType(request('type'))
            ->where('due_amount', '>', 0)
            ->latest()->paginate();

        return view('pages.accounts.general.dues.index', compact('parties', 'total_bill', 'advance_amount', 'pay_amount', 'due_amount', 'banks'));
    }

    public function maanFilter(Request $request)
    {
        $parties = Party::with('currency:id,name')
            ->where('due_amount', '>', 0)
            ->when($request->per_page, function($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('remarks', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.accounts.general.dues.datas', compact('parties'))->render()
            ]);
        }
        return redirect(url()->previous());
    }
}
