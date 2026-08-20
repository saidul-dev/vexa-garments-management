@extends('layouts.master')

@section('title')
    {{ __('Pay Salary Update') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="tab-content order-summary-tab">
                <div class="tab-pane fade show active" id="add-new-supplier">
                    <div class="table-header mt-lg-4">
                        <h4>{{ __('Pay Salary Update') }}</h4>
                        @can('employees-list')
                            <a href="{{ route('salaries.index') }}" class="add-order-btn rounded-2">
                                <i class="fas fa-credit-card me-1"></i>
                                {{ __('Salaries List') }}
                            </a>
                        @endcan
                    </div>
                    <div class="order-form-section">
                        <form action="{{ route('salaries.update', $salary->id) }}" method="post" enctype="multipart/form-data" class="ajaxform">
                            @csrf
                            @method('put')

                            <div class="row">
                                <div class="col-lg-6 mt-1">
                                    <label>{{__('Select Employee')}}</label>
                                    <select name="employee_id" required class="form-control table-select w-100 employee_id">
                                        <option value="">{{__('Select Employee')}}</option>
                                        @foreach($employees as $employee)
                                            <option @selected($salary->employee_id == $employee->id) data-salary="{{ $employee->salary }}" value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label>{{__('Salary Amount')}}</label>
                                    <input type="number" step="any" name="amount" value="{{ $salary->amount }}" class="form-control amount" placeholder="Enter Salary">
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label>{{__('Salary Year')}}</label>
                                    <select name="year" class="form-control table-select w-100">
                                        @for ($i = 2000; $i < date('Y')+1; $i++)
                                            <option @selected($i == $salary->year) value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label>{{ __('Salary Month') }}</label>
                                    <select name="month" class="form-control table-select w-100">
                                        <option value="">{{__('Select month') }}</option>
                                        <option @selected($salary->month == 'January') value="January">{{ __('January') }}</option>
                                        <option @selected($salary->month == 'February') value="February">{{ __('February') }}</option>
                                        <option @selected($salary->month == 'March') value="March">{{ __('March') }}</option>
                                        <option @selected($salary->month == 'April') value="April">{{ __('April') }}</option>
                                        <option @selected($salary->month == 'May') value="May">{{ __('May') }}</option>
                                        <option @selected($salary->month == 'June') value="June">{{ __('June') }}</option>
                                        <option @selected($salary->month == 'July') value="July">{{ __('July') }}</option>
                                        <option @selected($salary->month == 'August') value="August">{{ __('August') }}</option>
                                        <option @selected($salary->month == 'September') value="September">{{ __('September') }}</option>
                                        <option @selected($salary->month == 'October') value="October">{{ __('October') }}</option>
                                        <option @selected($salary->month == 'November') value="November">{{ __('November') }} </option>
                                        <option @selected($salary->month == 'December') value="December">{{ __('December') }} </option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label class="table-select-lebel">{{__('Payment method')}}</label>
                                    <div class="input-group">
                                        <select name="payment_method" class="form-control credit_payment_method remove-bg-table-select" required>
                                            <option value="">{{__('Select Payment Method')}}</option>
                                            <option @selected($salary->payment_method == 'cash') value="cash">{{__('Cash')}}</option>
                                            <option @selected($salary->payment_method == 'bank') value="bank">{{__('Bank')}}</option>
                                            <option @selected($salary->payment_method == 'cheque') value="cheque">{{__('Cheque')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-2 bank_cheque_input {{ in_array($salary->payment_method, ['bank', 'cheque']) ? '' : 'd-none' }}">
                                    <label>{{ __('Select Bank') }}</label>
                                    <select name="bank_id" class="form-control w-100">
                                        <option value="">-{{ __('Select Bank') }}-</option>
                                        @foreach($banks as $bank)
                                            <option @selected($salary->bank_id == $bank->id) value="{{ $bank->id }}" >{{ $bank->bank_name .' - '. $bank->account_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mt-2 cheque_input {{ $salary->payment_method == 'cheque' ? '' : 'd-none' }}"">
                                    <label>{{__('Cheque No')}}</label>
                                    <input type="number" name="cheque_no" value="{{ $salary->meta['issue_date'] ?? '' }}" class="form-control" placeholder="0202982883" required>
                                </div>
                                <div class="col-sm-4 mt-2 cheque_input {{ $salary->payment_method == 'cheque' ? '' : 'd-none' }}"">
                                    <label>{{ __('Issue Date') }}</label>
                                    <input type="date" name="issue_date" value="{{ Carbon\Carbon::parse($salary->meta['issue_date'] ?? NULL)->format('Y-m-d') }}"  class="form-control datepicker date" required>
                                </div>
                                <div class="col-lg-6 mt-1">
                                    <label>{{__('Notes')}}</label>
                                    <input type="text" step="any" name="notes" value="{{ $salary->notes }}" class="form-control" placeholder="Enter Note">
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