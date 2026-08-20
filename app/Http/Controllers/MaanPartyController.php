<?php

namespace App\Http\Controllers;

use App\Models\AccessoryOrder;
use App\Models\Bank;
use App\Models\Cash;
use App\Models\Order;
use App\Models\User;
use App\Models\Party;
use App\Models\Voucher;
use App\Models\Transfer;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class MaanPartyController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:parties-create')->only('create', 'store');
        $this->middleware('permission:parties-read')->only('index', 'show');
        $this->middleware('permission:parties-update')->only('edit', 'update');
        $this->middleware('permission:parties-delete')->only('destroy');
    }

    public function index()
    {
        $data['totalBill'] = Party::whereType(request('parties') ?? request('parties-type'))->sum('total_bill');
        $data['payAmount'] = Party::whereType(request('parties') ?? request('parties-type'))->sum('pay_amount');
        $data['dueAmount'] = Party::whereType(request('parties') ?? request('parties-type'))->sum('due_amount');
        $data['advanceAmount'] = Party::whereType(request('parties') ?? request('parties-type'))->sum('advance_amount');

        $data['parties'] = Party::with('user')
                            ->when(request('parties') || request('parties-type'), function($q) {
                                $q->where('type', request('parties'))
                                ->orWhere('type', request('parties-type'));
                            })
                            ->latest()
                            ->paginate(10);

        return view('pages.parties.index', $data);
    }

    /** filter the table list */
    public function filter(Request $request)
    {
        $parties = Party::with('user')
            ->when($request->per_page, function($q) use ($request) {
                $q->where('type', $request->type);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('address', 'like', '%' . $request->search . '%')
                        ->orWhere('total_bill', 'like', '%' . $request->search . '%')
                        ->orWhere('advance_amount', 'like', '%' . $request->search . '%')
                        ->orWhere('due_amount', 'like', '%' . $request->search . '%')
                        ->orWhere('pay_amount', 'like', '%' . $request->search . '%')
                        ->orWhere('opening_balance', 'like', '%' . $request->search . '%')
                        ->orWhere('remarks', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('user', function ($q) use ($request) {
                    $q->where('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%')
                        ->orWhere('country', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);


        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.parties.datas', compact('parties'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function create()
    {
        $banks = Bank::latest()->get();
        $countries = base_path('lang/countrylist.json');
        $countries = json_decode(file_get_contents($countries), true);
        return view('pages.parties.create', compact('banks', 'countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_bill' => 'nullable|integer',
            'type' => 'required|string|max:20',
            'image' => 'nullable|image',
            'name' => 'required|string|max:1000',
            'remarks' => 'nullable|string|max:1000',
            'password' => 'required|string|min:4|max:15',
            'address' => 'nullable|string|max:50|min:2',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|max:20|min:5',
            'opening_balance' => 'required_if:opening_balance_type,advance_payment|max:99999999999',
            'receivable_type' => 'required_if:opening_balance_type,advance_payment',
        ]);
        
        if ($request->opening_balance_type == '' && $request->opening_balance > 0) {
            return response()->json(__('The opening balance type field is required.'), 400);
        }
        if ($request->opening_balance_type && ($request->opening_balance == '' || $request->opening_balance == 0)) {
            return response()->json(__('The opening balance field is required.'), 400);
        }

        DB::beginTransaction();
        try {

            $user = User::create($request->except('image', 'password') + [
                'role' => $request->type,
                'password' => Hash::make($request->password),
                'image' => $request->image ? $this->upload($request, 'image') : NULL,
            ]);
            $role = Role::where('name', $request->type)->first();
            $user->assignRole($role);

            $party = Party::create($request->except('image', 'opening_balance', 'receivable_type') + [
                'user_id' => $user->id,
                'creator_id' => auth()->id(),
                'opening_balance' => $request->opening_balance,
                'due_amount' => $request->opening_balance_type == 'due_bill' ? $request->opening_balance : 0,
                'balance' => $request->opening_balance_type == 'advance_payment' ? $request->opening_balance : 0,
                'advance_amount' => $request->opening_balance_type == 'advance_payment' ? $request->opening_balance : 0,
                'receivable_type' =>  $request->opening_balance_type == 'advance_payment' ? $request->receivable_type : NULL,
                'meta' => [
                    'bank_id' => $request->bank_id
                ]
            ]);

            if ($request->opening_balance > 0 && $request->opening_balance_type == 'advance_payment') {

                $company_balance = company_balance();

                $voucher = Voucher::create($request->except('type') + [
                    'date' => today(),
                    'party_id' => $party->id,
                    'prev_balance' => $company_balance,
                    'amount' => $request->opening_balance,
                    'bill_type' => $request->opening_balance_type,
                    'payment_method' => $request->receivable_type,
                    'type' => in_array($party->type, ['supplier']) ? 'debit' : 'credit',
                    'current_balance' => in_array($party->type, ['supplier']) ? ($company_balance - $request->opening_balance) : ($company_balance + $request->opening_balance),
                ]);

                if ($request->type == 'buyer' || $request->type == 'customer') {

                    if ($request->receivable_type == 'cash') {
                        Cash::create([
                            'date' => today(),
                            'type' => 'credit',
                            'user_id' => auth()->id(),
                            'voucher_id' => $voucher->id,
                            'amount' => $request->opening_balance,
                            'bank_id' => 'new_party_create',
                            'description' => $request->remarks,
                            'cash_type' => $request->type.'_payment',
                        ]);
                    } elseif ($request->receivable_type == 'bank') {
                        $bank = Bank::findOrfail($request->bank_id);
                        Transfer::create([
                            'date' => now(),
                            'bank_to' => $bank->id,
                            'user_id' => auth()->id(),
                            'adjust_type' => 'credit',
                            'note' => $request->remarks,
                            'voucher_id' => $voucher->id,
                            'transfer_type' => 'adjust_bank',
                            'amount' => $request->opening_balance,
                            'meta' => 'new_party_create',
                        ]);
                        $bank->update([
                            'balance' => $bank->balance + $request->opening_balance
                        ]);
                    }
                } elseif (in_array($party->type, ['supplier'])) {

                    if ($request->receivable_type == 'cash') {
                        if($request->opening_balance <= cash_balance()) {

                            Cash::create($request->except('amount', 'type') + [
                                'type' => 'debit',
                                'date' => today(),
                                'user_id' => auth()->id(),
                                'description' => $request->remarks,
                                'bank_id' => $request->type.'_bill',
                                'voucher_id' => $voucher->id,
                                'amount' => $request->opening_balance,
                                'cash_type' => $request->type.'_payment',
                            ]);

                        } else {
                            return response()->json(__('Amount can not more than cash balance'), 400);
                        }

                    } elseif ($request->receivable_type == 'bank') {

                        $bank = Bank::findOrfail($request->bank_id);
                        if($request->opening_balance <= $bank->balance) {
                            Transfer::create([
                                'date' => now(),
                                'adjust_type' => 'debit',
                                'bank_from' => $bank->id,
                                'user_id' => auth()->id(),
                                'note' => $request->remarks,
                                'voucher_id' => $voucher->id,
                                'meta' => 'new_party_create',
                                'transfer_type' => 'adjust_bank',
                                'amount' => $request->opening_balance,
                            ]);

                            $bank->update([
                                'balance' => $bank->balance - $request->opening_balance
                            ]);
                        } else {
                            return response()->json(__('Amount can not more than bank balance'), 400);
                        }
                    }
                }
            }

            sendNotification($party->id, route('parties.index', ['parties' => $request->type]), __(ucfirst($request->type).' has been created.'), __(ucfirst($request->type)), null, null, true);
            
            DB::commit();
            return response()->json([
                'message'   => __(ucfirst($request->type).' created successfully'),
                'redirect'  => route('parties.index', ['parties-type' => $request->type])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function edit(Party $party)
    {
        $party->load('user');
        $banks = Bank::latest()->get();
        $countries = base_path('lang/countrylist.json');
        $voucher = Voucher::where('party_id', $party->id)->first();
        $countries = json_decode(file_get_contents($countries), true);
        return view('pages.parties.edit', compact('party', 'banks', 'countries', 'voucher'));
    }

    public function update(Request $request, Party $party)
    {
        $request->validate([
            'image' => 'nullable|image',
            'total_bill' => 'nullable|integer',
            'type' => 'required|string|max:20',
            'name' => 'required|string|max:1000',
            'remarks' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20|min:5',
            'address' => 'nullable|string|max:50|min:2',
            'password' => 'nullable|string|min:4|max:15',
            'email' => 'nullable|email|unique:users,email,'.$party->user_id,
            'receivable_type' => 'required_if:opening_balance_type,advance_payment',
            'opening_balance' => 'required_if:opening_balance_type,advance_payment|max:99999999999',
        ]);

        if ($request->opening_balance_type == '' && $request->opening_balance > 0) {
            return response()->json(__('The opening balance type field is required.'), 400);
        }
        if ($request->opening_balance_type && ($request->opening_balance == '' || $request->opening_balance == 0)) {
            return response()->json(__('The opening balance field is required.'), 400);
        }

        // Should be chnage
        $has_prev_vouchers = Voucher::where('party_id', $party->id)->whereNotNull(in_array($party->type, ['buyer', 'customer']) ? 'income_id' : 'expense_id')->exists();
        if ($has_prev_vouchers) {
            return response()->json([
                'message' => __("You can not update the opening balance now. Because this party already has transactions.")
            ], 400);
        }

        DB::beginTransaction();
        try {

            $user = User::find($party->user_id);
            $user->update($request->except('image', 'password') + [
                'role' => $request->type,
                'password' => $request->password ? Hash::make($request->password) : $user->password, // Default password
                'image' => $request->image ? $this->upload($request, 'image', $party->image) : $party->user->image,
            ]);

            if ($request->opening_balance > 0 && $request->opening_balance_type == 'advance_payment') {

                $voucher = Voucher::where('party_id', $party->id)->first();

                if (!$voucher) {
                    if ($request->receivable_type == 'cash') {
                        $company_balance = cash_balance();
                    } else {
                        $company_balance = Bank::sum('balance');
                    }

                    $voucher = Voucher::create($request->except('type') + [
                        'party_id' => $party->id,
                        'amount' => $request->opening_balance,
                        'prev_balance' => $company_balance,
                        'bill_type' => $request->opening_balance_type,
                        'payment_method' => $request->receivable_type,
                        'type' => in_array($party->type, ['supplier']) ? 'debit' : 'credit',
                        'current_balance' => in_array($party->type, ['supplier']) ? ($company_balance - $request->opening_balance) : ($company_balance + $request->opening_balance),
                    ]);
                }

                // IF PREVIOUS OR CURRENT PAYMENT METHOD IS CASH
                if ($party->receivable_type == 'cash' && $request->receivable_type == 'cash') {
                    $cash = Cash::where('voucher_id', $voucher->id)->first();
                    $cash->update([
                        'user_id' => auth()->id(),
                        'amount' => $request->opening_balance,
                        'description' => $request->remarks,
                        'bank_id' => $party->type.'_payment',
                        'cash_type' => $party->type.'_payment',
                        'type' => in_array($party->type, ['supplier']) ? 'debit':'credit',
                    ]);
                } elseif ($party->receivable_type != 'cash' && $request->receivable_type == 'cash') {
                    Cash::create([
                        'date' => today(),
                        'user_id' => auth()->id(),
                        'voucher_id' => $voucher->id,
                        'bank_id' => 'new_party_create',
                        'description' => $request->remarks,
                        'amount' => $request->opening_balance,
                        'cash_type' => $party->type.'_payment',
                        'type' => in_array($party->type, ['supplier']) ? 'debit':'credit',
                    ]);
                } elseif ($party->receivable_type == 'cash' && $request->receivable_type != 'cash') {
                    $cash = Cash::where('voucher_id', $voucher->id)->first();
                    $cash->delete();
                }

                // IF PREVIOUS OR CURRENT PAYMENT METHOD IS BANK
                if ($party->receivable_type == 'bank' && $request->receivable_type != 'bank') {
                    $transfer = Transfer::where('voucher_id', $voucher->id)->first();
                    $transfer->forceDelete();
                    $bank = Bank::findOrFail($voucher->bank_id);
                    $bank->update([
                        'balance' => $bank->balance - $voucher->amount,
                    ]);
                } elseif ($party->receivable_type == 'bank' && $request->receivable_type == 'bank') {

                    if ($voucher->bank_id != $request->bank_id) {
                        $bank = Bank::findOrFail($voucher->bank_id);
                        $bank->update([
                            'balance' => $bank->balance - $voucher->amount,
                        ]);
                    }
    
                    $current_bank = Bank::findOrFail($request->bank_id);
                    if (in_array($party->type, ['buyer', 'customer'])) {
                        $current_bank->update([
                            'balance' => $voucher->bank_id == $request->bank_id ? ($current_bank->balance - $voucher->amount) + $request->opening_balance : ($current_bank->balance + $request->opening_balance),
                        ]);
                    } else {
                        $current_bank->update([
                            'balance' => $voucher->bank_id == $request->bank_id ? ($current_bank->balance + $voucher->amount) - $request->opening_balance : ($current_bank->balance - $request->opening_balance),
                        ]);
                    }
    
                    $transfer = Transfer::where('voucher_id', $voucher->id)->first();
                    $transfer->update($request->all() + [
                        'user_id' => auth()->id(),
                        'note' => $request->remarks,
                        'bank_to' => $request->bank_id,
                        'amount' => $request->opening_balance,
                    ]);
                } elseif ($party->receivable_type != 'bank' && $request->receivable_type == 'bank') {
                    $bank = Bank::findOrFail($request->bank_id);
                    Transfer::create($request->all() + [
                        'user_id' => auth()->id(),
                        'bank_to' => $bank->id,
                        'adjust_type' => 'credit',
                        'note' => $request->remarks,
                        'voucher_id' => $voucher->id,
                        'meta' => 'new_party_create',
                        'transfer_type' => 'adjust_bank',
                    ]);

                    $bank->update([
                        'balance' => $bank->balance + $request->opening_balance,
                    ]);
                }

                $voucher->update($request->except('type') + [
                    'date' => today(),
                    'user_id' => auth()->id(),
                    'description' => $request->remarks,
                    'amount' => $request->opening_balance,
                    'current_balance' => $voucher->prev_balance + $request->opening_balance,
                ]);
            } else {
                $voucher = Voucher::where('party_id', $party->id)->first();
                if ($voucher) {
                    $voucher->delete();
                }
            }

            sendNotification($party->id, route('parties.index', ['parties' => $party->type]), __(ucfirst($party->type).' has been updated.'), __(ucfirst($party->type)), null, null, true);

            $party->update($request->except('receivable_type') + [
                'opening_balance' => $request->opening_balance,
                'due_amount' => $request->opening_balance_type == 'due_bill' ? $request->opening_balance : 0,
                'balance' => $party->opening_balance_type == 'advance_payment' ? $request->opening_balance : 0,
                'advance_amount' => $party->opening_balance_type == 'advance_payment' ? $request->opening_balance : 0,
                'receivable_type' =>  $request->opening_balance_type == 'advance_payment' ? $request->receivable_type : NULL,
                'meta' => [
                    'bank_id' => $party->meta['bank_id'] ?? null
                ]
            ]);

            DB::commit();
            return response()->json([
                'message'   => __(ucfirst($party->type).' updated successfully'),
                'redirect'  => route('parties.index', ['parties-type' => $party->type])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function show(Party $party)
    {
        if (request()->ajax()) {
            return response()->json($party);
        }
    }

    public function destroy(Party $party)
    {
        $has_prev_vouchers = Voucher::where('party_id', $party->id)->exists();
        if ($has_prev_vouchers) {
            return response()->json([
                'message' => __("You can not delete this " . $party->type . ". Because this party already has transactions.")
            ], 400);
        }

        DB::beginTransaction();
        try {
            if (file_exists($party->user->image)) {
                Storage::delete($party->user->image);
            }
            Order::where('party_id', $party->id)->delete();
            AccessoryOrder::where('party_id', $party->id)->delete();

            $party->delete();

            DB::commit();
            return response()->json([
                'message'   => __(ucfirst($party->type).' deleted successfully'),
                'redirect'  => route('parties.index', ['parties-type' => $party->type]),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }
}
