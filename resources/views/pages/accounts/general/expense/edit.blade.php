@extends('layouts.master')

@section('title')
    Edit Expense
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="table-header">
                <h4>{{__('Edit Expense')}}</h4>
            </div>
            <div class="order-form-section col-xxl-6 col-xl-6 col-lg-6">
                {{-- form start --}}
                <form action="{{ route('expense.update',$expense->id) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 mt-2">
                            <label>{{__('Category Name')}}</label>
                            <input type="text" name="category_name" value="{{ $expense->category_name }}" required class="form-control" placeholder="Category Name">
                        </div>
                        <div class="col-lg-12 mt-2">
                            <label>{{__('Description')}}</label>
                            <textarea name="expense_description" class="form-control" placeholder="Description">{{ $expense->expense_description }}</textarea>
                        </div>
                        <div class="col-lg-12">
                            <div class="button-group text-center mt-5">
                                <a href="{{ route('expense.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                                <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- form end --}}
            </div>
        </div>
    </div>
@endsection


