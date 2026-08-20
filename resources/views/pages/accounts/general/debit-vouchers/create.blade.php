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
                    <a href="{{ route('debit-vouchers.index') }}" class="add-report-btn {{ Route::is('debit-vouchers.index') ? 'active' : '' }}"><i class="fas fa-list me-1"></i> Debit List</a>
                    <a href="{{ route('debit-vouchers.create') }}" class="add-report-btn {{ Route::is('debit-vouchers.create') ? 'active' : '' }}"><i class="fas fa-plus-circle me-1"></i> Create Debit</a>
                </div>
            </div>
            <div class="order-form-section">
                <form action="{{ route('debit-vouchers.store') }}" method="post" class="ajaxform">
                    @csrf

                    <div class="add-suplier-modal-wrapper d-block">
                        <div class="row">
                            <div class="col-lg-6 mt-2">
                                <label class="table-select-lebel">{{__('Party Name')}} <span class="party-balance fw-bold"></span></label>
                                <div class="input-group">
                                    <select name="party_id" class="form-control party_id remove-bg-table-select" required>
                                        <option value="">{{__('Select a Party') }}</option>
                                        <option data-url="{{ route('get-expenses', 'others') }}" value="others">{{__('Others')}}</option>
                                        @foreach($parties as $party)
                                            <option data-type="{{ $party->type }}" data-party_blnc="{{ in_array($party->type, ['buyer', 'customer']) ? $party->balance : $party->due_amount }}" data-url="{{ route('get-expenses', $party->id) }}" value="{{ $party->id }}">{{ $party->name }} ({{ optional($party->user)->phone }})</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <a href="{{ route('parties.create', ['parties-type' => 'supplier']) }}" class="modal-btn-ctg input-group-text"><i class="fal fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Bill Date')}}</label>
                                <input type="date" name="date" value="{{ now()->format('Y-m-d') }}"  class="form-control datepicker" required>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label class="table-select-lebel"><span class="invoice_label">Select</span> <span class="fw-bold due-by-invoice d-none"></span></label>
                                <div class="input-group">
                                    <select name="expense_id" class="form-control expense_id remove-bg-table-select">
                                        <option value="">{{__('Select Payment Purpose') }}</option>
                                    </select>
                                    <div class="input-group-append">
                                        <a href="{{ route('expense.index') }}" class="modal-btn-ctg input-group-text"><i class="fal fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label class="table-select-lebel">{{__('Payment method')}}</label>
                                <div class="input-group blade-up-down-arrow position-relative">
                                    <select name="payment_method" class="form-control debit_payment_method remove-bg-table-select" required>
                                        <option value="">{{__('Select payment method')}}</option>
                                        <option value="cash">{{__('Cash')}}</option>
                                        <option value="bank">{{__('Bank')}}</option>
                                        <option value="cheque">{{__('Cheque')}}</option>
                                        <option value="party_balance">{{__('Wallet')}}</option>
                                    </select>
                                    <span></span>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-2 bank_cheque_input d-none">
                                <label>{{ __('Select Bank') }}</label>
                                <select name="bank_id" class="form-control w-100" required>
                                    <option value="">-{{ __('Select Bank') }}-</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" >{{ $bank->bank_name .' - '. $bank->account_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mt-2 cheque_input d-none">
                                <label>{{__('Cheque No')}}</label>
                                <input type="number" name="cheque_no" class="form-control" placeholder="0202982883" required>
                            </div>
                            <div class="col-sm-6 mt-2 cheque_input d-none">
                                <label>Issue Date</label>
                                <input type="date" name="issue_date" value="{{ now()->format('Y-m-d') }}"  class="form-control datepicker date" required>
                            </div>
                            <div class="col-lg-6 mt-2 bill_type">
                                <label>{{ __('Select payment type') }}</label>
                                <select name="bill_type" class="form-control w-100 bill_type_option" required>
                                    <option value="">{{__('Select')}}</option>
                                    <option value="due_bill">{{__('Due Bill')}}</option>
                                    <option value="advance_payment">{{__('Advance Payment')}}</option>
                                    <option value="balance_withdraw">{{__('Balance withdraw')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mt-2 voucher_no">
                                <label>{{__('Debit Voucher No')}}</label>
                                <input name="voucher_no" type="text" class="form-control voucher-input" placeholder="Debit Voucher No">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Debit Amount')}}</label>
                                <input type="number" step="any" name="amount" id="amount" class="form-control amount party_input" placeholder="Debit Amount" required>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Particulars')}}</label>
                                <input name="particulars" type="text" class="form-control" placeholder="Particulars">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Remarks')}}</label>
                                <input name="remarks" type="text" class="form-control" placeholder="Remarks">
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
