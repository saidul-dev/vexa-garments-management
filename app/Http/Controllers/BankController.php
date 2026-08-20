<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:banks-create')->only('maanStore');
        $this->middleware('permission:banks-read')->only('maanIndex');
        $this->middleware('permission:banks-update')->only('maanUpdate');
        $this->middleware('permission:banks-delete')->only('maanDelete');
    }

    /**
     * Display a listing of the Bank.
     */
    public function maanIndex()
    {
        $bank_balance = Bank::sum('balance');
        $deposit = Transfer::where('adjust_type', 'deposit')->orWhere('adjust_type', 'credit')->sum('amount');
        $withdraw = Transfer::where('adjust_type', 'withdraw')->orWhere('adjust_type', 'debit')->sum('amount');
        $banks = Bank::when(!auth()->user()->can('banks-list'), function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->latest()->paginate(10);
        $bankInfo = Bank::select('id', 'bank_name', 'account_number')->latest()->get();
        return view('pages.accounts.commercial.bank.index', compact('banks', 'bankInfo', 'bank_balance', 'deposit', 'withdraw'));
    }

    /**
     * Store a newly created bank in storage.
     */
    public function maanStore(Request $request)
    {
        //request validation
        $request->validate([
            'branch_name' => 'string',
            'routing_number' => 'nullable|string',
            'bank_name' => 'required|string',
            'holder_name' => 'required|string',
            'balance' => 'required|numeric|max:99999999999',
            'account_number' => 'required|string|unique:banks',
        ]);

        DB::beginTransaction();
        try {
            $bank = Bank::create($request->all() + [
                    'user_id' => auth()->id()
                ]);

            sendNotification($bank->id, route('banks.index'), __('New Bank has been created.'));

            DB::commit();
            return response()->json([
                'message' => __('Bank created successfully'),
                'redirect' => route('banks.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Update the Bank from storage.
     */
    public function maanUpdate(Request $request, $id)
    {
        $request->validate([
            'branch_name' => 'string',
            'routing_number' => 'nullable|string',
            'bank_name' => 'required|string',
            'holder_name' => 'required|string',
            'balance' => 'required|numeric|max:99999999999',
            'account_number' => 'required|string|unique:banks,account_number,'.$id,
        ]);
        DB::beginTransaction();
        try {
            $bank = Bank::findOrFail($id);
            $permission = check_visibility($bank->created_at, 'banks', $bank->user_id);
            if (!$permission) {
                return response()->json(__('You don\'t have permission.'), 403);
            }

            $bank->update($request->all() + [
                    'user_id' => auth()->id()
                ]);

            DB::commit();
            return response()->json([
                'message' => __('Bank updated successfully'),
                'redirect' => route('banks.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(__('Something was wrong.'));
        }
    }

    /**
     * Remove the Bank from storage.
     */
    public function maanDelete($id)
    {
        DB::beginTransaction();
        try {
            $bank = Bank::findOrFail($id);
            $permission = check_visibility($bank->created_at, 'banks', $bank->user_id);
            if (!$permission) {
                return response()->json(__('You don\'t have permission.'), 403);
            }
            
            $bank->delete();
            DB::commit();

            return response()->json([
                'message' => __('Bank deleted successfully'),
                'redirect' => route('banks.index')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

}
