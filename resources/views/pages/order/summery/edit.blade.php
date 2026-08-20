@extends('layouts.master')

@section('title')
    {{__('Order Summery')}}
@endsection

@section('main_content')
    <div class="container-fluid mt-3">
        <div class="table-header">
            <h4>{{__('Edit order')}}</h4>
        </div>
        <div class="order-form-section">
            <form action="{{ route('orders.update',$order->id) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-lg-4 mt-2 order-lg-last">
                        <div class="order-management-image">
                            <label><b>{{__('Uplaod Item Image')}}</b></label>
                            <label for="upload" class="upload-img">
                                <input type="file" name="image" id="upload" accept="image/*" class="d-none file-input-change" data-id="image">
                                <i><img src="{{ asset($order->image ?? 'assets/images/icons/upload.png') }}" id="image" class="img-preview"></i>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Party name')}}</label>
                                <select name="party_id" required class="form-control table-select w-100" disabled>
                                    <option value="">{{__('Select a Party')}}</option>
                                    @foreach($parties as $party)
                                        <option value="{{ $party->id }}" @selected($party->id == $order->party_id)>{{ $party->name }} ({{ optional($party->user)->phone }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mt-2 {{ auth()->user()->role == 'merchandiser' ? 'd-none' : '' }}">
                                <label class="table-select-lebel">{{__('Merchandiser name')}}</label>
                                <div class="input-group">
                                    <select name="merchandiser_id" required class="form-control table-select remove-bg-table-select">
                                        <option value="">{{__('Select a Merchandiser')}}</option>
                                        @foreach($merchandisers as $merchandiser)
                                            <option value="{{ $merchandiser->id }}" @selected($merchandiser->id == $order->merchandiser_id)>{{ $merchandiser->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <a href="{{ route('users.create', ['users' => 'merchandiser']) }}" class="modal-btn-ctg input-group-text"><i class="fal fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Order No.')}}</label>
                                <input type="text" name="order_no" required value="{{ $order->order_no }}" class="form-control" placeholder="Enter Order No." >
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Order Title')}}</label>
                                <input type="text" name="title" value="{{ $order->title }}" required class="form-control" placeholder="Enter Order Title">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Order Description')}}</label>
                                <input type="text" name="description" value="{{ $order->description }}" class="form-control" placeholder="Enter Order Description">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Fabrication')}}</label>
                                <input type="text" name="fabrication" value="{{ $order->fabrication }}" class="form-control" placeholder="Enter Fab Name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row new-order-wrapper">
                    <div class="col-lg-4 mt-2">
                        <label>{{__('GSM')}}</label>
                        <input type="text" name="gsm" value="{{ $order->gsm }}" class="form-control" placeholder="Enter GSM">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Yarn Count')}}</label>
                        <input type="text" name="yarn_count" value="{{ $order->yarn_count }}" class="form-control" placeholder="Enter Yarn Count">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Shipment Mode')}}</label>
                        <input type="text" name="shipment_mode" value="{{ $order->shipment_mode }}" class="form-control" placeholder="Enter Shipment Mode">
                    </div>

                    <div class="col-lg-4 mt-2">
                        <label>{{__('Payment Mode')}}</label>
                        <input type="text" name="payment_mode" value="{{ $order->payment_mode }}" class="form-control" placeholder="Enter Payment Mode">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Bank Account')}}</label>
                        <select name="bank_id" required class="form-control table-select w-100 form-control">
                            <option value="">{{__('Select a Account')}}</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}" @selected($bank->id == $order->bank_id)>{{ $bank->holder_name }} ({{ $bank->account_number  }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Year')}}</label>
                        <input type="text" name="year" value="{{ $order->year }}" class="form-control" placeholder="Enter Year">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Season')}}</label>
                        <input type="text" name="season" value="{{ $order->season }}" class="form-control" placeholder="Enter Season">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Pantone')}}</label>
                        <input type="text" name="meta[pantone]" value="{{ $order->meta['pantone'] ?? '' }}" class="form-control" placeholder="Enter Pantone">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Consignee & Notify')}}</label>
                        <input type="text" name="invoice_details[consignee]" value="{{ $order->invoice_details['consignee'] ?? '' }}" class="form-control" placeholder="Consignee & Notify">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Contact Date')}}</label>
                        <input type="date" name="invoice_details[contact_date]" value="{{ $order->invoice_details['contact_date'] ?? '' }}" class="form-control" placeholder="Contact Date">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Expiry Date')}}</label>
                        <input type="date" name="invoice_details[expire_date]" value="{{ $order->invoice_details['expire_date'] ?? '' }}" class="form-control" placeholder="Expiry Date">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Negotiation Period')}}</label>
                        <input type="text" name="invoice_details[negotiation]" value="{{ $order->invoice_details['negotiation'] ?? '' }}" class="form-control" placeholder="15 Days">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Port of Loading')}}</label>
                        <input type="text" name="invoice_details[loading]" value="{{ $order->invoice_details['loading'] ?? '' }}" class="form-control" placeholder="Port of Loading">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Port of Discharge')}}</label>
                        <input type="text" name="invoice_details[discharge]" value="{{ $order->invoice_details['discharge'] ?? '' }}" class="form-control" placeholder="Port of Discharge">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Payment Terms')}}</label>
                        <input type="text" name="invoice_details[payment_terms]" value="{{ $order->invoice_details['payment_terms'] ?? '' }}" class="form-control" placeholder="Payment Terms">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Remarks')}}</label>
                        <input type="text" name="invoice_details[remarks]" value="{{ $order->invoice_details['remarks'] }}" class="form-control" placeholder="Remarks">
                    </div>

                    <div class="col-lg-12 table-form-section">
                        <div class="table-responsive responsive-table mt-4 pb-3">
                            <table class="table table-two daily-production-table-print mw-1000" id="erp-table">
                                <thead>
                                <tr>
                                    <th><strong>{{__('STYLE')}}</strong></th>
                                    <th><strong>{{__('COLOR')}}</strong></th>
                                    <th><strong>{{__('ITEM')}}</strong></th>
                                    <th><strong>{{__('SHIPMENT DATE')}}</strong></th>
                                    <th><strong>{{__('QTY')}}</strong></th>
                                    <th><strong>{{__('UNIT PRICE')}}</strong></th>
                                    <th><strong>{{__('TTL PRICE')}}</strong></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="position-relative">
                                    <td><div class="add-btn-one"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                                    <td><div class="tr-remove-btn remove-one"><i class="fas fa-trash"></i></div></td>
                                </tr>
                                @foreach($order->orderDetails as $key => $detail)
                                <tr class="duplicate-one">
                                    <td><input type="text" name="style[]" value="{{ $detail->style }}" class="form-control style clear-input" required placeholder="Style"></td>
                                    <td><input type="text" name="color[]" value="{{ $detail->color }}" class="form-control color clear-input" placeholder="Color"></td>
                                    <td><input type="text" name="item[]" value="{{ $detail->item }}" class="form-control item clear-input" placeholder="Item description"></td>
                                    <td><input type="date" name="shipment_date[]" value="{{ $detail->shipment_date }}" required class="form-control shipment_date clear-input"></td>
                                    <td><input type="number" name="qty[]" value="{{ $detail->qty }}" class="form-control count-length qty {{ $key }}" data-length="{{ $key }}" required placeholder="Qty"></td>
                                    <td><input type="number" name="unit_price[]" value="{{ $detail->unit_price }}" class="form-control count-length unit_price {{ $key }}" data-length="{{ $key }}" required placeholder="Unit price"></td>
                                    <td><input type="number" name="total_price[]" value="{{ number_format($detail->total_price, 2, '.', '') }}" class="form-control total_price {{ $key }}" placeholder="Total price" readonly data-length="{{ $key }}"></td>
                                </tr>
                                @endforeach
                                <tr class="total">
                                    <td colspan="4"><h6 class="text-end">Total</h6></td>
                                    <td><h6 class="total_qty">{{ $order->orderDetails->sum('qty') }}</h6></td>
                                    <td><h6 class="total_unit_price">{{ $order->orderDetails->sum('unit_price') }}</h6></td>
                                    <td><h6 class="final_total_price">{{ $order->orderDetails->sum('total_price') }}</h6></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-0">
                        <div class="button-group text-center">
                            <a href="{{ route('orders.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                            <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
<script src="{{ asset('assets/js/custom/booking.js') }}"></script>
@endpush
