@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Accessories Order List') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Invoice No.')}}</th>
            <th>{{__('Party name')}}</th>
            <th>{{__('Accessories')}} </th>
            <th>{{__('Unit')}}</th>
            <th>{{__('QTY')}}</th>
            <th>{{__('Unit price')}}</th>
            <th>{{__('TTL Amount')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $order )
            <tr>
                <td>{{ $loop->iteration }} </td>
                <td>{{ $order->invoice_no }}</td>
                <td>{{ ucwords($order->party->name ?? '') }}</td>
                <td>{{ $order->accessory->name ?? '' }}</td>
                <td>{{ optional($order->accessory->unit)->name ?? '' }}</td>
                <td><b>{{ $order->qty_unit }}</b></td>
                <td><b>{{ currency_format($order->unit_price) }}</b></td>
                <td><b>{{ currency_format($order->ttl_amount) }}</b></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
