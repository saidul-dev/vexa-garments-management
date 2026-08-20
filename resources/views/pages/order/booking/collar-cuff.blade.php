@extends('layouts.blank')

@section('title')
    {{__('Fabric Booking Report')}}
@endsection

@section('main_content')
    <div class="section-container print-wrapper p-0 ">
        <div class="erp-table-section">
            <div class="container-fluid">
                <button class="print-window theme-btn print-btn float-end"><i class="fa fa-print"></i> Print</button>
                <div class="table-header justify-content-center border-0 d-block text-center">
                    @include('pages.invoice.header2')
                    <h4 class="mt-2">{{__('COLLAR AND CUFF BOOKING')}}</h4>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2" >
                    <p class="text-lg-start">
                        <b>{{__('Buyer Name:')}}</b>  {{ $booking->order->party->name ?? '' }}
                    </p>
                    <p class="text-lg-end ml-auto">
                        <b>{{__('Date:')}}</b>  {{ formatted_date($booking->booking_date) }}
                    </p>
                </div>
                <div class="responsive-table mt-0">
                    <table class="table table-two daily-production-table-print" id="erp-table">
                        <thead>
                        <tr>
                            <td colspan="1" rowspan="2"></td>
                            <td colspan="6" rowspan="2"></td>
                            <td colspan="8" class="bg-info fw-bold">{{__('Coller Size/Quantity: Solid')}}</td>
                            <td rowspan="3" class="bg-warning fw-bold">{{__('Cuff Color')}}</td>
                            <td colspan="4" class="bg-info fw-bold">{{__('Cuff Solid')}}</td>
                        </tr>
                        <tr>
                            <td class="bg-warning">{{ $booking->header['collar_size_qty_40'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['collar_size_qty_41'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['collar_size_qty_42'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['collar_size_qty_43'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['collar_size_qty_44'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['collar_size_qty_45'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['collar_size_qty_46'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['collar_size_qty_47'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['cuff_solid_l'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['cuff_solid_4xl'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['cuff_solid_5xl'] ?? '' }}</td>
                            <td class="bg-warning">{{ $booking->header['cuff_solid_6xl'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th><strong>{{__('Style')}}</strong></th>
                            <th><strong>{{__('Color')}}</strong></th>
                            <th><strong>{{__('Item')}}</strong></th>
                            <th><strong>{{__('Shipment Date')}}</strong></th>
                            <th><strong>{{__('Qty')}}</strong></th>
                            <th><strong>{{__('Unit Price')}}</strong></th>
                            <th><strong>{{__('TTL Price')}}</strong></th>
                            <th>{{ $booking->header['collar_size_qty_xs'] ?? '' }}</th>
                            <th>{{ $booking->header['collar_size_qty_s'] ?? '' }}</th>
                            <th>{{ $booking->header['collar_size_qty_m'] ?? '' }}</th>
                            <th>{{ $booking->header['collar_size_qty_l'] ?? '' }}</th>
                            <th>{{ $booking->header['collar_size_qty_xl'] ?? '' }}</th>
                            <th>{{ $booking->header['collar_size_qty_xxl'] ?? '' }}</th>
                            <th>{{ $booking->header['collar_size_qty_3xl'] ?? '' }}</th>
                            <th>{{ $booking->header['collar_size_qty_4xl'] ?? '' }}</th>
                            <th>{{ $booking->header['cuff_solid_37'] ?? '' }}</th>
                            <th>{{ $booking->header['cuff_solid_38'] ?? '' }}</th>
                            <th>{{ $booking->header['cuff_solid_39'] ?? '' }}</th>
                            <th>{{ $booking->header['cuff_solid_40'] ?? '' }}</th>
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
                            <tr>
                                <td>{{ $detail->style }}</td>
                                <td>{{ $detail->color }}</td>
                                <td>{{ $detail->item }}"</td>
                                <td>{{ $detail->shipment_date }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>{{ $detail->unit_price }}</td>
                                <td>{{ number_format($detail->total_price, 2, '.', '') }}</td>
                                <td>{{ $booking->detail->collar_size_qty['40_xs'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->collar_size_qty['41_s'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->collar_size_qty['42_m'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->collar_size_qty['43_l'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->collar_size_qty['44_xl'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->collar_size_qty['45_xxl'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->collar_size_qty['46_3xl'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->collar_size_qty['47_4xl'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->cuff_color[$key] ?? '' }}"</td>
                                <td>{{ $booking->detail->cuff_solid['37_l'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->cuff_solid['38_4xl'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->cuff_solid['39_5xl'][$key] ?? '' }}</td>
                                <td>{{ $booking->detail->cuff_solid['40_6xl'][$key] ?? '' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4"><h6 class="text-end fw-bold">Total </h6></td>
                            <td><h6 class="fw-bold">{{ $qty }}</h6></td>
                            <td></td>
                            <td><h6 class="fw-bold">{{ number_format($total_price, 2, '.', '') }}</h6></td>
                            <td colspan="13"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
