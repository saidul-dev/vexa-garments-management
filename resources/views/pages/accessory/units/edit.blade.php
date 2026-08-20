@extends('layouts.master')

@section('title')
    {{ __('Edit Unit') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="cards">
            <div class="table-header">
                <h4 class="mt-2">{{ __('Edit Unit') }}</h4>
            </div>
            <div class="order-form-section">
                <form action="{{ route('units.update', $unit->id) }}" method="post" enctype="multipart/form-data"
                      class="ajaxform_instant_reload">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Unit Name')}}</label>
                            <input type="text" name="name" value="{{ $unit->name }}" required class="form-control" placeholder="Unit Name">
                        </div>
                        <div class="col-lg-12">
                            <div class="button-group mt-5 text-center">
                                <a href="{{ route('units.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                                <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
@endsection
