<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Voucher;
use Illuminate\Http\Request;

class AcnooInvoiceController extends Controller
{
    public function voucherPrint($id)
    {
        $voucher = Voucher::with('party', 'user', 'income')->findOrFail($id);
        return view('pages.invoice.voucher', compact('voucher'));
    }

    public function partialPayment(Request $request)
    {

        $vouchers = Voucher::with('user:id,name')
                    ->when(request('expense_id'), function($q) {
                        $q->where('expense_id', request('expense_id'))
                        ->where('type', 'debit');
                    })
                    ->when(request('income_id'), function($q) {
                        $q->where('income_id', request('income_id'))
                        ->where('type', 'credit');
                    })
                    ->get();

        $party = Party::find($vouchers->first()['party_id'] ?? null);

        return view('pages.invoice.partial-payment', compact('vouchers', 'party'));
    }
}
