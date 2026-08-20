@extends('layouts.master')

@section('title')
    {{__('Booking')}}
@endsection

@section('main_content')
    <div class="container-fluid mt-3">
        <div class="table-header">
            <h4>{{__('Edit Booking Form')}}</h4>
            <a href="{{ route('bookings.index') }}" class="theme-btn print-btn text-light"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
        </div>
        <div class="order-form-section add-suplier-modal-wrapper d-block">
            <form action="{{ route('bookings.update',$booking->id) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-lg-4">
                        <div class="order-management-image">
                            <label><b>{{__('Upload Item Image')}}</b></label>
                            <label id="upload" class="upload-img">
                                <i><img src="{{ asset($booking->order->image ?? 'assets/images/icons/upload.png') }}" id="booking_image" class="img-preview"></i>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-8 order-lg-first">
                        <div class="row">
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Order No')}}</label>
                                <select name="order_id" id="booking" class="table-select form-control select-tow" disabled>
                                    <option value="">{{__('Select a Order')}}</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" @selected($booking->order_id == $order->id)>{{ $order->order_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Prepared By')}}	</label>
                                <input type="text" readonly value="{{ $booking->order->merchandiser->name ?? '' }}" id="booking_merchandiser" class="form-control" placeholder="Prepared By">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{('Booking Date')}}</label>
                                <input type="date" name="booking_date" required  value="{{ $booking->booking_date }}" class="form-control datepicker" placeholder="{{ date("d-M-Y") }}">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Composition')}}</label>
                                <input type="text" name="composition" value="{{ $booking->composition }}" class="form-control" placeholder="Composition">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Process Loss')}}</label>
                        <input type="text" name="meta[process_loss]" value="{{ $booking->meta['process_loss'] ?? '' }}" class="form-control" placeholder="Process Loss">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Others Fabric')}}</label>
                        <input type="text" name="meta[other_fabric]" value="{{ $booking->meta['other_fabric'] ?? '' }}" class="form-control" placeholder="Others Fabric">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Rib')}}</label>
                        <input type="text" name="meta[rib]" value="{{ $booking->meta['rib'] ?? '' }}" class="form-control" placeholder="Rib">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label>{{__('Collar')}}</label>
                        <input type="text" name="meta[collar]" value="{{ $booking->meta['collar'] ?? '' }}" class="form-control" placeholder="Collar">
                    </div>
                    <div class="col-lg-12 table-form-section">
                        <div class="table-responsive responsive-table mt-4">
                            <table class="table table-two daily-production-table-print mw-1000" id="erp-table">
                                <thead>
                                <tr>
                                    <td colspan="25" rowspan="2"><strong></strong></td>
                                    <td colspan="8"><strong>{{__('Coller Size/Quantity: Solid')}}</strong></td>
                                    <td rowspan="3"><strong>{{__('Cuff Color')}}</strong></td>
                                    <td colspan="4"><strong>{{__('Cuff Solid')}}</strong></td>
                                </tr>
                                <tr>
                                    <td><input type="text" name="header[collar_size_qty_40]" value="{{ $booking->header['collar_size_qty_40'] ?? '' }}" class="form-control reset-input"></td>
                                    <td><input type="text" name="header[collar_size_qty_41]" value="{{ $booking->header['collar_size_qty_41'] ?? '' }}" class="form-control reset-input"></td>
                                    <td><input type="text" name="header[collar_size_qty_42]" value="{{ $booking->header['collar_size_qty_42'] ?? '' }}" class="form-control reset-input"></td>
                                    <td><input type="text" name="header[collar_size_qty_43]" value="{{ $booking->header['collar_size_qty_43'] ?? '' }}" class="form-control reset-input"></td>
                                    <td><input type="text" name="header[collar_size_qty_44]" value="{{ $booking->header['collar_size_qty_44'] ?? '' }}" class="form-control reset-input"></td>
                                    <td><input type="text" name="header[collar_size_qty_45]" value="{{ $booking->header['collar_size_qty_45'] ?? '' }}" class="form-control reset-input"></td>
                                    <td><input type="text" name="header[collar_size_qty_46]" value="{{ $booking->header['collar_size_qty_46'] ?? '' }}" class="form-control reset-input"></td>
                                    <td><input type="text" name="header[collar_size_qty_47]" value="{{ $booking->header['collar_size_qty_47'] ?? '' }}" class="form-control reset-input"></td>
                                    <th><input type="text" name="header[cuff_solid_l]" value="{{ $booking->header['cuff_solid_l'] ?? '' }}" class="form-control reset-input"></th>
                                    <th><input type="text" name="header[cuff_solid_4xl]" value="{{ $booking->header['cuff_solid_4xl'] ?? '' }}" class="form-control reset-input"></th>
                                    <th><input type="text" name="header[cuff_solid_5xl]" value="{{ $booking->header['cuff_solid_5xl'] ?? '' }}" class="form-control reset-input"></th>
                                    <th><input type="text" name="header[cuff_solid_6xl]" value="{{ $booking->header['cuff_solid_6xl'] ?? '' }}" class="form-control reset-input"></th>
                                </tr>
                                <tr>
                                    <th><strong>{{__('Style')}}</strong></th>
                                    <th><strong>{{__('Color')}}</strong></th>
                                    <th><strong>{{__('Item')}}</strong></th>
                                    <th><strong>{{__('Shipment Date')}}</strong></th>
                                    <th><strong>{{__('Garments QTY')}}</strong></th>
                                    <th><strong>{{__('Unit Price')}}</strong></th>
                                    <th><strong>{{__('Total Value')}}</strong></th>

                                    <th><strong>{{__('Description Of  Garments')}}</strong></th>
                                    <th><strong>{{__('Garments Picture')}}</strong></th>
                                    <th><strong>{{__('Pantone')}}</strong></th>
                                    <th><strong>{{__('Body Fabrication')}}</strong></th>
                                    <th><strong>{{__('Yarn Count For Body')}}</strong></th>
                                    <th><strong>{{__('Garments QTY In DZN')}}</strong></th>
                                    <th><strong>{{__('Consumption Body Fabric In DZN')}}</strong></th>
                                    <th><strong>{{__('Body Gray Fabric In KG')}}</strong></th>
                                    <th><strong>{{__('Description Of  Garments (RIB)')}}</strong></th>
                                    <th><strong>{{__('Yarn Counts For RIB 1*1')}}</strong></th>
                                    <th><strong>{{__('Consumption RIB In DZN')}}</strong></th>
                                    <th><strong>{{__('RIB In KG')}}</strong></th>
                                    <th><strong>{{__('Yarn Counts For RIB 1*1 Lycra 1*1 RIB   Yarn- 26/1 Finished DIA  48” Open')}}</strong></th>
                                    <th><strong>{{__('Receive')}}</strong></th>
                                    <th><strong>{{__('Balance')}}</strong></th>
                                    <th><strong>{{__('Gray Body Febric')}}</strong></th>
                                    <th><strong>{{__('Graybody RIB (2*1)')}}</strong></th>
                                    <th><strong>{{__('Revised')}}</strong></th>
                                    <th><input type="text" name="header[collar_size_qty_xs]" value="{{ $booking->header['collar_size_qty_xs'] ?? '' }}" class="form-control reset-input"></th>
                                    <th><input type="text" name="header[collar_size_qty_s]" value="{{ $booking->header['collar_size_qty_s'] ?? '' }}" class="form-control reset-input"></th>
                                    <th><input type="text" name="header[collar_size_qty_m]" value="{{ $booking->header['collar_size_qty_m'] ?? '' }}" class="form-control reset-input"></th>
                                    <th><input type="text" name="header[collar_size_qty_l]" value="{{ $booking->header['collar_size_qty_l'] ?? '' }}" class="form-control reset-input"></th>
                                    <th><input type="text" name="header[collar_size_qty_xl]" value="{{ $booking->header['collar_size_qty_xl'] ?? '' }}" class="form-control reset-input"></th>
                                    <th><input type="text" name="header[collar_size_qty_xxl]" value="{{ $booking->header['collar_size_qty_xxl'] ?? '' }}" class="form-control reset-input"></th>
                                    <th><input type="text" name="header[collar_size_qty_3xl]" value="{{ $booking->header['collar_size_qty_3xl'] ?? '' }}" class="form-control reset-input"></th>
                                    <th><input type="text" name="header[collar_size_qty_4xl]" value="{{ $booking->header['collar_size_qty_4xl'] ?? '' }}" class="form-control reset-input"></th>
                                    <td><input type="text" name="header[cuff_solid_37]" value="{{ $booking->header['cuff_solid_37'] ?? '' }}" class="form-control reset-input"></td>
                                    <td><input type="text" name="header[cuff_solid_38]" value="{{ $booking->header['cuff_solid_38'] ?? '' }}" class="form-control reset-input"></td>
                                    <td><input type="text" name="header[cuff_solid_39]" value="{{ $booking->header['cuff_solid_39'] ?? '' }}" class="form-control reset-input"></td>
                                    <td><input type="text" name="header[cuff_solid_40]" value="{{ $booking->header['cuff_solid_40'] ?? '' }}" class="form-control reset-input"></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="position-relative">
                                    <td><div class="add-btn-one"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                                    <td><div class="tr-remove-btn remove-one"><i class="fas fa-trash"></i></div></td>
                                </tr>
                                @php
                                    $qty = 0 ;
                                    $unit_price = 0 ;
                                    $total_price = 0 ;
                                @endphp
                                @foreach($singleOrder->orderDetails as $key=>$detail)
                                    @php
                                   $qty += $detail->qty ?? 0;
                                   $unit_price += $detail->unit_price ?? 0;
                                   $total_price += $detail->total_price ?? 0;
                                   @endphp
                                   <tr class="duplicate-one">
                                   <td><input type="text" name="style[]" value="{{ $detail->style }}" class="form-control clear-input" required placeholder="Style"></td>
                                   <td><input type="text" name="color[]" value="{{ $detail->color }}" class="form-control clear-input" placeholder="Color"></td>
                                   <td><input type="text" name="item[]" value="{{ $detail->item }}" class="form-control clear-input" placeholder="Item description"></td>
                                   <td><input type="date" name="shipment_date[]" value="{{ $detail->shipment_date }}" required class="form-control clear-input"></td>
                                   <td><input type="number" name="qty[]" value="{{ $detail->qty }}" class="form-control count-length qty {{ $key }}" data-length="{{ $key }}" required placeholder="Qty"></td>
                                   <td><input type="number" name="unit_price[]" value="{{ $detail->unit_price }}" class="form-control count-length unit_price {{ $key }}" data-length="{{ $key }}" required placeholder="Unit price"></td>
                                   <td><input type="number" name="total_price[]" value="{{ number_format($detail->total_price, 2, '.', '') }}" class="form-control total_price {{ $key }}" placeholder="Total price" readonly data-length="{{ $key }}" ></td>

                                   <td><input type="text" name="data[desc_garments][]" value="{{ $booking->detail->data['desc_garments'][$key] ?? '' }}" class="form-control clear-input" placeholder="Description Of Garments"></td>
                                   <td>
                                       <label for="image{{ $key }}" class="remove-position">
                                           <input type="file" id="image{{ $key }}" name="data[images][]" class="form-control reset-img d-none on-change-input" accept="image/*">
                                           <img src="{{ asset($booking->detail->data['images'][$key] ?? 'assets/images/icons/upload2.png') }}"  class="table-img justify-content-center">
                                       </label>
                                   </td>
                                   <td><input type="text" name="data[pantone][]" value="{{ $booking->detail->data['pantone'][$key] ?? '' }}" class="form-control clear-input" placeholder="Pantone"></td>
                                   <td><input type="text" name="data[body_fab][]" value="{{ $booking->detail->data['body_fab'][$key] ?? '' }}" class="form-control clear-input" placeholder="Body Fabrication"></td>
                                   <td><input type="text" name="data[yarn_count_body][]" value="{{ $booking->detail->data['yarn_count_body'][$key] ?? '' }}" class="form-control clear-input" placeholder="Yarn Count For Body"></td>
                                   <td><input type="text" name="data[garments_qty_dzn][]" value="{{ $booking->detail->data['garments_qty_dzn'][$key] ?? '' }}" class="form-control clear-input" placeholder="Garments QTY In DZN"></td>
                                   <td><input type="text" name="data[body_fab_dzn][]" value="{{ $booking->detail->data['body_fab_dzn'][$key] ?? '' }}" class="form-control clear-input" placeholder="Consumption Body Fabric In DZN"></td>
                                   <td><input type="text" name="data[body_gray_fab][]" value="{{ $booking->detail->data['body_gray_fab'][$key] ?? '' }}" class="form-control clear-input" placeholder="Body Gray Fabric In KG"></td>
                                   <td><input type="text" name="data[desc_garments_rib][]" value="{{ $booking->detail->data['desc_garments_rib'][$key] ?? '' }}" class="form-control clear-input" placeholder="Description Of Garments (RIB)"></td>
                                   <td><input type="text" name="data[yarn_count_rib][]" value="{{ $booking->detail->data['yarn_count_rib'][$key] ?? '' }}" class="form-control clear-input" placeholder="Yarn Counts For RIB 1*1"></td>
                                   <td><input type="text" name="data[consump_rib_dzn][]" value="{{ $booking->detail->data['consump_rib_dzn'][$key] ?? '' }}" class="form-control clear-input" placeholder="Consumption RIB In DZN"></td>
                                   <td><input type="text" name="data[rib_kg][]" value="{{ $booking->detail->data['rib_kg'][$key] ?? '' }}" class="form-control clear-input" placeholder="RIB In KG"></td>
                                   <td><input type="text" name="data[yarn_count_rib_lycra][]" value="{{ $booking->detail->data['yarn_count_rib_lycra'][$key] ?? '' }}" class="form-control clear-input" placeholder="Yarn Counts For RIB 1*1 Lycra 1*1 RIB Yarn- 26/1 Finished DIA 48” Open"></td>
                                   <td><input type="text" name="data[receive][]" value="{{ $booking->detail->data['receive'][$key] ?? '' }}" class="form-control clear-input" placeholder="Receive"></td>
                                   <td><input type="text" name="data[balance][]" value="{{ $booking->detail->data['balance'][$key] ?? '' }}" class="form-control clear-input" placeholder="Balance"></td>
                                   <td><input type="text" name="data[gray_body_fab][]" value="{{ $booking->detail->data['gray_body_fab'][$key] ?? '' }}" class="form-control clear-input" placeholder="Gray Body Febric"></td>
                                   <td><input type="text" name="data[gray_body_rib][]" value="{{ $booking->detail->data['gray_body_rib'][$key] ?? '' }}" class="form-control clear-input" placeholder="Graybody RIB (2*1)"></td>
                                   <td><input type="text" name="data[revised][]" value="{{ $booking->detail->data['revised'][$key] ?? '' }}" class="form-control clear-input" placeholder="Revised"></td>
                                   <td><input type="text" name="collar_size_qty[40_xs][]" value="{{ $booking->detail->collar_size_qty['40_xs'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="collar_size_qty[41_s][]" value="{{ $booking->detail->collar_size_qty['41_s'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="collar_size_qty[42_m][]" value="{{ $booking->detail->collar_size_qty['42_m'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="collar_size_qty[43_l][]" value="{{ $booking->detail->collar_size_qty['43_l'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="collar_size_qty[44_xl][]" value="{{ $booking->detail->collar_size_qty['44_xl'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="collar_size_qty[45_xxl][]" value="{{ $booking->detail->collar_size_qty['45_xxl'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="collar_size_qty[46_3xl][]" value="{{ $booking->detail->collar_size_qty['46_3xl'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="collar_size_qty[47_4xl][]" value="{{ $booking->detail->collar_size_qty['47_4xl'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="cuff_color[]" value="{{ $booking->detail->cuff_color[$key] ?? '' }}" class="form-control clear-input" placeholder="Same to collor"></td>
                                   <td><input type="text" name="cuff_solid[37_l][]" value="{{ $booking->detail->cuff_solid['37_l'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="cuff_solid[38_4xl][]" value="{{ $booking->detail->cuff_solid['38_4xl'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="cuff_solid[39_5xl][]" value="{{ $booking->detail->cuff_solid['39_5xl'][$key] ?? '' }}" class="form-control clear-input"></td>
                                   <td><input type="text" name="cuff_solid[40_6xl][]" value="{{ $booking->detail->cuff_solid['40_6xl'][$key] ?? '' }}" class="form-control clear-input"></td>

                               </tr>
                               @endforeach
                               <tr class="total">
                                   <td colspan="4"><h6 class="text-end">Totals</h6></td>
                                   <td><h6 class="total_qty">{{ $qty }}</h6></td>
                                   <td><h6 class="total_unit_price">{{ $unit_price }}</h6></td>
                                   <td><h6 class="final_total_price">{{ $total_price }}</h6></td>
                                   <td colspan="31"></td>
                               </tr>
                               </tbody>
                           </table>
                       </div>
                   </div>
                   <div class="col-lg-12">
                       <div class="button-group text-center mt-5">
                           <button type="reset" class="theme-btn border-btn m-2">{{__('Reset')}}</button>
                           <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                       </div>
                   </div>
               </div>
           </form>
       </div>
       <input type="hidden" value="{{ route('booking.order') }}" id="get-order_id">
   </div>
@endsection

@push('js')
<script src="{{ asset('assets/js/custom/booking.js') }}"></script>
@endpush
