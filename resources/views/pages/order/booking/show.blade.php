@extends('layouts.master')

@section('title')
    {{__('Booking')}}
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="table-header">
            <h4>{{__('Show Booking Form')}}</h4>
            <a href="{{ route('bookings.index') }}" class="theme-btn print-btn"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
        </div>
        <div class="order-form-section add-suplier-modal-wrapper">
            <form>
                <div class="row">
                    <div class="col-lg-4">
                        <label><b>{{__('Image')}}</b></label>
                        <label id="upload" class="upload-img">
                            <input type="file" accept="image/*" id="upload" class="d-none file-input-change" data-id="image">
                            <i><img src="{{ asset($booking->order->image ?? 'assets/images/icons/upload.png') }}" id="image" class="img-preview"></i>
                            <p> {{__('Image , JEP, JPEG, PNG (Size 400 X 255)')}}</p>
                        </label>
                    </div>
                    <div class="col-lg-8 order-lg-first">
                        <div class="row">
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Order No')}}</label>
                                <input type="text" readonly value="{{ $booking->order->order_no ?? '' }}" required class="form-control" placeholder="Order No.">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Prepared By')}}	</label>
                                <input type="text" readonly value="{{ $booking->order->merchandiser->name ?? '' }}" class="form-control" placeholder="Prepared By">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Booking Date')}}</label>
                                <input type="date" readonly value="{{ $booking->booking_date }}" required class="form-control datepicker" placeholder="{{ date("d-M-Y") }}">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Composition')}}</label>
                                <input type="text" readonly value="{{ $booking->composition }}" class="form-control" placeholder="Composition">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Process Loss')}}</label>
                        <input type="text" readonly value="{{ $booking->meta['process_loss'] ?? '' }}" class="form-control" placeholder="Process Loss">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Others Fabric')}}</label>
                        <input type="text" readonly value="{{ $booking->meta['other_fabric'] ?? '' }}" class="form-control" placeholder="Others Fabric">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Rib')}}</label>
                        <input type="text" readonly value="{{ $booking->meta['rib'] ?? '' }}" class="form-control" placeholder="Rib">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Collar')}}</label>
                        <input type="text" readonly value="{{ $booking->meta['collar'] ?? '' }}" class="form-control" placeholder="Collar">
                    </div>
                    <div class="col-lg-12 table-form-section">
                        <div class="table-responsive responsive-table mt-4">
                            <table class="table table-two daily-production-table-print mw-1000" id="erp-table">
                                <thead>
                                <tr>
                                    <td colspan="1" rowspan="2"><strong></strong></td>
                                    <td colspan="6" rowspan="2"><strong></strong></td>
                                    <td colspan="8"><strong>{{__('Coller Size/Quantity: Solid')}}</strong></td>
                                    <td rowspan="3"><strong>{{__('Cuff Color')}}</strong></td>
                                    <td colspan="4"><strong>{{__('Cuff Solid')}}</strong></td>
                                </tr>
                                <tr>
                                    <td><input type="text" readonly value="{{ $booking->header['collar_size_qty_40'] ?? '' }}" class="form-control" placeholder="40X7.5"></td>
                                    <td><input type="text" readonly value="{{ $booking->header['collar_size_qty_41'] ?? '' }}" class="form-control" placeholder="41X7.5"></td>
                                    <td><input type="text" readonly value="{{ $booking->header['collar_size_qty_42'] ?? '' }}" class="form-control" placeholder="42X7.5"></td>
                                    <td><input type="text" readonly value="{{ $booking->header['collar_size_qty_43'] ?? '' }}" class="form-control" placeholder="43X7.5"></td>
                                    <td><input type="text" readonly value="{{ $booking->header['collar_size_qty_44'] ?? '' }}" class="form-control" placeholder="44X7.5"></td>
                                    <td><input type="text" readonly value="{{ $booking->header['collar_size_qty_45'] ?? '' }}" class="form-control" placeholder="45X7.5"></td>
                                    <td><input type="text" readonly value="{{ $booking->header['collar_size_qty_46'] ?? '' }}" class="form-control" placeholder="46X7.5"></td>
                                    <td><input type="text" readonly value="{{ $booking->header['collar_size_qty_47'] ?? '' }}" class="form-control" placeholder="47X7.5"></td>
                                    <th><input type="text" readonly value="{{ $booking->header['cuff_solid_l'] ?? '' }}" class="form-control" placeholder="Qty.XS-L"></th>
                                    <th><input type="text" readonly value="{{ $booking->header['cuff_solid_4xl'] ?? '' }}" class="form-control" placeholder="Qty.XL-4XL"></th>
                                    <th><input type="text" readonly value="{{ $booking->header['cuff_solid_5xl'] ?? '' }}" class="form-control" placeholder="Qty.XS-5XL"></th>
                                    <th><input type="text" readonly value="{{ $booking->header['cuff_solid_6xl'] ?? '' }}" class="form-control" placeholder="Qty.XL-6XL"></th>
                                </tr>
                                <tr>
                                    <th><strong>{{__('STYLE')}}</strong></th>
                                    <th><strong>{{__('COLOR')}}</strong></th>
                                    <th><strong>{{__('ITEM')}}</strong></th>
                                    <th><strong>{{__('SHIPMENT DATE')}}</strong></th>
                                    <th><strong>{{__('QTY')}}</strong></th>
                                    <th><strong>{{__('UNIT PRICE')}}</strong></th>
                                    <th><strong>{{__('TTL PRICE')}}</strong></th>
                                    <th><input type="text" readonly value="{{ $booking->header['collar_size_qty_xs'] ?? '' }}" class="form-control" placeholder="XS"></th>
                                    <th><input type="text" readonly value="{{ $booking->header['collar_size_qty_s'] ?? '' }}" class="form-control" placeholder="S"></th>
                                    <th><input type="text" readonly value="{{ $booking->header['collar_size_qty_m'] ?? '' }}" class="form-control" placeholder="M"></th>
                                    <th><input type="text" readonly value="{{ $booking->header['collar_size_qty_l'] ?? '' }}" class="form-control" placeholder="L"></th>
                                    <th><input type="text" readonly value="{{ $booking->header['collar_size_qty_xl'] ?? '' }}" class="form-control" placeholder="XL"></th>
                                    <th><input type="text" readonly value="{{ $booking->header['collar_size_qty_xxl'] ?? '' }}" class="form-control" placeholder="XXL"></th>
                                    <th><input type="text" readonly value="{{ $booking->header['collar_size_qty_3xl'] ?? '' }}" class="form-control" placeholder="3XL"></th>
                                    <th><input type="text" readonly value="{{ $booking->header['collar_size_qty_4xl'] ?? '' }}" class="form-control" placeholder="4XL"></th>
                                    <td><input type="text" readonly value="{{ $booking->header['cuff_solid_37'] ?? '' }}" class="form-control" placeholder="37X.3.5 CM"></td>
                                    <td><input type="text" readonly value="{{ $booking->header['cuff_solid_38'] ?? '' }}" class="form-control" placeholder="40X.3.5 CM"></td>
                                    <td><input type="text" readonly value="{{ $booking->header['cuff_solid_39'] ?? '' }}" class="form-control" placeholder="37X.3.5 CM"></td>
                                    <td><input type="text" readonly value="{{ $booking->header['cuff_solid_40'] ?? '' }}" class="form-control" placeholder="40X.3.5 CM"></td>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $qty = 0 ;
                                    $unit_price = 0 ;
                                    $total_price = 0 ;
                                @endphp
                                @foreach($order->orderDetails as $key=>$detail)
                                    @php
                                        $qty += $detail->qty ?? 0;
                                        $unit_price += $detail->unit_price ?? 0;
                                        $total_price += $detail->total_price ?? 0;
                                    @endphp
                                    <tr class="duplicate-one">
                                        <td>{{ $detail->style }}</td>
                                        <td>{{ $detail->color }}</td>
                                        <td>{{ $detail->item }}</td>
                                        <td>{{ $detail->shipment_date }}</td>
                                        <td>{{ $detail->qty }}</td>
                                        <td>{{ $detail->unit_price }}</td>
                                        <td>{{ $detail->total_price }}</td>
                                        <td>{{ $booking->detail->collar_size_qty['40_xs'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->collar_size_qty['41_s'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->collar_size_qty['42_m'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->collar_size_qty['43_l'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->collar_size_qty['44_xl'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->collar_size_qty['45_xxl'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->collar_size_qty['46_3xl'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->collar_size_qty['47_4xl'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->cuff_color[$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->cuff_solid['37_l'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->cuff_solid['38_4xl'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->cuff_solid['39_5xl'][$key] ?? '' }}</td>
                                        <td>{{ $booking->detail->cuff_solid['40_6xl'][$key] ?? '' }}</td>
                                    </tr>
                                @endforeach
                                <tr class="total">
                                    <td colspan="4"><h6 class="text-end">Total</h6></td>
                                    <td><h6 class="total_qty">{{ $qty }}</h6></td>
                                    <td><h6 class="total_unit_price">{{ $unit_price }}</h6></td>
                                    <td><h6 class="final_total_price">{{ $total_price }}</h6></td>
                                    <td colspan="13"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

