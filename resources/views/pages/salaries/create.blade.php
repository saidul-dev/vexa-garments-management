@extends('layouts.master')

@section('title')
    {{ __('Pay Salary') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="tab-content order-summary-tab">
                <div class="tab-pane fade show active" id="add-new-supplier">
                    <div class="table-header mt-lg-4">
                        <h4>{{ __('Pay Salary') }}</h4>
                        @can('salaries-read')
                            <a href="{{ route('salaries.index') }}" class="add-order-btn rounded-2">
                                <i class="fas fa-credit-card me-1"></i>
                                {{ __('Salaries List') }}
                            </a>
                        @endcan
                    </div>
                    <div class="order-form-section">
                        <form action="{{ route('salaries.store') }}" method="post" enctype="multipart/form-data" class="ajaxform">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6 mt-1">
                                    <label>{{__('Select Employee')}}</label>
                                    <select name="employee_id" required class="form-control table-select w-100 employee_id">
                                        <option value="">{{__('Select Employee')}}</option>
                                        @foreach($employees as $employee)
                                            <option data-salary="{{ $employee->salary }}" value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label>{{ __('Salary Amount') }}</label>
                                    <input type="number" step="any" name="amount" class="form-control amount" placeholder="Enter Salary">
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label>{{__('Salary Year')}}</label>
                                    <select name="year" class="form-control table-select w-100">
                                        @for ($i = 2000; $i < date('Y')+1; $i++)
                                            <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label>{{ __('Salary Month') }}</label>
                                    <select name="month" class="form-control table-select w-100">
                                        <option value="">{{__('Select month') }}</option>
                                        <option @selected(date('F') == 'January') value="January">{{ __('January') }}</option>
                                        <option @selected(date('F') == 'February') value="February">{{ __('February') }}</option>
                                        <option @selected(date('F') == 'March') value="March">{{ __('March') }}</option>
                                        <option @selected(date('F') == 'April') value="April">{{ __('April') }}</option>
                                        <option @selected(date('F') == 'May') value="May">{{ __('May') }}</option>
                                        <option @selected(date('F') == 'June') value="June">{{ __('June') }}</option>
                                        <option @selected(date('F') == 'July') value="July">{{ __('July') }}</option>
                                        <option @selected(date('F') == 'August') value="August">{{ __('August') }}</option>
                                        <option @selected(date('F') == 'September') value="September">{{ __('September') }}</option>
                                        <option @selected(date('F') == 'October') value="October">{{ __('October') }}</option>
                                        <option @selected(date('F') == 'November') value="November">{{ __('November') }} </option>
                                        <option @selected(date('F') == 'December') value="December">{{ __('December') }} </option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label class="table-select-lebel">{{__('Payment method')}}</label>
                                    <div class="input-group">
                                        <select name="payment_method" class="form-control credit_payment_method remove-bg-table-select" required>
                                            <option value="">{{__('Select Payment Method')}}</option>
                                            <option value="cash">{{__('Cash')}}</option>
                                            <option value="bank">{{__('Bank')}}</option>
                                            <option value="cheque">{{__('Cheque')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-2 bank_cheque_input d-none">
                                    <label>{{ __('Select Bank') }}</label>
                                    <select name="bank_id" class="form-control w-100">
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
                                <div class="col-sm-4 mt-2 cheque_input d-none">
                                    <label>{{ __('Issue Date') }}</label>
                                    <input type="date" name="issue_date" value="{{ now()->format('Y-m-d') }}"  class="form-control datepicker date" required>
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label>{{__('Notes')}}</label>
                                    <input type="text" step="any" name="notes" class="form-control" placeholder="Enter Note">
                                </div>

                                <div class="col-lg-12">
                                    <div class="button-group text-center mt-3">
                                        <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                        <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
