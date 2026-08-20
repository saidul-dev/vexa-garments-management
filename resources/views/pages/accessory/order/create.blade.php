<div class="table-header">
    <h4>{{__('Create Accessories Order')}}</h4>
</div>
<div class="order-form-section">
    {{-- form start --}}
    <form action="{{ route('accessory-orders.store') }}" method="post" enctype="multipart/form-data"  class="ajaxform_instant_reload">
        @csrf
        <div class="row">
            <div class="col-lg-6 mt-2">
                <label>{{__('Accessories Name')}}</label>
                <select name="accessory_id" required class="form-control order_id table-select w-100 unit-price">
                    <option value="">{{__('Select a Accessory')}}</option>
                    @foreach($accessories as $accessory)
                        <option data-unit_price="{{ $accessory->unit_price }}" data-unit_name= "{{ $accessory->unit_name }}" value="{{ $accessory->id }}">{{ $accessory->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('Party Name')}}</label>
                <div class="add-suplier-wrapper">
                    <select name="party_id" required class="form-control table-select w-100">
                        <option value="">{{__('Select a Party')}}</option>
                        @foreach($parties  as $party)
                            <option value="{{ $party->id }}">{{ $party->name }} ({{ optional($party->user)->phone }})</option>
                        @endforeach
                    </select>
                    <a href="{{ route('parties.create') }}" class="add-suplier-modal"><i class="fal fa-plus"></i></a>
                </div>
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('Invoice No')}}</label>
                <input type="text" name="invoice_no" value="{{ $invoice_no }}" required class="form-control" placeholder="Enter Invoice No">
            </div>
            <div class="col-lg-6 mt-2">
                <label class="unit-name">{{__('Quantity')}}</label>
                <input type="number" name="qty_unit" id="qtyUnit" required class="form-control" placeholder="Enter Quantity">
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('Unit Price')}}</label>
                <input type="number" step="any" name="unit_price" id="unitPrice" readonly required class="form-control" placeholder="Unit Price">
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('TTL Amount')}}</label>
                <input type="number" readonly name="ttl_amount" id="ttlAmount" required class="form-control amount" placeholder="0">
            </div>
            <div class="col-lg-12 text-center">
                <div class="button-group text-center mt-5">
                    <a href="{{ route('accessory-orders.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                    <button type="submit" class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                </div>
            </div>
        </div>
    </form>
    {{-- form end --}}
</div>
