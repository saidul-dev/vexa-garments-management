@extends('layouts.blank')

@section('title')
    {{__('Fabric Booking Report')}}
@endsection

@section('main_content')
    <div class="section-container print-wrapper p-0 ">
        <div class="erp-table-section">
            <div class="container-fluid">
                <button class="print-window theme-btn print-btn float-end"><i class="fa fa-print"></i> Print</button>
                <div class="table-header justify-content-center border-0 d-block text-center">
                    @include('pages.invoice.header2')
                    <h4 class="mt-2">{{__('Fabrics Booking')}}</h4>
                </div>
                <div class="responsive-table">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>{{__('Order No')}}</th>
                                <th>{{__('Booking date')}}</th>
                                <th>{{__('Party Name')}}</th>
                                <th>{{__('Composition')}}</th>
                                <th>{{__('Process Loss')}}</th>
                                <th>{{__('Others Fabric')}} </th>
                                <th>{{__('Rib')}}</th>
                                <th>{{__('Collar')}}</th>
                                <th>{{__('Prepared by')}}</th>
                            </tr>
                            <tr>
                                <td>{{ $booking->order->order_no ?? '' }}</td>
                                <td> {{ formatted_date($booking->booking_date) }}</td>
                                <td>{{ $booking->order->party->name ?? '' }}</td>
                                <td>{{ $booking->composition }}</td>
                                <td>{{ $booking->meta['process_loss'] ?? null }}</td>
                                <td>{{ $booking->meta['other_fabric'] ?? null }}</td>
                                <td>{{ $booking->meta['rib'] ?? null }}</td>
                                <td>{{ $booking->meta['collar'] ?? null }}</td>
                                <td>{{ $booking->order->merchandiser->name ?? null }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="print-signature-wrapper mb-2">
                        <p>{{__('Merchandiser')}}</p>
                        <p>{{__('Check By')}}</p>
                        <p>{{__('Managing Director')}}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
