<div class="table-header">
    <h4>{{__('Costing Form')}}</h4>
</div>
<form action="{{ route('costings.store') }}" method="post" enctype="multipart/form-data" class="table-form-section ajaxform_instant_reload">
    @csrf
    <div class="col-4 mt-30">
        <table class="table table-bordered small-table clr-black form-table-sm budget-form">
            <tr>
                <label>{{ __("Select a order") }}</label>
                <select name="order_id" id="order" required class="order-id table-select form-control select-tow mb-30">
                    <option value="">{{__('Select a Order')}}</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}">{{ $order->order_no }}</option>
                    @endforeach
                </select>
            </tr>
            <tr>
                <select name="order_info[style]" required="required" class="form-control costing-style select-tow mb-30 w-100 style-dropdown-container">
                    <option value="">{{__('Style (Select Order First)')}}</option>
                    {{-- Add dropdown options dynamically from JavaScript --}}
                </select>
            </tr>
            <tr>
                <td>{{__('Party Name:')}}</td>
                <td><input type="text" id="party_name" readonly class="form-control" placeholder="Party Name"></td>
            </tr>
            <tr>
                <td>{{__('Type:')}}</td>
                <td><input type="text" id="party_type" readonly class="form-control" placeholder="Party Type"></td>
            </tr>
            <tr>
                <td>{{__('Order No')}}</td>
                <td><input type="text" id="order_no" readonly class="form-control datepicker" placeholder="Order No."></td>
            </tr>
            <tr>
                <td>{{__('Fabrication')}}</td>
                <td><input type="text" id="fabrication" readonly class="form-control" placeholder="Fabrication"></td>
            </tr>
            <tr class="all-hide">
                <td>{{__('Shipment Date')}}</td>
                <td><input type="text" name="order_info[shipment_date]" id="shipment_date" readonly class="form-control datepicker" placeholder="Shipment Date"></td>
            </tr>
            <tr>
                <td>{{__('Total Order Qty')}}</td>
                <td><input type="number" name="order_info[qty]" id="qty" readonly class="form-control"></td>
            </tr>
            <tr>
                <td >{{__('Unit Price')}}</td>
                <td><input type="number" name="order_info[unit_price]" id="unit_price" readonly class="form-control"></td>
            </tr>
            <tr>
                <td>{{__('Total L/C Value')}}</td>
                <td><input type="number" name="order_info[lc]" id="lc" readonly class="form-control"></td>
            </tr>
        </table>
    </div>
    <div class="responsive-table budget-form">
        <table class="table table-bordered" >
            <thead>
            <tr>
                <th>{{__('items details')}}</th>
                <th>{{__('yarn composition & count')}}</th>
                <th>{{__('type')}}</th>
                <th>{{__('qty(KGS)')}}</th>
                <th>{{__('unit')}}</th>
                <th>{{__('price(KGS)')}}</th>
                <th>{{__('total')}}</th>
                <th>{{__('G. total')}}</th>
                <th>{{__('Payment')}}</th>
                <th>{{__('Remarks')}}</th>
            </tr>
            </thead>
            <tbody>
            <tr class="position-relative">
                <td><div class="add-btn-one"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                <td><div class="tr-remove-btn remove-one"><i class="fas fa-trash"></i></div></td>
            </tr>
            <tr class="duplicate-one">
                <td><input type="text" name="yarn_desc[items][]" class="form-control clear-input" placeholder="Yarn"></td>
                <td><input type="text" name="yarn_desc[composition][]" class="form-control clear-input" placeholder="Composition"></td>
                <td><input type="text" name="yarn_desc[type][]" class="form-control clear-input" placeholder="Type"></td>
                <td><input type="number" name="yarn_desc[qty][]" step="any" data-length="0" class="form-control count-total yarn-qty 0" placeholder="0"></td>
                <td><input type="text" name="yarn_desc[unit][]" class="form-control clear-input" placeholder="Unit"></td>
                <td><input type="number" name="yarn_desc[price][]" step="any" data-length="0" class="form-control count-total yarn-price 0" placeholder="0"></td>
                <td><input type="number" name="yarn_desc[total][]" step="any" value="0" data-length="0" readonly class="form-control yarn-total 0" placeholder="Total"></td>
                <td rowspan="1"><input type="number" name="yarn_desc[grand_total][]" step="any" readonly class="form-control yarn-grand-total"></td>
                <td><input type="text" name="yarn_desc[payment][]" class="form-control clear-input" placeholder="Payment"></td>
                <td><input type="text" name="yarn_desc[remarks][]" class="form-control clear-input" placeholder="Remarks"></td>
            </tr>
            <tr>
                <td colspan="7" class="text-start"><b>{{__('Yarn')}}</b></td>
                <td><input type="number" readonly name="yarn_total" id="yarn_total" value="0" class="form-control fw-semibold text-center"></td>
                <td colspan="2"></td>
            </tr>

            <tr class="position-relative">
                <td><div class="add-btn-two"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                <td><div class="tr-remove-btn remove-two"><i class="fas fa-trash"></i></div></td>
            </tr>
            <tr class="duplicate-two">
                <td><input type="text" name="knitting_desc[items][]" class="form-control clear-input" placeholder="Knitting Type"></td>
                <td><input type="text" name="knitting_desc[composition][]" class="form-control clear-input" placeholder="Composition"></td>
                <td><input type="text" name="knitting_desc[type][]" class="form-control clear-input" placeholder="Type"></td>
                <td><input type="number" name="knitting_desc[qty][]" step="any" data-length="0" class="form-control count-total knitting-qty 0" placeholder="0"></td>
                <td><input type="text" name="knitting_desc[unit][]" class="form-control clear-input" placeholder="Unit"></td>
                <td><input type="number" name="knitting_desc[price][]" step="any" data-length="0" class="form-control count-total knitting-price 0" placeholder="0"></td>
                <td><input type="number" name="knitting_desc[total][]" step="any" value="0" data-length="0" readonly class="form-control knitting-total 0" placeholder="Total"></td>
                <td><input type="number" name="knitting_desc[grand_total][]" step="any" readonly class="form-control"></td>
                <td><input type="text" name="knitting_desc[payment][]" class="form-control clear-input" placeholder="Payment"></td>
                <td><input type="text" name="knitting_desc[remarks][]" class="form-control clear-input" placeholder="Remarks"></td>
            </tr>
            <tr>
                <td colspan="7" class="text-start"><b>{{__('Knitting Type')}}</b></td>
                <td><input type="number" readonly name="knitting_total" id="knitting_total" value="0" class="form-control fw-semibold text-center"></td>
                <td colspan="2"></td>
            </tr>

            <tr class="position-relative">
                <td><div class="add-btn-three"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                <td><div class="tr-remove-btn remove-three"><i class="fas fa-trash"></i></div></td>
            </tr>
            <tr class="duplicate-three">
                <td><input type="text" name="dyeing_desc[items][]" class="form-control clear-input" placeholder="Dyeing & Finishing"></td>
                <td><input type="text" name="dyeing_desc[composition][]" class="form-control clear-input" placeholder="Composition"></td>
                <td><input type="text" name="dyeing_desc[type][]" class="form-control clear-input" placeholder="Type"></td>
                <td><input type="number" step="any" name="dyeing_desc[qty][]" data-length="0" class="form-control count-total dyeing-qty 0" placeholder="0"></td>
                <td><input type="text" name="dyeing_desc[unit][]" class="form-contro clear-input" placeholder="Unit"></td>
                <td><input type="number" step="any" name="dyeing_desc[price][]" data-length="0" class="form-control count-total dyeing-price 0" placeholder="0"></td>
                <td><input type="number" step="any" name="dyeing_desc[total][]" value="0" data-length="0" readonly class="form-control dyeing-total 0" placeholder="Total"></td>
                <td><input type="number" step="any" name="dyeing_desc[grand_total][]" readonly class="form-control"></td>
                <td><input type="text" name="dyeing_desc[payment][]" class="form-control clear-input" placeholder="Payment"></td>
                <td><input type="text" name="dyeing_desc[remarks][]" class="form-control clear-input" placeholder="Remarks"></td>
            </tr>
            <tr>
                <td colspan="7" class="text-start"><b>{{__('Dyeing & Finishing')}}</b></td>
                <td><input type="number" readonly name="dyeing_total" id="dyeing_total" value="0" class="form-control fw-semibold text-center"></td>
                <td colspan="2"></td>
            </tr>

            <tr class="position-relative">
                <td><div class="add-btn-four"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                <td><div class="tr-remove-btn remove-four"><i class="fas fa-trash"></i></div></td>
            </tr>
            <tr class="duplicate-four">
                <td><input type="text" name="print_desc[items][]" class="form-control clear-input" placeholder="Print/Embo"></td>
                <td><input type="text" name="print_desc[composition][]" class="form-control clear-input" placeholder="Composition"></td>
                <td><input type="text" name="print_desc[type][]" class="form-control clear-input" placeholder="Type"></td>
                <td><input type="number" step="any" name="print_desc[qty][]" data-length="0" class="form-control count-total print-qty 0" placeholder="0"></td>
                <td><input type="text" name="print_desc[unit][]" class="form-control clear-input" placeholder="Unit"></td>
                <td><input type="number" step="any" name="print_desc[price][]" data-length="0" class="form-control count-total print-price 0" placeholder="0"></td>
                <td><input type="number" step="any" name="print_desc[total][]" value="0" data-length="0" readonly class="form-control print-total 0" placeholder="Total"></td>
                <td><input type="number" step="any" name="print_desc[grand_total][]" readonly class="form-control"></td>
                <td><input type="text" name="print_desc[payment][]" class="form-control clear-input" placeholder="Payment"></td>
                <td><input type="text" name="print_desc[remarks][]" class="form-control clear-input" placeholder="Remarks"></td>
            </tr>
            <tr>
                <td colspan="7" class="text-start"><b>{{__('Print/Embo')}}</b></td>
                <td><input type="number" readonly name="print_total" id="print_total" value="0" class="form-control fw-semibold text-center"></td>
                <td colspan="2"></td>
            </tr>

            <tr class="position-relative">
                <td><div class="add-btn-five"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                <td><div class="tr-remove-btn remove-five"><i class="fas fa-trash"></i></div></td>
            </tr>
            <tr class="duplicate-five">
                <td><input type="text" name="trim_desc[items][]" class="form-control clear-input" placeholder="Trims & Accessories"></td>
                <td><input type="text" name="trim_desc[composition][]" class="form-control clear-input" placeholder="Composition"></td>
                <td><input type="text" name="trim_desc[type][]" class="form-control clear-input" placeholder="Type"></td>
                <td><input type="number" step="any" name="trim_desc[qty][]" data-length="0" class="form-control count-total trim-qty 0" placeholder="0"></td>
                <td><input type="text" name="trim_desc[unit][]" class="form-control clear-input" placeholder="Unit"></td>
                <td><input type="number" step="any" name="trim_desc[price][]" data-length="0" class="form-control count-total trim-price 0" placeholder="0"></td>
                <td><input type="number" step="any" name="trim_desc[total][]" value="0" data-length="0" readonly class="form-control trim-total 0" placeholder="Total"></td>
                <td><input type="number" step="any" name="trim_desc[grand_total][]" readonly class="form-control"></td>
                <td><input type="text" name="trim_desc[payment][]" class="form-control clear-input" placeholder="Payment"></td>
                <td><input type="text" name="trim_desc[remarks][]" class="form-control clear-input" placeholder="Remarks"></td>
            </tr>
            <tr>
                <td class="text-start"><b>{{__('Trims & Accessories')}}</b></td>
                <td colspan="6"></td>
                <td><input type="number" readonly name="trim_total" id="trim_total" value="0" class="form-control fw-semibold text-center"></td>
                <td colspan="2"></td>
            </tr>

            <tr>
                <td class="text-start"><b>{{__('Commercial Cost')}}</b></td>
                <td colspan="2"></td>
                <td><input type="number" step="any" name="commercial_qty" class="form-control commercial-total commercial-qty" placeholder="0"></td>
                <td><input type="text" name="commercial_unit" class="form-control" placeholder="Unit"></td>
                <td><input type="number" step="any" name="commercial_price" class="form-control commercial-total commercial-price" placeholder="0"></td>
                <td><input type="number" step="any" name="commercial_total" id="commercial_total" value="0" readonly class="form-control" placeholder="Total"></td>
                <td><input type="number" readonly value="0" class="form-control commercial-grand-total fw-semibold text-center"></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="text-start"><b>{{__('CM Cost/DZ.')}}</b></td>
                <td colspan="2"><input type="text" name="cm_cost_composition" class="form-control" placeholder="Long Pant-1 Styles"></td>
                <td><input type="number" step="any" name="cm_cost_qty" class="form-control cm-total cm-qty" placeholder="0"></td>
                <td><input type="text" name="cm_cost_unit" class="form-control" placeholder="Unit"></td>
                <td><input type="number" step="any" name="cm_cost_price" class="form-control cm-total cm-price" placeholder="0"></td>
                <td><input type="number" step="any" name="cm_cost_total" id="cm_cost_total" value="0" readonly class="form-control" placeholder="Total"></td>
                <td><input type="number" readonly value="0" class="form-control cm-grand-total fw-semibold text-center"></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="7"></td>
                <td><input type="number" readonly name="grand_total" id="grand_total" value="0" class="form-control fw-semibold text-center"></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="7" class="text-end text-uppercase"><b>{{__('Total Costing Per DZ Fob')}}</b></td>
                <td><input type="number" readonly name="grand_total_in_dzn" id="grand_total_in_dzn" value="0" class="form-control fw-semibold text-center"></td>
                <td colspan="2"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="button-group text-center mt-5">
        <button type="reset" class="theme-btn border-btn m-2">{{__('Reset')}}</button>
        <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
    </div>
</form>
<input type="hidden" id="url" data-model="Costing" value="{{ route('costing.order') }}">

