@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Order Report') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Date')}}</th>
            <th>{{__('Image')}}</th>
            <th>{{__('Order No.')}}</th>
            <th>{{__('Party Name')}}</th>
            <th>{{__('Party Type')}}</th>
            <th>{{__('merchandiser')}}</th>
            <th>{{__('Dept')}}</th>
            <th>{{__('GSM')}}</th>
            <th>{{__('shipment Mode')}} </th>
            <th>{{__('payment mode')}}</th>
            <th>{{__('Year')}}</th>
            <th>{{__('Season')}}</th>
            <th>{{__('Status')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $order)
            <tr>
                <td>{{ $loop->iteration }} <i class="{{ request('id') == $order->id ? 'fas fa-bell text-red' : '' }}"></i></td>
                <td>{{ formatted_date($order->created_at) }}</td>
                <td>
                    <img class="table-img" src="{{ asset($order->image ?? 'assets/images/user-img/3.png') }}" alt="not found">
                </td>
                <td>{{ $order->order_no }}</td>
                <td>{{ $order->party->name ?? ''  }}</td>
                <td>{{ $order->party->type ?? ''  }}</td>
                <td>{{ optional($order->merchandiser)->name }}</td>
                <td>{{ $order->department }}</td>
                <td>{{ $order->gsm }}</td>
                <td>{{ $order->shipment_mode }}</td>
                <td>{{ $order->payment_mode }}</td>
                <td>{{ $order->year }}</td>
                <td>{{ $order->season }}</td>
                <td>{{ $order->status == 1 ? 'Pending' : ($order->status == 2 ? 'Approved' : ($order->status == 3 ? 'Completed' : 'Reject')) }} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
