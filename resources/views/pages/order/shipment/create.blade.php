<div class="table-header">
    <h4>{{__('Add New Shipment')}}</h4>
</div>
<div class="order-form-section">
    <form action="{{ route('shipments.store') }}" method="post" class="ajaxform_instant_reload">
        @csrf
        <div class="row">
            <div class="col-lg-6 mt-2">
                <label>{{__('Order')}}</label>
                <select name="order_id" required="required"  class="form-control class table-select w-100 order-id">
                    <option value="">{{__('Select a Order')}}</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}">{{ $order->order_no  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6 mt-2 party-name">
                <label>{{__('Party Name')}}</label>
                <input type="text" readonly class="form-control" id="shipment_party_name" placeholder="Party Name">
            </div>

            {{-- Add from js --}}

            <div class="col-lg-12">
                <div class="button-group text-center mt-5">
                    <a href="{{ route('shipments.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                    <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>

<input type="hidden" id="cost-price">
<input type="hidden" id="url" data-model="Shipment" value="{{ route('shipment.get-order') }}">

@push('js')
<script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
