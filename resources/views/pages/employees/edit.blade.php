@extends('layouts.master')

@section('title')
    {{__('Edit Employee')}}
@endsection

@section('main_content')
    <div class="erp-table-section employees-module">
        <div class="container-fluid">
            <div class="tab-content order-summary-tab">
                <div class="tab-pane fade show active" id="add-new-supplier">
                    <div class="table-header mt-lg-4">
                        <h4>{{ __('Edit Employee') }}</h4>
                        @can('employees-read')
                            <a href="{{ route('employees.index') }}" class="add-order-btn rounded-2"><i class="fas fa-plus-circle"></i> {{__('Employee List')}} </a>
                        @endcan
                    </div>
                    <div class="order-form-section">
                        <form action="{{ route('employees.update', $employee->id) }}" method="post" enctype="multipart/form-data" class="ajaxform">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('Name')}}</label>
                                    <input type="text" name="name" value="{{ $employee->name }}" class="form-control" placeholder="Enter Name">
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('Phone Number')}}</label>
                                    <input type="text" name="phone" value="{{ $employee->phone }}" class="form-control" placeholder="Enter Phone Number">
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('Email')}}</label>
                                    <input type="email" name="email" value="{{ $employee->email }}" class="form-control" placeholder="Enter Email">
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('Address')}}</label>
                                    <input type="text" name="address" value="{{ $employee->address }}" class="form-control" placeholder="Enter Address">
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('Gender')}}</label>
                                    <select name="gender" class="form-control table-select w-100">
                                        <option value="">{{__('Select Gender')}}</option>
                                        <option value="male" @selected($employee->gender == 'male')>{{__('Male')}}</option>
                                        <option value="female" @selected($employee->gender == 'female')>{{__('Female')}}</option>
                                        <option value="other" @selected($employee->gender == 'other')>{{__('Other')}}</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('Employment Type')}}</label>
                                    <select name="employee_type" class="form-control table-select w-100">
                                        <option value="">{{__('Select Employee Type')}}</option>
                                        <option value="part_time" @selected($employee->employee_type == 'part_time')>{{__('Part time')}}</option>
                                        <option value="full_time" @selected($employee->employee_type == 'full_time')>{{__('Full time')}}</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('Birth Date')}}</label>
                                    <input type="date" name="birth_date" value="{{ $employee->birth_date }}" class="form-control">
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('Joining Date')}}</label>
                                    <input type="date" name="join_date" value="{{ $employee->join_date }}" class="form-control">
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('Designation')}}</label>
                                    <select name="designation_id" required class="form-control table-select w-100">
                                        <option value="">{{__('Select Designation')}}</option>
                                        @foreach($designations as $designation)
                                            <option value="{{ $designation->id }}" @selected($employee->designation_id == $designation->id)>{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('Salary')}}</label>
                                    <input type="number" step="any" name="salary" value="{{ $employee->salary }}" class="form-control" placeholder="Enter Salary">
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{ __('NID / Passport') }}</label>
                                    <input type="file" accept="image/*" name="nid_front" class="form-control" placeholder="Enter Salary">
                                </div>
                                <div class="col-lg-4 mt-1">
                                    <label>{{__('NID / Passport back (Optional)')}}</label>
                                    <input type="file" accept="image/*" name="nid_back" class="form-control" placeholder="Enter Salary">
                                </div>

                                <div class="col-lg-12">
                                    <div class="button-group text-center mt-3">
                                        <button type="reset" class="theme-btn border-btn m-2">{{__('Reset')}}</button>
                                        <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
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

