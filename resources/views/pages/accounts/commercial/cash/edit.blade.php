@extends('layouts.master')

@section('title')
    {{ __('Cash in Hand') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="table-header">
                <h4>{{ __('Cash in Hand') }}</h4>
                <div class="button-group nav">
                    <a href="{{ route('cashes.index') }}" class="add-order-btn rounded-2 active"><i class="fas fa-arrow-left"></i> {{__('Back')}}</a>
                </div>
            </div>
            <div class="order-form-section">
                <form action="{{ route('cashes.update', $cash->id) }}" method="post" class="ajaxform">
                    @csrf
                    @method('put')

                    <div class="add-suplier-modal-wrapper mt-3">
                        <div class="row">
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Adjustment')}}</label>
                                <select name="bank_id" class="form-control table-select w-100" disabled>
                                    <option value="petty_cash">{{__('Adjust Cash')}}</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" @selected($bank->id == $cash->bank_id)>{{ $bank->bank_name }} ({{ $bank->account_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Type')}}</label>
                                <select name="type" required class="form-control table-select w-100" disabled>
                                    <option value="">{{__('Select Type')}}</option>
                                    <option value="debit" @selected('debit' == $cash->type)>{{__('Debit')}}</option>
                                    <option value="credit" @selected('credit' == $cash->type)>{{__('Credit')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Enter Amount')}}</label>
                                <input type="number" step="any" name="amount" value="{{ $cash->amount }}" class="form-control amount" placeholder="$500.00">
                            </div>
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Note')}}</label>
                                <input type="text" name="cash_type" value="{{ $cash->cash_type }}" class="form-control" placeholder="main cash / petty cash">
                            </div>
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Adjustment Date')}}</label>
                                <input type="date" name="date" value="{{ $cash->date }}" required class="form-control" placeholder="Adjustment Date">
                            </div>

                            <div class="col-lg-12 mt-1">
                                <label>{{__('Description')}} </label>
                                <textarea name="description" class="form-control">{{ $cash->description }}</textarea>
                            </div>
                            <div class="col-lg-12">
                                <div class="button-group text-center mt-3">
                                    <a href="{{ route('cashes.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
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
