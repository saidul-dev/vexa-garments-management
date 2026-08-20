@extends('layouts.blank')

@section('title')
    {{__('Order Details')}}
@endsection

@section('main_content')
    <div class="section-container print-wrapper p-0 ">
        <div class="erp-table-section">
            <div class="container-fluid">
                <button class="print-window theme-btn print-btn float-end"><i class="fa fa-print"></i> Print</button>
                <div class="table-header justify-content-center border-0 d-block text-center">
                    @include('pages.invoice.header2')
                    <h4 class="mt-2">{{__('ORDER DETAILS')}}</h4>
                </div>
                <div class="d-flex align-items-center justify-content-between"><p></p><p class="mx-5"></p><p class="text-end my-2 ml-auto">
                        {{__('Date:')}} {{ formatted_date($order_details[0]->created_at) }}</p></div>
                <div class="col-lg-12 table-form-section">
                    <div class="table-responsive responsive-table mt-4 pb-3 ps-0">
                        <table class="table table-two daily-production-table-print mw-1000" id="erp-table">
                            <thead>
                            <tr>
                                <th><strong>{{__('STYLE')}}</strong></th>
                                <th><strong>{{__('COLOR')}}</strong></th>
                                <th><strong>{{__('ITEM')}}</strong></th>
                                <th><strong>{{__('SHIPMENT DATE')}}</strong></th>
                                <th><strong>{{__('QTY')}}</strong></th>
                                <th><strong>{{__('UNIT PRICE')}}</strong></th>
                                <th><strong>{{__('TTL PRICE')}}</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($order_details as $detail)
                                <tr class="duplicate-one text-center">
                                    <td>{{ $detail->style }}</td>
                                    <td>{{ $detail->color }}</td>
                                    <td>{{ $detail->item }}</td>
                                    <td>{{ $detail->shipment_date }}</td>
                                    <td>{{ $detail->qty }}</td>
                                    <td>{{ $detail->unit_price }}</td>
                                    <td>{{ number_format($detail->total_price, 2, '.', '') }}</td>
                                </tr>
                                @endforeach
                                <tr class="total">
                                    <td colspan="4"><h6 class="text-end">Totals </h6></td>
                                    <td><h6 class="total_qty">{{ $order_details->sum('qty') }}</h6></td>
                                    <td><h6 class="total_unit_price">{{ $order_details->sum('unit_price') }}</h6></td>
                                    <td><h6 class="final_total_price">{{ number_format($order_details->sum('total_price'), 2, '.', '') }}</h6></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
