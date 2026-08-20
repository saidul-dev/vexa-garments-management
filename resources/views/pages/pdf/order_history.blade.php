@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Order changes history') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Date & Time')}}</th>
            <th>{{__('Order No.')}}</th>
            <th>{{__('Style')}}</th>
            <th>{{__('Party')}}</th>
            <th>{{__('Color')}}</th>
            <th>{{__('Total Qty')}}</th>
            <th>{{__('Total Unit Price')}}</th>
            <th>{{__('Total Value')}}</th>
            <th>{{__('Status')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $history)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date("d F Y - h:i a", strtotime($history->created_at)) }}</td>
                <td>{{ $history->datas['order_no'] ?? '' }}</td>
                <td>
                    @foreach($history->datas['order_details'] ?? '' as $key => $detail)
                        {{ $detail['style'] ?? '' }}@if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>{{ $history->datas['party']['name'] ?? ''  }}</td>
                <td>
                    @foreach($history->datas['order_details'] ?? '' as $key => $detail)
                        {{ $detail['color'] ?? '' }}@if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>
                    <b>
                        {{
                            currency_format(array_reduce($history->datas['order_details'], function ($carry, $item) {
                                return $carry + (int)$item['qty'];
                            }, 0) )
                        }}
                    </b>
                </td>
                <td>
                    <b>
                        {{
                            currency_format(array_reduce($history->datas['order_details'], function ($carry, $item) {
                                return $carry + (int)$item['unit_price'];
                            }, 0))
                        }}
                    </b>
                </td>
                <td>
                    <b>
                        {{
                            currency_format(array_reduce($history->datas['order_details'], function ($carry, $item) {
                                return $carry + (int)$item['total_price'];
                            }, 0))
                        }}
                    </b>
                </td>
                <td>
                    <div class="{{ $history->datas['status'] ?? 0 == 1 ? 'badge bg-warning' : ($history->datas['status'] ?? 0 == 2 ? 'badge bg-primary' : ($history->datas['status'] ?? 0 == 3 ? 'badge bg-success' : 'badge bg-danger')) }}" title="{{ $history->datas['meta']['reason'] ?? '' }}">
                        {{ $history->datas['status'] ?? 0 == 1 ? 'Pending' : ($history->datas['status'] ?? 0 == 2 ? 'Approved' : ($history->datas['status'] ?? 0 == 3 ? 'Completed' : 'Reject')) }}
                    </div>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection
