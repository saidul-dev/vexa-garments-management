@extends('layouts.master')

@section('title')
    {{__('Shipment')}}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="table-header">
                <h4>{{__('Edit Shipment')}}</h4>
            </div>
            <div class="order-form-section">
                <form action="{{ route('shipments.update', $shipment->id) }}" method="post" class="ajaxform_instant_reload">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Order')}}</label>
                            <select name="order_id" required="required"  class="form-control order-id table-select w-100 order-id">
                                <option value="">{{__('Select a Order')}}</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" @selected($order->id == $shipment->order_id)>{{ $order->order_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mt-2 party-name">
                            <label>{{__('Party Name')}}</label>
                            <input type="text" readonly class="form-control" value="{{ $shipment->order->party->name ?? '' }}" id="shipment_party_name" placeholder="Party Name">
                        </div>

                        @foreach($shipment->details as $detail)
                            <div class="col-lg-12 feature-row duplicate-feature2 sample-form-wrp">
                                <button type="button" class="btn btn-secondary service-btn-possition add-more-feature-2">+</button>
                                <button type="button" class="btn text-danger trash remove-btn-features" disabled><i class="fas fa-trash"></i></button>
                                <div class="grid-3">
                                    <div class="grid-items mt-2">
                                        <label>Style</label>
                                        <input type="text" name="styles[]" required value="{{ $detail->style }}" class="form-control clear-input" placeholder="Enter Style">
                                    </div>
                                    <div class="grid-items mt-2">
                                        <label>Color</label>
                                        <input type="text" name="colors[]" value="{{ $detail->color }}" class="form-control clear-input" placeholder="Enter Color">
                                    </div>
                                    <div class="grid-items mt-2">
                                        <label>Item</label>
                                        <input type="text" name="items[]" required value="{{ $detail->item }}" class="form-control clear-input" placeholder="Enter Item">
                                    </div>
                                    <div class="grid-items mt-2">
                                        <label>Shipment</label>
                                        <input type="date" name="dates[]" required value="{{ $detail->shipment_date }}" class="form-control clear-input">
                                    </div>
                                    <div class="grid-items mt-2">
                                        <label>Size</label>
                                        <input type="text" name="sizes[]" value="{{ $detail->size }}" class="form-control clear-input" placeholder="Enter Size">
                                    </div>
                                    <div class="grid-items mt-2">
                                        <label>Garments Qty</label>
                                        <input type="number" name="qts[]" required value="{{ $detail->qty }}" class="form-control clear-input" placeholder="Enter Garments Qty">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-lg-12">
                            <div class="button-group text-center mt-5">
                                <a href="{{ route('shipments.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                                <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="url" data-model="Shipment" value="{{ route('shipment.get-order') }}">
@endsection

@push('js')
<script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
