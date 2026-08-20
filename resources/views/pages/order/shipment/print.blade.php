@extends('layouts.blank')

@section('main_content')
    <div class="section-container print-wrapper p-0 erp-new-invice">
        <div class="erp-table-section">
            <div class="container p-0">
                <button class="print-window theme-btn print-btn float-end"><i class="fa fa-print"></i> Print</button>
                @include('pages.invoice.header',['title' => 'Shipment'])
                <div class="invice-detaisl">
                    <div class="bill-left-side">
                        <p><strong>Party Name:</strong> {{ $order->party->name ?? '' }}</p>
                        <p><strong>Address:</strong> {{ $order->party->address ?? '' }}</p>
                        <p><strong>Consignee & Notify:</strong> {{ $order->invoice_details['consignee']  ?? '' }}</p>
                        <p><strong>Remark:</strong> {{ $order->party->remarks ?? '' }}</p>

                    </div>
                    <div class="bill-right-side">
                        <p><strong>Order No: </strong> {{ $order->order_no }}</p>
                        <p><strong>Contact Date: </strong> {{ formatted_date($order->invoice_details['contact_date']) ?? '' }}</p>
                        <p><strong>Negotiation Period: </strong> {{ $order->invoice_details['negotiation'] ?? '' }}</p>
                        <p><strong>PORT OF LOADING: </strong> {{ $order->invoice_details['loading'] ?? '' }}</p>
                        <p><strong>Shipment Mode: </strong> {{ $order->shipment_mode }}</p>
                    </div>
                </div>
                <table class="table commercial-invoice text-center table-bordered invoice-two border-0" id="erp-table">
                    <thead>
                    <tr>
                        <th>SL.</th>
                        <th>VPO</th>
                        <th>Style</th>
                        <th>Item</th>
                        <th class="qty-new">Shipment Date</th>
                        <th class="qty-new">Qty</th>
                    </tr>
                    <tbody>
                    @php
                    $qty = 0;
                    @endphp
                        @foreach ($details as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->order_no }}</td>
                                <td>{{ $detail['style'] }}</td>
                                <td>{{ $detail['item'] }}</td>
                                <td>{{ $detail['shipment_date'] }}</td>
                                <td>{{ $detail['qty'] }}</td>
                                @php
                                    $qty += $detail['qty'];
                                @endphp
                            </tr>
                        @endforeach

                    </tbody>
                    <tr>
                        <td colspan="5" class="text-end fw-bold">Total</td>
                        <td class="fw-bold">{{ $qty }}</td>
                    </tr>
                </table>
                @include('pages.invoice.main-footer')
            </div>
        </div>

    </div>
@endsection


