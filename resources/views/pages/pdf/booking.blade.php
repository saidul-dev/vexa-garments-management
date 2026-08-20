@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Booking List') }}
@endsection

@section('pdf_content')
<table class="styled-table">
    <thead>
    <tr>
        <th>{{__('SL.')}}</th>
        <th>{{__('Order No')}}</th>
        <th>{{__('Booking date')}}</th>
        <th>{{__('Party Name')}}</th>
        <th>{{__('Type')}}</th>
        <th>{{__('Composition')}}</th>
        <th>{{__('Order Image')}}</th>
        <th>{{__('Process Loss')}}</th>
        <th>{{__('Others Fabric')}} </th>
        <th>{{__('Rib')}}</th>
        <th>{{__('Collar')}}</th>
        <th>{{__('Prepared by')}}</th>
        <th>{{__('Status')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $booking)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $booking->order->order_no }}</td>
            <td> {{ formatted_date($booking->booking_date) }}</td>
            <td>{{ $booking->order->party->name ?? '' }}</td>
            <td>{{ $booking->order->party->type ?? '' }}</td>
            <td>{{ $booking->composition }}</td>
            <td>
                @if(file_exists(public_path($booking->order->image)))
                    <img class="table-img" src="{{ asset($booking->order->image) }}">
                @endif
            </td>
            <td>{{ $booking->meta['process_loss'] ?? null }}</td>
            <td>{{ $booking->meta['other_fabric'] ?? null }}</td>
            <td>{{ $booking->meta['rib'] ?? null }}</td>
            <td>{{ $booking->meta['collar'] ?? null }}</td>
            <td>{{ $booking->order->merchandiser->name ?? '' }}</td>
            <td>
                <div class="{{ $booking->status == 1 ? 'badge bg-warning' : ($booking->status == 2 ? 'badge bg-primary' : 'badge bg-danger') }}" title="{{ $booking->reason }}">
                    {{ $booking->status == 1 ? 'Pending' : ($booking->status == 2 ? 'Approved' : 'Reject') }}
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
