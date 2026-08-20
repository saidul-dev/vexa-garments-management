@extends('layouts.blank')

@section('main_content')
    <div class="section-container print-wrapper p-0 erp-new-invice">
        <div class="erp-table-section">
            <div class="container p-0">
                @include('pages.invoice.header',['title' => 'Invoice'])
                <div class="invice-detaisl invoice-details-two">
                    <div class="bill-left-side">
                        <p><strong>Party Name:</strong> {{ $booking->order->party->name ?? '' }}</p>
                        <p><strong>Address:</strong> {{ $booking->order->party->address ?? '' }}</p>
                        <p><strong>Consignee & Notify:</strong> {{ $booking->order->invoice_details['consignee']  ?? '' }}</p>
                        <p><strong>Remark:</strong> {{ $booking->order->invoice_details['remarks'] ?? '' }}</p>

                    </div>
                    <div class="bill-right-side">
                        <p>Order No: {{ $booking->order->order_no }}</p>
                        <p>Contact Date: {{ date('d-M-Y', strtotime($booking->order->invoice_details['contact_date'] ?? '')) }}</p>
                        <p>Negotiation Period: {{ $booking->order->invoice_details['negotiation'] ?? '' }}</p>
                        <p>Port of Loading: {{ $booking->order->invoice_details['loading'] ?? '' }}</p>
                        <p>Shipment Mode: {{ $booking->order->shipment_mode ?? '' }}</p>
                    </div>

                </div>
                <table class="table commercial-invoice text-start table-bordered invoice-two border-0" id="erp-table">
                    <thead>
                    <tr>
                        <th>S.L</th>
                        <th>VPO</th>
                        <th>STYLE</th>
                        <th>ITEM</th>
                        <th>SHIPMENT DATE</th>
                        <th>QTY</th>
                        <th>UNIT PRICE</th>
                        <th>TOTAL PRICE</th>
                    </tr>
                    <tbody>
                    @foreach ($order->orderDetails as $detail)
                        <tr>
                            @if($loop->first)
                                <td rowspan="{{ $detail->count() }}">{{ $loop->iteration }}</td>
                                <td rowspan="{{ $detail->count() }}">{{ $order->order_no }}</td>
                            @endif
                            <td>{{ $detail->style }}</td>
                            <td>{{ $detail->item }}</td>
                            <td>{{ $detail->shipment_date }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ currency_format($detail->unit_price) }}</td>
                            <td>{{ currency_format($detail->total_price) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-end"><b>Total</b></td>
                        <td><b class="total_qty">{{ $order->orderDetails->sum('qty') }}</b></td>
                        <td><b class="total_unit_price">{{ currency_format($order->orderDetails->sum('unit_price')) }}</b></td>
                        <td><b class="final_total_price">{{ currency_format($order->orderDetails->sum('total_price')) }}</b></td>
                    </tr>
                    </tbody>
                    </thead>
                </table>
                <h5 class="mb-lg-3"><b>Amount in word:</b> {{ ucwords(amountInWords($order->orderDetails->sum('total_price'))) }} Dollar.</h5></th>

                <div class="invoice-payment-details">
                    <h3>Payment Details:</h3>
                    @if($order->bank)
                        <p>Bank Name: {{$order->bank->bank_name ?? ''}}</p>
                        <p>Account Name: {{$order->bank->holder_name ?? ''}}</p>
                        <p>Account Number: {{$order->bank->account_number ?? ''}}</p>
                        <p>Bank Branch: {{$order->bank->branch_name ?? ''}}</p>
                        <p>Shift Code: {{$order->bank->routing_number ?? ''}}</p>
                    @else
                        <p>Payment Mode: {{$order->payment_mode ?? ''}}</p>
                    @endif
                </div>
                @include('pages.invoice.main-footer')
            </div>
        </div>

    </div>
@endsection


