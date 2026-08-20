@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Order Summery') }}
@endsection

@section('pdf_content')
<table class="styled-table">
    <thead>
    <tr>
        <th>{{ __('SL.') }}</th>
        <th>{{ __('Order No.') }}</th>
        <th>{{ __('Image') }}</th>
        <th>{{ __('Party') }}</th>
        <th>{{ __('merchandiser') }}</th>
        <th>{{ __('GSM') }}</th>
        <th>{{ __('shipment Mode') }}</th>
        <th>{{ __('payment mode') }}</th>
        <th>{{ __('Year') }}</th>
        <th>{{ __('Season') }}</th>
        <th>{{ __('Total Qty') }}</th>
        <th>{{ __('Total Unit Price') }}</th>
        <th>{{ __('Total Value') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $order)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $order->order_no }}</td>
            <td>
                @if(file_exists(public_path($order->image)))
                    <img class="table-img" src="{{ asset($order->image) }}">
                @endif
            </td>
            <td>{{ $order->party->name ?? ''  }}</td>
            <td>{{ optional($order->merchandiser)->name }}</td>
            <td>{{ $order->gsm }}</td>
            <td>{{ $order->shipment_mode }}</td>
            <td>{{ $order->payment_mode }}</td>
            <td>{{ $order->year }}</td>
            <td>{{ $order->season }}</td>
            <td><b>{{ $order->orderDetails->sum('qty') ?? 0 }}</b></td>
            <td><b>{{ currency_format($order->orderDetails->sum('unit_price') ?? 0) }}</b></td>
            <td><b>{{ currency_format($order->orderDetails->sum('total_price') ?? 0) }}</b></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
