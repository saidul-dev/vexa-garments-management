<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Party;
use App\Models\Cheque;
use App\Models\Income;
use App\Models\Voucher;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaanChequeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cheques-create')->only('create', 'store');
        $this->middleware('permission:cheques-read')->only('index', 'show');
        $this->middleware('permission:cheques-update')->only('edit', 'update');
        $this->middleware('permission:cheques-delete')->only('destroy');
    }
    public function index()
    {
        $cheques = Cheque::with('bank', 'party', 'voucher.income', 'voucher.expense')
                    ->when(request('status') == 1 || request('status') === '0', function ($q) {
                        $q->where('status', request('status'));
                    })
                    ->when(!auth()->user()->can('cheques-list'), function($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->latest()
                    ->paginate();
        return view('pages.accounts.commercial.cheques.index', compact('cheques'));

    }

    /** filter the table list */
    public function maanFilter(Request $request)
    {
        $cheques = Cheque::with('party','bank','voucher.income', 'voucher.expense')
                        ->when(!auth()->user()->can('cheques-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->when($request->search, function ($q) use($request) {
                            $q->where(function($q) use($request) {
                                $q->where('type', 'like', '%'.$request->search.'%')
                                    ->orWhereHas('party', function($q) use($request){
                                        $q->where('name', 'like', '%'.$request->search.'%');
                                    })
                                    ->orWhereHas('bank', function($q) use($request){
                                        $q->where('bank_name', 'like', '%'.$request->search.'%');
                                    })
                                    ->orWhereHas('voucher.income', function($q) use($request){
                                        $q->where('category_name', 'like', '%'.$request->search.'%');
                                    })
                                    ->orWhereHas('voucher.expense', function($q) use($request){
                                        $q->where('category_name', 'like', '%'.$request->search.'%');
                                    });
                            });
                        })
                        ->latest()
                        ->get();

        if($request->ajax()){
            return response()->json([
                'data' => view('pages.accounts.commercial.cheques.datas',compact('cheques'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function update(Cheque $cheque)
    {
        $permission = check_visibility($cheque->created_at, 'cheques', $cheque->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $voucher = Voucher::findOrFail($cheque->voucher_id);
        $party = Party::find($voucher->party_id);

        DB::beginTransaction();
        try {
            if ($party) {
                if ($voucher->bill_type == 'advance_payment') {
                    $party->update([
                        'balance' => $party->balance + $voucher->amount,
                        'advance_amount' => $party->advance_amount + $voucher->amount,
                    ]);
                } elseif ($voucher->bill_type == 'due_bill') {

                    if ($voucher->income_id) {
                        $income = Income::findOrFail($voucher->income_id);
                        $income->update([
                            'total_due' => $income->total_due - $voucher->amount,
                            'total_paid' => $income->total_paid + $voucher->amount,
                        ]);
                    }

                    $party->update([
                        'due_amount' => $party->due_amount - $voucher->amount,
                        'pay_amount' => $party->pay_amount + $voucher->amount,
                    ]);
                } elseif ($voucher->bill_type == 'balance_withdraw') {
                    $party->update([
                        'balance' => $party->balance - $voucher->amount,
                    ]);
                }
            }

            $bank = Bank::findOrFail($cheque->bank_id);
            Transfer::create([
                'user_id' => auth()->id(),
                'amount' => $cheque->amount,
                'note' => $voucher->remarks,
                'voucher_id' => $voucher->id,
                'date' => $cheque->issue_date,
                'adjust_type' => $cheque->type,
                'transfer_type' => 'adjust_bank',
                'bank_to' => $voucher->type == 'credit' ? $bank->id : null,
                'bank_from' => $voucher->type == 'debit' ? $bank->id : null,
            ]);

            $current_amount = $cheque->type == 'debit' ? ($bank->balance - $cheque->amount) : ($bank->balance + $cheque->amount);
            $bank->update([
                'balance' => $current_amount
            ]);

            $voucher->update([
                'status' => 1,
                'meta' => [
                    'cheque_no' => $voucher->meta['cheque_no'] ?? '',
                    'issue_date' => $voucher->meta['issue_date'] ?? '',
                    'due_amount' => $party->due_amount ?? 0,
                ],
            ]);

            $cheque->update([
                'status' => 1
            ]);

            DB::commit();

            sendNotification($cheque->id, route('cheques.index'), __('New cheques has been created.'));
            return response()->json([
                'message'   => __('Cheque withdraw successfully'),
                'redirect'  => route('cheques.index')
            ]);

        } catch (\Exception $e){
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function destroy(Cheque $cheque)
    {
        $permission = check_visibility($cheque->created_at, 'cheques', $cheque->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        if ($cheque->status == 0) {
            $cheque->voucher()->delete();
            $cheque->delete();
            return response()->json([
                'message'   => __('Cheque deleted successfully'),
                'redirect'  => route('cheques.index')
            ]);
        } else {
            return response()->json(__('You can\'t delete this transaction, because this cheque has already been withdrawn.'), 400);
        }
    }
}
