@extends('layouts.master')

@section('title')
{{ __('Edit Accessory') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="cards">
            <div class="table-header">
                <h4 class="mt-2">{{__('Edit Accessory')}}</h4>
            </div>
            <div class="order-form-section">
                <form action="{{ route('accessories.update',$accessory->id) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Accessory Name')}}</label>
                            <input type="text" name="name" value="{{ $accessory->name }}" required class="form-control" placeholder="Accessory Name">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label class="table-select-lebel">{{__('Unit Name')}}</label>
                            <div class="input-group">
                                <select name="unit_id" required="required" class="form-control order_id table-select remove-bg-table-select">
                                    <option value="">{{__('Select a Unit')}}</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" @selected($unit->id == $accessory->unit_id)>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <a href="{{ route('units.index') }}" class="modal-btn-ctg input-group-text"><i class="fal fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Unit Price')}}</label>
                            <input type="number" step="any" name="unit_price" value="{{ $accessory->unit_price }}" required class="form-control amount" placeholder="Enter unit price">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Description')}}</label>
                            <textarea name="description" class="form-control" placeholder="Description">{{ $accessory->description }}</textarea>
                        </div>
                        <div class="col-lg-12">
                            <div class="button-group text-center mt-5">
                                <a href="{{ route('accessories.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
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

@push('js')
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
