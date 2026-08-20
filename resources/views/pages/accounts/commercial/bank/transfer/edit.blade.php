@extends('layouts.master')

@section('title')
    Edit Transfer
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="table-header">
                <h4>{{__('Edit Transfer')}}</h4>
            </div>
            <div class="order-form-section">
                {{-- form start --}}
                <form action="{{ route('transfers.update',$transfer->id) }}" method="post" class="ajaxform">
                    @csrf
                    @method('put')

                    <div class="add-suplier-modal-wrapper">
                        <div class="row">
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Transfer Type')}}</label>
                                <select class="w-100 form-control transfer_type" disabled>
                                    <option @selected($transfer->transfer_type == 'bank_to_bank') value="bank_to_bank" >{{__('Bank to Bank Transfer')}}</option>
                                    <option @selected($transfer->transfer_type == 'bank_to_cash') value="bank_to_cash" >{{__('Bank to Cash Transfer')}}</option>
                                    <option @selected($transfer->transfer_type == 'cash_to_bank') value="cash_to_bank" >{{__('Cash to Bank Transfer')}}</option>
                                    <option @selected($transfer->transfer_type == 'adjust_bank') value="adjust_bank" >{{__('Adjust Bank Balance')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Date')}}</label>
                                <input name="date" type="date" class="form-control datepicker date" placeholder="Adjustment Date" required value="{{ $transfer->date }}">
                            </div>
                            <div class="col-sm-6 mt-2 type {{ $transfer->transfer_type == 'adjust_bank' ? '':'d-none' }}">
                                <label>{{__('Adjust Type')}}</label>
                                <select class="w-100 form-control adjust_type" required disabled>
                                    <option @selected($transfer->adjust_type == 'deposit') value="deposit">{{ __('Deposit') }}</option>
                                    <option @selected($transfer->adjust_type == 'withdraw') value="withdraw">{{ __('Withdraw') }}</option>
                                </select>
                            </div>
                            @if (in_array($transfer->transfer_type, ['bank_to_bank', 'bank_to_cash']) || $transfer->adjust_type == 'withdraw')
                            <div class="col-lg-6 mt-2" id="bank_from">
                                <label>{{__('Bank From')}}</label>
                                <select name="bank_from" class="form-control w-100 bank_from" disabled>
                                    <option value="">-{{ __('Select Bank') }}-</option>
                                    @foreach($bankInfo as $bank_from)
                                        <option @selected($bank_from->id == $transfer->bank_from) value="{{ $bank_from->id }}">{{ $bank_from->bank_name }} ({{ $bank_from->account_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            @if (in_array($transfer->transfer_type, ['bank_to_bank', 'cash_to_bank']) || $transfer->adjust_type == 'deposit')
                            <div class="col-lg-6 mt-2" id="bank_to">
                                <label>{{__('Bank To')}}</label>
                                <select name="bank_to" class="form-control w-100 bank_to" disabled>
                                    <option value="">-{{ __('Select Bank') }}-</option>
                                    @foreach($bankInfo as $bank_to)
                                        <option @selected($bank_to->id == $transfer->bank_to) value="{{ $bank_to->id }}" >{{ $bank_to->bank_name }} ({{ $bank_to->account_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Amount')}}</label>
                                <input type="number" name="amount" required class="form-control amount" placeholder="50,000.00" value="{{ $transfer->amount }}">
                            </div>
                            <div class="col-lg-6 mt-2 cash_type {{ $transfer->transfer_type == 'bank_to_cash' ? '':'d-none' }}">
                                <label>{{__('Cash Type')}}</label>
                                <input type="text" name="cash_type" required class="form-control cash_type" placeholder="Petty Cash / Main Cash" value="{{ $transfer->meta['cash_type'] ?? '' }}">
                            </div>

                            <div class="col-lg-12 mt-2">
                                <label>{{__('Note')}}</label>
                                <textarea name="note" class="form-control">{{ $transfer->note }}</textarea>
                            </div>
                            <div class="col-lg-12">
                                <div class="button-group text-center mt-5">
                                    <button type="reset" class="theme-btn border-btn m-2">{{__('Reset')}}</button>
                                    <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- form end --}}
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
