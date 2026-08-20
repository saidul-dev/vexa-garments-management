@extends('layouts.master')

@section('main_content')
    <div class="container-fluid">
        <div class="table-header">
            <h4>{{__('Edit Costing Form')}}</h4>
        </div>
        <form action="{{ route('costings.update',$costing->id) }}" method="post" enctype="multipart/form-data" class="table-form-section ajaxform_instant_reload">
            @csrf
            @method('put')
            <div class="col-4 mt-30">
                <table class="table table-bordered small-table clr-black form-table-sm budget-form">
                    <tr>
                        <select name="order_id" id="order" required class="order-id table-select form-control select-tow mb-30">
                            <option value="">{{__('Select a Order')}}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected($order->id == $costing->order_id)>{{ $order->order_no }}</option>
                            @endforeach
                        </select>
                    </tr>
                    <tr>
                        <select name="order_info[style]" required="required" class="form-control costing-style select-tow mb-30 w-100 style-dropdown-container">
                            <option value="{{ $costing->order_info['style'] ?? '' }}">{{ $costing->order_info['style'] ?? '' }}</option>
                            {{-- Add dropdown options dynamically from JavaScript --}}
                        </select>
                    </tr>
                    <tr>
                        <td>{{__('Party Name:')}}</td>
                        <td><input type="text" id="party_name" value="{{ $costing->order->party->name ?? '' }}" readonly class="form-control" placeholder="Party Name"></td>
                    </tr>
                    <tr>
                        <td>{{__('Type:')}}</td>
                        <td><input type="text" id="party_type" value="{{ $costing->order->party->type ?? '' }}" readonly class="form-control" placeholder="Party Type"></td>
                    </tr>
                    <tr>
                        <td>{{__('Order No')}}</td>
                        <td><input type="text" id="order_no" value="{{ $costing->order->order_no ?? '' }}" readonly class="form-control datepicker" placeholder="Order No."></td>
                    </tr>
                    <tr>
                        <td>{{__('Fabrication')}}</td>
                        <td><input type="text" id="fabrication" value="{{ $costing->order->fabrication ?? '' }}" readonly class="form-control" placeholder="Fabrication"></td>
                    </tr>
                    <tr class="all-hide">
                        <td>{{__('Shipment Date')}}</td>
                        <td><input type="text" name="order_info[shipment_date]" id="shipment_date" value="{{ $costing->order_info['shipment_date'] ?? '' }}" readonly class="form-control datepicker" placeholder="Shipment Date"></td>
                    </tr>
                    <tr>
                        <td>{{__('Total Order Qty')}}</td>
                        <td><input type="number" name="order_info[qty]" id="qty" value="{{ $costing->order_info['qty'] ?? '' }}" readonly class="form-control"></td>
                    </tr>
                    <tr>
                        <td >{{__('Total Unit Price')}}</td>
                        <td><input type="number" name="order_info[unit_price]" id="unit_price" value="{{ $costing->order_info['unit_price'] ?? '' }}" readonly class="form-control"></td>
                    </tr>
                    <tr>
                        <td>{{__('Total L/C Value')}}</td>
                        <td><input type="number" name="order_info[lc]" id="lc" value="{{ $costing->order_info['lc'] ?? '' }}" readonly class="form-control"></td>
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
                    @foreach($costing->yarn_desc['items'] ?? [] as $key=>$items)
                        <tr class="duplicate-one">
                        <td><input type="text" name="yarn_desc[items][]" value="{{ $items ?? '' }}" class="form-control clear-input" placeholder="Items Details"></td>
                        <td><input type="text" name="yarn_desc[composition][]" value="{{ $costing->yarn_desc['composition'][$key] ?? '' }}" class="form-control clear-input" placeholder="Composition"></td>
                        <td><input type="text" name="yarn_desc[type][]" value="{{ $costing->yarn_desc['type'][$key] ?? '' }}" class="form-control clear-input" placeholder="Type"></td>
                        <td><input type="number" name="yarn_desc[qty][]" step="any" value="{{ $costing->yarn_desc['qty'][$key] ?? '' }}" data-length="0" class="form-control count-total yarn-qty 0" placeholder="0"></td>
                        <td><input type="text" name="yarn_desc[unit][]" value="{{ $costing->yarn_desc['unit'][$key] ?? '' }}" class="form-control clear-input" placeholder="Unit"></td>
                        <td><input type="number" name="yarn_desc[price][]" step="any" value="{{ $costing->yarn_desc['price'][$key] ?? '' }}" data-length="0" class="form-control count-total yarn-price 0" placeholder="0"></td>
                        <td><input type="number" name="yarn_desc[total][]" step="any" value="{{ $costing->yarn_desc['total'][$key] ?? '' }}" data-length="0" readonly class="form-control yarn-total 0" placeholder="Total"></td>
                        <td rowspan="1"><input type="number" name="yarn_desc[grand_total][]" value="{{ $costing->yarn_desc['grand_total'][$key] ?? '' }}" step="any" readonly class="form-control yarn-grand-total"></td>
                        <td><input type="text" name="yarn_desc[payment][]" value="{{ $costing->yarn_desc['payment'][$key] ?? '' }}" class="form-control clear-input" placeholder="Payment"></td>
                        <td><input type="text" name="yarn_desc[remarks][]" value="{{ $costing->yarn_desc['remarks'][$key] ?? '' }}" class="form-control clear-input" placeholder="Remarks"></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" class="text-start"><b>{{__('Yarn')}}</b></td>
                        <td><input type="number" readonly name="yarn_total" id="yarn_total" value="{{ $costing->yarn_total }}" class="form-control fw-semibold text-center"></td>
                        <td colspan="2"></td>
                    </tr>

                    <tr class="position-relative">
                        <td><div class="add-btn-two"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                        <td><div class="tr-remove-btn remove-two"><i class="fas fa-trash"></i></div></td>
                    </tr>
                    @foreach($costing->knitting_desc['items'] ?? [] as $key=>$items)
                    <tr class="duplicate-two">
                        <td><input type="text" name="knitting_desc[items][]" value="{{ $items ?? '' }}"  class="form-control clear-input" placeholder="Items Details"></td>
                        <td><input type="text" name="knitting_desc[composition][]" value="{{ $costing->knitting_desc['composition'][$key] ?? '' }}" class="form-control clear-input" placeholder="Composition"></td>
                        <td><input type="text" name="knitting_desc[type][]" value="{{ $costing->knitting_desc['type'][$key] ?? '' }}" class="form-control clear-input" placeholder="Type"></td>
                        <td><input type="number" name="knitting_desc[qty][]" step="any"  value="{{ $costing->knitting_desc['qty'][$key] ?? '' }}" data-length="0" class="form-control count-total knitting-qty 0" placeholder="0"></td>
                        <td><input type="text" name="knitting_desc[unit][]" value="{{ $costing->knitting_desc['unit'][$key] ?? '' }}" class="form-control clear-input" placeholder="Unit"></td>
                        <td><input type="number" name="knitting_desc[price][]" step="any"  value="{{ $costing->knitting_desc['price'][$key] ?? '' }}" data-length="0" class="form-control count-total knitting-price 0" placeholder="0"></td>
                        <td><input type="number" name="knitting_desc[total][]" step="any"  value="{{ $costing->knitting_desc['total'][$key] ?? '' }}" data-length="0" readonly class="form-control knitting-total 0" placeholder="Total"></td>
                        <td><input type="number" name="knitting_desc[grand_total][]" value="{{ $costing->knitting_desc['grand_total'][$key] ?? '' }}" step="any" readonly class="form-control"></td>
                        <td><input type="text" name="knitting_desc[payment][]" value="{{ $costing->knitting_desc['payment'][$key] ?? '' }}" class="form-control clear-input" placeholder="Payment"></td>
                        <td><input type="text" name="knitting_desc[remarks][]" value="{{ $costing->knitting_desc['remarks'][$key] ?? '' }}" class="form-control clear-input" placeholder="Remarks"></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" class="text-start"><b>{{__('Knitting Type')}}</b></td>
                        <td><input type="number" readonly name="knitting_total" id="knitting_total" value="{{ $costing->knitting_total }}" class="form-control fw-semibold text-center"></td>
                        <td colspan="2"></td>
                    </tr>

                    <tr class="position-relative">
                        <td><div class="add-btn-three"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                        <td><div class="tr-remove-btn remove-three"><i class="fas fa-trash"></i></div></td>
                    </tr>
                    @foreach($costing->dyeing_desc['items'] ?? [] as $key=>$items)
                    <tr class="duplicate-three">
                        <td><input type="text" name="dyeing_desc[items][]" value="{{ $items ?? '' }}" class="form-control clear-input" placeholder="Items Details"></td>
                        <td><input type="text" name="dyeing_desc[composition][]" value="{{ $costing->dyeing_desc['composition'][$key] ?? '' }}" class="form-control clear-input" placeholder="Composition"></td>
                        <td><input type="text" name="dyeing_desc[type][]" value="{{ $costing->dyeing_desc['type'][$key] ?? '' }}" class="form-control clear-input" placeholder="Type"></td>
                        <td><input type="number" step="any" name="dyeing_desc[qty][]"  value="{{ $costing->dyeing_desc['qty'][$key] ?? '' }}" data-length="0" class="form-control count-total dyeing-qty 0" placeholder="0"></td>
                        <td><input type="text" name="dyeing_desc[unit][]" value="{{ $costing->dyeing_desc['unit'][$key] ?? '' }}" class="form-control clear-input" placeholder="Unit"></td>
                        <td><input type="number" step="any" name="dyeing_desc[price][]"  value="{{ $costing->dyeing_desc['price'][$key] ?? '' }}" data-length="0" class="form-control count-total dyeing-price 0" placeholder="0"></td>
                        <td><input type="number" step="any" name="dyeing_desc[total][]"  value="{{ $costing->dyeing_desc['total'][$key] ?? '' }}" data-length="0" readonly class="form-control dyeing-total 0" placeholder="Total"></td>
                        <td><input type="number" step="any" name="dyeing_desc[grand_total][]" value="{{ $costing->dyeing_desc['grand_total'][$key] ?? '' }}" readonly class="form-control"></td>
                        <td><input type="text" name="dyeing_desc[payment][]" value="{{ $costing->dyeing_desc['payment'][$key] ?? '' }}" class="form-control clear-input" placeholder="Payment"></td>
                        <td><input type="text" name="dyeing_desc[remarks][]" value="{{ $costing->dyeing_desc['remarks'][$key] ?? '' }}" class="form-control clear-input" placeholder="Remarks"></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" class="text-start"><b>{{__('Dyeing & Finishing')}}</b></td>
                        <td><input type="number" readonly name="dyeing_total" id="dyeing_total" value="{{ $costing->dyeing_total }}" class="form-control fw-semibold text-center"></td>
                        <td colspan="2"></td>
                    </tr>

                    <tr class="position-relative">
                        <td><div class="add-btn-four"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                        <td><div class="tr-remove-btn remove-four"><i class="fas fa-trash"></i></div></td>
                    </tr>
                    @foreach($costing->print_desc['items'] ?? [] as $key=>$items)
                    <tr class="duplicate-four">
                        <td><input type="text" name="print_desc[items][]" value="{{ $items ?? '' }}" class="form-control clear-input" placeholder="Items Details"></td>
                        <td><input type="text" name="print_desc[composition][]" value="{{ $costing->print_desc['composition'][$key] ?? '' }}" class="form-control clear-input" placeholder="Composition"></td>
                        <td><input type="text" name="print_desc[type][]" value="{{ $costing->print_desc['type'][$key] ?? '' }}" class="form-control clear-input" placeholder="Type"></td>
                        <td><input type="number" step="any" name="print_desc[qty][]"  value="{{ $costing->print_desc['qty'][$key] ?? '' }}" data-length="0" class="form-control count-total print-qty 0" placeholder="0"></td>
                        <td><input type="text" name="print_desc[unit][]" value="{{ $costing->print_desc['unit'][$key] ?? '' }}" class="form-control clear-input" placeholder="Unit"></td>
                        <td><input type="number" step="any" name="print_desc[price][]"  value="{{ $costing->print_desc['price'][$key] ?? '' }}" data-length="0" class="form-control count-total print-price 0" placeholder="0"></td>
                        <td><input type="number" step="any" name="print_desc[total][]"  value="{{ $costing->print_desc['total'][$key] ?? '' }}" data-length="0" readonly class="form-control print-total 0" placeholder="Total"></td>
                        <td><input type="number" step="any" name="print_desc[grand_total][]" value="{{ $costing->print_desc['grand_total'][$key] ?? '' }}" readonly class="form-control"></td>
                        <td><input type="text" name="print_desc[payment][]" value="{{ $costing->print_desc['payment'][$key] ?? '' }}" class="form-control clear-input" placeholder="Payment"></td>
                        <td><input type="text" name="print_desc[remarks][]" value="{{ $costing->print_desc['remarks'][$key] ?? '' }}" class="form-control clear-input" placeholder="Remarks"></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" class="text-start"><b>{{__('Print/Embo')}}</b></td>
                        <td><input type="number" readonly name="print_total" id="print_total" value="{{ $costing->print_total }}" class="form-control fw-semibold text-center"></td>
                        <td colspan="2"></td>
                    </tr>

                    <tr class="position-relative">
                        <td><div class="add-btn-five"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                        <td><div class="tr-remove-btn remove-five"><i class="fas fa-trash"></i></div></td>
                    </tr>
                    @foreach($costing->trim_desc['items'] ?? [] as $key=>$items)
                    <tr class="duplicate-five">
                        <td><input type="text" name="trim_desc[items][]" value="{{ $items ?? '' }}"  class="form-control clear-input" placeholder="Items Details"></td>
                        <td><input type="text" name="trim_desc[composition][]" value="{{ $costing->trim_desc['composition'][$key] ?? '' }}"  class="form-control clear-input" placeholder="Composition"></td>
                        <td><input type="text" name="trim_desc[type][]" value="{{ $costing->trim_desc['type'][$key] ?? '' }}"  class="form-control clear-input" placeholder="Type"></td>
                        <td><input type="number" step="any" name="trim_desc[qty][]"   data-length="0" value="{{ $costing->trim_desc['qty'][$key] ?? '' }}" class="form-control count-total trim-qty 0" placeholder="0"></td>
                        <td><input type="text" name="trim_desc[unit][]" value="{{ $costing->trim_desc['unit'][$key] ?? '' }}"  class="form-control clear-input" placeholder="Unit"></td>
                        <td><input type="number" step="any" name="trim_desc[price][]"   data-length="0" value="{{ $costing->trim_desc['price'][$key] ?? '' }}" class="form-control count-total trim-price 0" placeholder="0"></td>
                        <td><input type="number" step="any" name="trim_desc[total][]"   data-length="0" value="{{ $costing->trim_desc['total'][$key] ?? '' }}" readonly class="form-control trim-total 0" placeholder="Total"></td>
                        <td><input type="number" step="any" name="trim_desc[grand_total][]" value="{{ $costing->trim_desc['grand_total'][$key] ?? '' }}"  readonly class="form-control"></td>
                        <td><input type="text" name="trim_desc[payment][]" value="{{ $costing->trim_desc['payment'][$key] ?? '' }}"  class="form-control clear-input" placeholder="Payment"></td>
                        <td><input type="text" name="trim_desc[remarks][]" value="{{ $costing->trim_desc['remarks'][$key] ?? '' }}"  class="form-control clear-input" placeholder="Remarks"></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="text-start"><b>{{__('Trims & Accessories')}}</b></td>
                        <td colspan="6"></td>
                        <td><input type="number" readonly name="trim_total" id="trim_total" value="{{ $costing->trim_total }}" class="form-control fw-semibold text-center"></td>
                        <td colspan="2"></td>
                    </tr>

                    <tr>
                        <td class="text-start"><b>{{__('Commercial Cost')}}</b></td>
                        <td colspan="2"></td>
                        <td><input type="number" step="any" name="commercial_qty" value="{{ $costing->commercial_qty }}" class="form-control commercial-total commercial-qty" placeholder="0"></td>
                        <td><input type="text" name="commercial_unit" value="{{ $costing->commercial_unit }}" class="form-control" placeholder="Unit"></td>
                        <td><input type="number" step="any" name="commercial_price" value="{{ $costing->commercial_price }}" class="form-control commercial-total commercial-price" placeholder="0"></td>
                        <td><input type="number" step="any" name="commercial_total" id="commercial_total" value="{{ $costing->commercial_total }}" readonly class="form-control" placeholder="Total"></td>
                        <td><input type="number" readonly value="{{ $costing->commercial_total }}" class="form-control commercial-grand-total fw-semibold text-center"></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="text-start"><b>{{__('CM Cost/DZ.')}}</b></td>
                        <td colspan="2"><input type="text" name="cm_cost_composition" value="{{ $costing->cm_cost_composition }}" class="form-control" placeholder="Long Pant-1 Styles"></td>
                        <td><input type="number" step="any" name="cm_cost_qty" value="{{ $costing->cm_cost_qty }}" class="form-control cm-total cm-qty" placeholder="0"></td>
                        <td><input type="text" name="cm_cost_unit" value="{{ $costing->cm_cost_unit }}" class="form-control" placeholder="Unit"></td>
                        <td><input type="number" step="any" name="cm_cost_price" value="{{ $costing->cm_cost_price }}" class="form-control cm-total cm-price" placeholder="0"></td>
                        <td><input type="number" step="any" name="cm_cost_total" id="cm_cost_total" value="{{ $costing->cm_cost_total }}" readonly class="form-control" placeholder="Total"></td>
                        <td><input type="number" readonly value="{{ $costing->cm_cost_total }}" class="form-control cm-grand-total fw-semibold text-center"></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td><input type="number" readonly name="grand_total" id="grand_total" value="{{ $costing->grand_total }}" class="form-control fw-semibold text-center"></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-end text-uppercase"><b>{{__('Total Costing Per DZ Fob')}}</b></td>
                        <td><input type="number" readonly name="grand_total_in_dzn" id="grand_total_in_dzn" value="{{ $costing->grand_total_in_dzn }}" class="form-control fw-semibold text-center"></td>
                        <td colspan="2"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="button-group text-center mt-5">
                <a href="{{ route('costings.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
            </div>
        </form>
    </div>

    <input type="hidden" id="url" data-model="Costing" value="{{ route('costing.order') }}">

@endsection
@push('js')
    <script src="{{ asset('assets/js/custom/costing-form.js') }}"></script>
@endpush

