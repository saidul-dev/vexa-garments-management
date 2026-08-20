@extends('layouts.blank')

@section('main_content')
    <div class="section-container print-wrapper p-0 erp-new-invice">
        <div class="erp-table-section">
            <button class="print-window theme-btn print-btn float-end"><i class="fa fa-print"></i> Print</button>
            <div class="container p-0">
                @include('pages.invoice.header', ['title' => 'Invoice'])
                <div class="invice-detaisl">
                    <div class="bill-left-side">
                        <p><strong>Party Name:</strong> {{ $history->datas['party']['name'] ?? '' }}</p>
                        <p><strong>Address:</strong> {{ $history->datas['party']['address'] ?? '' }}</p>
                        <p><strong>Consignee & Notify:</strong> {{ $history->datas['invoice_details']['consignee'] ?? '' }}</p>
                        <p><strong>Remark:</strong> {{ $history->datas['invoice_details']['remarks'] ?? '' }}</p>

                    </div>
                    <div class="bill-right-side">
                        <p><b>Order No:</b> {{ $history->datas['order_no'] ?? '' }}</p>
                        <p><b>Contact Date:</b> {{ date('d-M-Y', strtotime($history->datas['invoice_details']['contact_date'] ?? '')) }}</p>
                        <p><b>Negotiation Period:</b> {{ $history->datas['invoice_details']['negotiation'] ?? '' }}</p>
                        <p><b>Port of Loading:</b> {{ $history->datas['invoice_details']['loading'] ?? '' }}</p>
                        <p><b>Shipment Mode:</b> {{ $history->datas['shipment_mode'] ?? '' }}</p>
                    </div>

                </div>
                <table class="table commercial-invoice text-start table-bordered invoice-two border-0" id="erp-table">
                    <thead>
                    <tr>
                        <th>S.L</th>
                        <th>VPO</th>
                        <th>SHIPMENT DATE</th>
                        <th>STYLE</th>
                        <th>ITEM</th>
                        <th>QTY</th>
                        <th>UNIT PRICE</th>
                        <th>TOTAL PRICE</th>
                    </tr>
                    <tbody>
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($history->datas['order_details'] ?? [] as $detail)
                            <tr>
                                @if($loop->first)
                                    <td rowspan="{{ $count + $loop->index }}">{{ $loop->iteration }}</td>
                                    <td rowspan="{{ $count + $loop->index }}">{{ $history->datas['order_no'] ?? '' }}</td>
                                @endif
                                <td>{{ $detail['shipment_date'] }}</td>
                                <td>{{ $detail['style'] }}</td>
                                <td>{{ $detail['item'] }}</td>
                                <td>{{ $detail['qty'] }}</td>
                                <td>{{ $detail['unit_price'] }}</td>
                                <td>{{ currency_format($detail['total_price']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tr>
                        <td colspan="5" class="text-end"><b>Total</b></td>
                        <td>
                            <b class="total_qty">
                                {{
                                    currency_format(array_reduce($history->datas['order_details'], function ($carry, $item) {
                                        return $carry + (int)$item['qty'];
                                    }, 0))
                                }}
                            </b>
                        </td>
                        <td></td>
                        <td>
                            <b class="final_total_price">
                                {{
                                    currency_format(array_reduce($history->datas['order_details'], function ($carry, $item) {
                                        return $carry + (int)$item['total_price'];
                                    }, 0))
                                }}
                            </b>
                        </td>
                    </tr>
                </table>
                <h5 class="mb-lg-3"><b>Amount in word:</b>
                        {{ ucwords(amountInWords(array_reduce($history->datas['order_details'], function ($carry, $item) {
                            return $carry + (int)$item['total_price'];
                        }, 0)))
                        }} Dollar.
                </h5>

                <div class="invoice-payment-details">
                    <h3>Payment Details:</h3>
                    <p><b>Payment Mode:</b> {{ $history->datas['payment_mode'] ?? '' }}</p>
                </div>
                @include('pages.invoice.main-footer')
            </div>
        </div>
    </div>
@endsection


