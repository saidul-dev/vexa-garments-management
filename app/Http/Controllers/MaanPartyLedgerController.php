<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Voucher;
use Illuminate\Http\Request;

class MaanPartyLedgerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:party-ledger-read')->only('index');
    }

    public function index()
    {
        $total_bill = Party::whereType(request('type'))->sum('total_bill');
        $advance_amount = Party::whereType(request('type'))->sum('advance_amount');
        $pay_amount = Party::whereType(request('type'))->sum('pay_amount');
        $due_amount = Party::whereType(request('type'))->sum('due_amount');
        $parties = Party::whereType(request('type'))->latest()->paginate(10);
        return view('pages.accounts.general.party-ledger.index', compact('parties', 'total_bill', 'advance_amount', 'pay_amount', 'due_amount'));
    }

    /** filter the table list */
    public function filter(Request $request)
    {
        $parties = Party::whereType(request('type'))
            ->where('type', $request->type)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('type', 'like', '%' . $request->search . '%')
                        ->orWhere('total_bill', 'like', '%' . $request->search . '%')
                        ->orWhere('advance_amount', 'like', '%' . $request->search . '%')
                        ->orWhere('due_amount', 'like', '%' . $request->search . '%')
                        ->orWhere('pay_amount', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.accounts.general.party-ledger.datas', compact('parties'))->render(),
            ]);
        }
        return redirect(url()->previous());
    }

    public function show(Request $request, $id)
    {
        $party = Party::findOrFail($id);
        $vouchers = Voucher::with('bank', 'user', 'income', 'expense')
            ->where(function ($query) {
                $query->whereNull('payment_method')->orWhere('payment_method', '!=', 'party_balance');
            })
            ->where('status', 1)
            ->where('party_id', $party->id)
            ->get();

        return view('pages.accounts.general.party-ledger.show', compact('party', 'vouchers'));
    }
}