<div class="table-header">
    <h4>{{__('Add Booking Form')}}</h4>
</div>
<div class="order-form-section add-suplier-modal-wrapper d-block">
    <form action="{{ route('bookings.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
        @csrf

        <div class="row">
            <div class="col-lg-4">
                <div class="order-management-image">
                    <label><b>{{__(' Order Item Image')}}</b></label>
                    <label id="upload" class="upload-img">
                        <i><img src="{{ asset('assets/images/icons/upload.png') }}" id="booking_image" class="img-preview"></i>
                    </label>
                </div>
            </div>
            <div class="col-lg-8 order-lg-first">
                <div class="row">
                    <div class="col-lg-6">
                        <label>{{__('Order No')}}</label>
                        <select name="order_id" id="booking" required class="table-select form-control select-tow">
                            <option value="">{{__('Select a Order')}}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}">{{ $order->order_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label>{{__('Prepared By')}}</label>
                        <input type="text" id="booking_merchandiser" readonly class="form-control" placeholder="Prepared By">
                    </div>
                    <div class="col-lg-6">
                        <label>{{('Booking Date')}}</label>
                        <input type="date" name="booking_date" value="{{ now()->format('Y-m-d') }}" required class="form-control datepicker" placeholder="{{ date("d-M-Y") }}">
                    </div>
                    <div class="col-lg-6">
                        <label>{{__('Composition')}}</label>
                        <input type="text" name="composition" class="form-control" placeholder="Composition">
                    </div>
                    <div class="col-lg-6">
                        <label>{{__('Process Loss')}}</label>
                        <input type="text" name="meta[process_loss]" class="form-control" placeholder="Process Loss">
                    </div>
                    <div class="col-lg-6">
                        <label>{{__('Others Fabric')}}</label>
                        <input type="text" name="meta[other_fabric]" class="form-control" placeholder="Others Fabric">
                    </div>
                    <div class="col-lg-6">
                        <label>{{ __('Rib') }}</label>
                        <input type="text" name="meta[rib]" class="form-control" placeholder="Rib">
                    </div>
                    <div class="col-lg-6">
                        <label>{{ __('Collar') }}</label>
                        <input type="text" name="meta[collar]" class="form-control" placeholder="Collar">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 table-form-section add-form-table">
                <div class="table-responsive responsive-table mt-4 pb-0">
                    <table class="table table-two daily-production-table-print mw-1000 booking-table" id="erp-table">
                        <thead>
                        <tr>
                            <td colspan="25" rowspan="2"><strong></strong></td>
                            <td colspan="8"><strong>{{__('Coller Size/Quantity: Solid')}}</strong></td>
                            <td rowspan="3"><strong>{{__('Cuff Color')}}</strong></td>
                            <td colspan="4"><strong>{{__('Cuff Solid')}}</strong></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="header[collar_size_qty_40]" value="40X7.5" class="form-control reset-input"></td>
                            <td><input type="text" name="header[collar_size_qty_41]" value="41X7.5" class="form-control reset-input"></td>
                            <td><input type="text" name="header[collar_size_qty_42]" value="42X7.5" class="form-control reset-input"></td>
                            <td><input type="text" name="header[collar_size_qty_43]" value="43X7.5" class="form-control reset-input"></td>
                            <td><input type="text" name="header[collar_size_qty_44]" value="44X7.5" class="form-control reset-input"></td>
                            <td><input type="text" name="header[collar_size_qty_45]" value="45X7.5" class="form-control reset-input"></td>
                            <td><input type="text" name="header[collar_size_qty_46]" value="46X7.5" class="form-control reset-input"></td>
                            <td><input type="text" name="header[collar_size_qty_47]" value="47X7.5" class="form-control reset-input"></td>
                            <th><input type="text" name="header[cuff_solid_l]" value="Qty.XS-L" class="form-control reset-input"></th>
                            <th><input type="text" name="header[cuff_solid_4xl]" value="Qty.XL-4XL" class="form-control reset-input"></th>
                            <th><input type="text" name="header[cuff_solid_5xl]" value="Qty.XS-5XL" class="form-control reset-input"></th>
                            <th><input type="text" name="header[cuff_solid_6xl]" value="Qty.XL-6XL" class="form-control reset-input"></th>
                        </tr>
                        <tr>
                            <th><strong>{{__('Style')}}</strong></th>
                            <th><strong>{{__('Color')}}</strong></th>
                            <th><strong>{{__('Item')}}</strong></th>
                            <th><strong>{{__('Shipment Date')}}</strong></th>
                            <th><strong>{{__('Garments QTY')}}</strong></th>
                            <th><strong>{{__('Unit Price')}}</strong></th>
                            <th><strong>{{__('Total Value')}}</strong></th>

                            <th><strong>{{__('Description Of Garments')}}</strong></th>
                            <th><strong>{{__('Garments Picture')}}</strong></th>
                            <th><strong>{{__('Pantone')}}</strong></th>
                            <th><strong>{{__('Body Fabrication')}}</strong></th>
                            <th><strong>{{__('Yarn Count For Body')}}</strong></th>
                            <th><strong>{{__('Garments QTY In DZN')}}</strong></th>
                            <th><strong>{{__('Consumption Body Fabric In DZN')}}</strong></th>
                            <th><strong>{{__('Body Gray Fabric In KG')}}</strong></th>
                            <th><strong>{{__('Description Of Garments (RIB)')}}</strong></th>
                            <th><strong>{{__('Yarn Counts For RIB 1*1')}}</strong></th>
                            <th><strong>{{__('Consumption RIB In DZN')}}</strong></th>
                            <th><strong>{{__('RIB In KG')}}</strong></th>
                            <th><strong>{{__('Yarn Counts For RIB 1*1 Lycra 1*1 RIB   Yarn- 26/1 Finished DIA  48” Open')}}</strong></th>
                            <th><strong>{{__('Receive')}}</strong></th>
                            <th><strong>{{__('Balance')}}</strong></th>
                            <th><strong>{{__('Gray Body Febric')}}</strong></th>
                            <th><strong>{{__('Graybody RIB (2*1)')}}</strong></th>
                            <th><strong>{{__('Revised')}}</strong></th>

                            <th><input type="text" name="header[collar_size_qty_xs]" value="XS" class="form-control reset-input"></th>
                            <th><input type="text" name="header[collar_size_qty_s]" value="S" class="form-control reset-input"></th>
                            <th><input type="text" name="header[collar_size_qty_m]" value="M" class="form-control reset-input"></th>
                            <th><input type="text" name="header[collar_size_qty_l]" value="L" class="form-control reset-input"></th>
                            <th><input type="text" name="header[collar_size_qty_xl]" value="XL" class="form-control reset-input"></th>
                            <th><input type="text" name="header[collar_size_qty_xxl]" value="XXL" class="form-control reset-input"></th>
                            <th><input type="text" name="header[collar_size_qty_3xl]" value="3XL" class="form-control reset-input"></th>
                            <th><input type="text" name="header[collar_size_qty_4xl]" value="4XL" class="form-control reset-input"></th>
                            <td><input type="text" name="header[cuff_solid_37]" value="37X.3.5 CM" class="form-control reset-input"></td>
                            <td><input type="text" name="header[cuff_solid_38]" value="40X.3.5 CM" class="form-control reset-input"></td>
                            <td><input type="text" name="header[cuff_solid_39]" value="37X.3.5 CM" class="form-control reset-input"></td>
                            <td><input type="text" name="header[cuff_solid_40]" value="40X.3.5 CM" class="form-control reset-input"></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="position-relative">
                            <td><div class="add-btn-one"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                            <td><div class="tr-remove-btn remove-one"><i class="fas fa-trash"></i></div></td>
                        </tr>

                        {{--  Here add duplicate-one tr from js  --}}

                        <tr class="total">
                            <td colspan="4"><h6 class="text-end">Totals</h6></td>
                            <td><h6 class="total_qty" >0</h6></td>
                            <td><h6 class="total_unit_price" >0</h6></td>
                            <td><h6 class="final_total_price" >0</h6></td>
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
<input type="hidden" data-model="Booking" value="{{ route('booking.order') }}" id="get-order_id">

