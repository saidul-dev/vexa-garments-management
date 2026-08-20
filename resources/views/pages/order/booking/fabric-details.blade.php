@extends('layouts.blank')

@section('main_content')
    <div class="section-container  print-wrapper p-0">
        <div class="erp-table-section">
            <div class="container-fluid">
                <button class="print-window theme-btn print-btn float-end"><i class="fa fa-print"></i> Print</button>

                <div class="table-header justify-content-center border-0 d-block text-center">
                    @include('pages.invoice.header2')
                    <h4 class="mt-2">Fabric Booking Details</h4>
                </div>
                <div class="table-title-three d-flex justify-content-between">
                    <div>
                        <h5><b>Party: {{ $order->party->name ?? '' }}</b></h5>
                    </div>
                    <div>
                        <h5><b>Booking No: {{ $booking->booking_no }}</b></h5>
                    </div>
                    <div>
                        <h5><b>Date: {{ $booking->created_at }}</b></h5>
                    </div>
                </div>
                <div class="responsive-table mt-0">
                    <table class="table table-two fabric-details-table" id="erp-table">
                        <thead>
                        <tr>
                            <th colspan="6"></th>
                            <th colspan="6">GARMENTS BODY DESCRIPTION</th>
                            <th colspan="4">GARMENTS RIB DESCRIPTION</th>
                            <th colspan="7"></th>
                        </tr>
                        <tr>
                            <td>Style</td>
                            <td>PO. NO.</td>
                            <td>Description of  Garments</td>
                            <td>Garments Picture</td>
                            <td>Color</td>
                            <td>Pentone</td>
                            <td>Body Fabrication</td>
                            <td>Yarn Count for Body</td>
                            <td>Garments QTY</td>
                            <td>Garments QTY in DZN</td>
                            <td>Consumtion Body Fabric in DZN</td>
                            <td>Body Gray Fabric in KG</td>
                            <td>Description Of  Garments	</td>
                            <td>Yarn Counts for RIB 1*1</td>
                            <td>Consumtion RIB in DZN</td>
                            <td>RIB in KG</td>
                            <td>Yarn Counts for RIB 1*1 Lycra 1*1 RIB   Yarn- 26/1 Finished DIA  48” Open</td>
                            <td>Receive</td>
                            <td>Balance</td>
                            <td>Gray Body Febric</td>
                            <td>Graybody RIB (2*1)</td>
                            <td>Revised</td>
                            <td>Total Value</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->orderDetails as $key=>$detail)
                            <tr>
                            <td>{{ $detail->style }}</td>
                            <td>{{ $order->order_no }}</td>
                            <td>{{ $booking->detail->data['desc_garments'][$key] ?? '' }}</td>
                            <td><img class="table-img" src="{{ asset($booking->detail->data['images'][$key] ?? '') }}" alt="table-img"></td>
                            <td>{{ $detail->color }}</td>
                            <td>{{ $booking->detail->data['pantone'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['body_fab'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['yarn_count_body'][$key] ?? '' }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ $booking->detail->data['garments_qty_dzn'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['body_fab_dzn'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['body_gray_fab'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['desc_garments_rib'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['yarn_count_rib'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['consump_rib_dzn'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['rib_kg'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['yarn_count_rib_lycra'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['receive'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['balance'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['gray_body_fab'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['gray_body_rib'][$key] ?? '' }}</td>
                            <td>{{ $booking->detail->data['revised'][$key] ?? '' }}</td>
                            <td><b>{{ currency_format($detail->total_price) }}</b></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection


