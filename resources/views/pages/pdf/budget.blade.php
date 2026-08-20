@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Budget List') }}
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
        <th>{{__('Avg. / Unit Price')}} </th>
        <th>{{__('Total Value')}}</th>
        <th>{{__('status')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $budget)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $budget->order->order_no ?? '' }}</td>
            <td>{{ $budget->order->party->name ?? '' }}</td>
            <td>{{ $budget->order->party->type ?? '' }}</td>
            <td>{{ $budget->order_info['style'] ?? '' }}</td>
            <td>{{ $budget->order_info['qty'] ?? '' }} </td>
            <td>{{ currency_format($budget->order_info['unit_price'] ?? '') }} </td>
            <td>{{ currency_format($budget->order_info['lc'] ?? '') }} </td>
            <td>
            <span class="{{ $budget->status == 1 ? 'badge bg-warning' : ($budget->status == 2 ? 'badge bg-primary' : ($budget->status == 3 ? 'badge bg-success' : 'badge bg-danger')) }}" title="{{ $budget->meta['reason'] ?? '' }}">
                {{ $budget->status == 1 ? 'Pending' : ($budget->status == 2 ? 'Approved' : 'Reject') }}
            </span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
