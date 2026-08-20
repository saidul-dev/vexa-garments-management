@extends('layouts.master')

@section('title')
    Debit Voucher
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="table-header">
                <h4>{{__('Debit Voucher')}}</h4>
                <div class="button-group nav">
                    <a href="{{ route('debit-vouchers.index') }}" class="add-report-btn active"><i class="fas fa-list me-1"></i> Debit List</a>
                </div>
            </div>
            <div class="order-form-section">
                <form action="{{ route('debit-vouchers.update', $voucher->id) }}" method="post" class="ajaxform">
                    @csrf
                    @method('put')

                    <div class="add-suplier-modal-wrapper d-block">
                        <div class="row">
                            <div class="col-lg-6 mt-2">
                                <label>
                                    {{ __('Select Party') }} 
                                    @if ($voucher->party_id)
                                    <span class="party-balance fw-bold">{{ in_array(optional($voucher->party)->type, ['buyer', 'customer']) ? "Balance: ". currency_format(optional($voucher->party)->balance) : 'Due: ' . currency_format(optional($voucher->party)->due_amount) }}</span>
                                    @endif
                                </label>
                                <select name="party_id" class="form-control party_id" required disabled>
                                    <option value="">{{__('Select a Party') }}</option>
                                    <option @selected($voucher->party_id == '') data-url="{{ route('get-invoices', 'others') }}" value="others">{{__('Others')}}</option>
                                    @foreach($parties as $party)
                                        <option @selected($voucher->party_id == $party->id) data-type="{{ $party->type }}" value="{{ $party->id }}">
                                            {{ $party->name }} ({{ optional($party->user)->phone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mt-2">
                                <label>{{ __('Bill Date') }}</label>
                                <input type="date" name="date" value="{{ date('Y-m-d', strtotime($voucher->date)) }}"  class="form-control datepicker" required>
                            </div>

                            <div class="col-lg-6 mt-2">
                                <label>{{ $voucher->expense_id ? 'Select Invoice':'Payment Purpose' }} <span class="fw-bold due-by-invoice d-{{ $voucher->expense_id ? '':'d-none' }}">{{ $voucher->party_id ? currency_format(optional($voucher->expense)->total_due) : '' }}</span></label>
                                <div class="input-group">
                                    <select name="expense_id" class="form-control table-select expense_id" required>
                                        <option value="">{{__('Select Payment Purpose')}}</option>
                                        @foreach($expenses as $expense)
                                        <option data-total_due="{{ $expense->total_due }}" @selected($expense->id == $voucher->expense_id) value="{{ $expense->id }}">{{ $expense->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <a href="{{ route('expense.index') }}" class="modal-btn-ctg input-group-text"><i class="fal fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mt-2">
                                <label>{{__('Payment method')}}</label>
                                <div class="input-group">
                                    <select name="payment_method" class="form-control table-select debit_payment_method" required>
                                        <option @selected($voucher->payment_method == 'cash') value="cash">{{__('Cash')}}</option>
                                        <option @selected($voucher->payment_method == 'bank') value="bank">{{__('Bank')}}</option>
                                        <option @selected($voucher->payment_method == 'cheque') value="cheque">{{__('Cheque')}}</option>
                                        @if ($voucher->party_id && in_array(optional($voucher->party)->type, ['supplier']))
                                        <option @selected($voucher->payment_method == 'party_balance') value="party_balance">{{__('Wallet')}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 mt-2 bank_cheque_input {{ $voucher->payment_method == 'bank' || $voucher->payment_method == 'cheque' ? '' : 'd-none' }}">
                                <label>{{ __('Select Bank') }}</label>
                                <select name="bank_id" class="form-control w-100" required>
                                    <option value="">-{{ __('Select Bank') }}-</option>
                                    @foreach($banks as $bank)
                                        <option @selected($voucher->bank_id == $bank->id) value="{{ $bank->id }}" >{{ $bank->bank_name .' - '. $bank->account_number }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mt-2 cheque_input {{ $voucher->payment_method == 'cheque' ? '' : 'd-none' }}">
                                <label>{{__('Cheque No')}}</label>
                                <input type="number" name="cheque_no" value="{{ $voucher->meta['cheque_no'] ?? '' }}" class="form-control" placeholder="0202982883" required>
                            </div>

                            <div class="col-sm-6 mt-2 cheque_input {{ $voucher->payment_method == 'cheque' ? '' : 'd-none' }}">
                                <label>Issue Date</label>
                                <input type="date" name="issue_date" value="{{ date('Y-m-d', strtotime($voucher->meta['issue_date'] ?? '')) }}" class="form-control datepicker date" required>
                            </div>

                            <div class="col-lg-6 mt-2 bill_type  {{ in_array(optional($voucher->party)->type, ['supplier']) || !$voucher->party_id ? 'd-none' : '' }}">
                                <label>{{ __('Select payment type') }}</label>
                                <select name="bill_type" class="form-control w-100 bill_type_option" required>
                                    <option value="">{{ __('Select a bill type') }}</option>
                                    <option @selected($voucher->bill_type == 'due_bill') value="due_bill">{{__('Due Bill')}}</option>
                                    <option @selected($voucher->bill_type == 'advance_payment') value="advance_payment">{{__('Advance Payment')}}</option>
                                    <option @selected($voucher->bill_type == 'balance_withdraw') value="balance_withdraw">{{__('Balance withdraw')}}</option>
                                </select>
                            </div>

                            <div class="col-lg-6 mt-2 {{ $voucher->expense_id ? 'd-none':'' }}"">
                                <label>{{__('Debit Voucher No')}}</label>
                                <input name="voucher_no" type="text" value="{{ $voucher->voucher_no }}" class="form-control" placeholder="Debit Voucher No">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Debit Amount')}}</label>
                                <input type="number" step="any" name="amount" id="amount" class="form-control amount party_input" placeholder="Debit Amount" value="{{ $voucher->amount }}" required>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Particulars')}}</label>
                                <input name="particulars" value="{{ $voucher->particulars }}" type="text" class="form-control" placeholder="Particulars">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Remarks')}}</label>
                                <input name="remarks" value="{{ $voucher->remarks }}" type="text" class="form-control" placeholder="Remarks">
                            </div>
                            <div class="col-lg-12">
                                <div class="button-group text-center mt-5">
                                    <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                    <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
