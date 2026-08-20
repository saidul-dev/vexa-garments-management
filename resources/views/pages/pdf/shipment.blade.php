@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Shipment List') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{ __('SL.') }}</th>
            <th>{{ __('Invoice No.') }}</th>
            <th>{{ __('Order No.') }}</th>
            <th>{{ __('Creator') }}</th>
            <th>{{ __('Total Qty') }}</th>
            <th>{{ __('Total CM') }}</th>
            <th>{{ __('Created At') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $shipment)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>{{ $shipment->invoice_no }}</td>
                <td>{{ $shipment->order->order_no ?? '' }}</td>
                <td>
                    {{ $shipment->user->name ?? '' }}
                </td>
                <td>
                    <b>{{ $shipment->details_sum_qty }}</b>
                </td>
                <td>
                    <b>{{ currency_format($shipment->details_sum_total_cm) }}</b>
                </td>
                <td>
                    {{ formatted_date($shipment->created_at, 'd M, Y H:m A') }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
