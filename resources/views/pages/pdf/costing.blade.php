@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Costing List') }}
@endsection

@section('pdf_content')

<table class="styled-table">
    <thead>
    <tr>
        <th>{{__('SL. ')}}</th>
        <th>{{__('Order No.')}}</th>
        <th>{{__('Party Name')}}</th>
        <th>{{__('Type')}}</th>
        <th>{{__('Style')}}</th>
        <th>{{__('Total Qty')}}</th>
        <th>{{__('Total Unit Price')}} </th>
        <th>{{__('Total Value')}}</th>
        <th>{{__('Status')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($datas as $cost)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $cost->order->order_no ?? '' }}</td>
            <td>{{ $cost->order->party->name ?? '' }}</td>
            <td>{{ $cost->order->party->type ?? '' }}</td>
            <td>{{ $cost->order_info['style'] ?? '' }}</td>
            <td>{{ $cost->order_info['qty'] ?? '' }} </td>
            <td>{{ currency_format($cost->order_info['unit_price'] ?? '') }} </td>
            <td>{{ currency_format($cost->order_info['lc'] ?? '') }} </td>
            <td>
                <div class="{{ $cost->status == 1 ? 'badge bg-warning' : ($cost->status == 2 ? 'badge bg-primary' : 'badge bg-danger') }}" title="{{ $cost->reason }}">
                    {{ $cost->status == 1 ? 'Pending' : ($cost->status == 2 ? 'Approved' : 'Reject') }}
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
