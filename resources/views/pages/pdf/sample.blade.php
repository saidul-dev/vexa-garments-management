@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Sample List') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Order No.')}}</th>
            <th>{{__('Consignee')}}</th>
            <th>{{__('Style No.')}}</th>
            <th>{{__('Item')}}</th>
            <th>{{__('Sample Type')}}</th>
            <th>{{__('Garments Qty')}}</th>
            <th>{{__('Status')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $sample)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $sample->order->order_no ?? '' }}</td>

                <td>{{ $sample->consignee }}</td>
                <td>
                    @foreach($sample->styles ?? [] as $style)
                        {{ $style }}@if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>
                    @foreach($sample->items ?? [] as $item)
                        {{ $item }}@if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>
                    @foreach($sample->types ?? [] as $type)
                        {{ $type }}@if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>
                    @foreach($sample->quantities ?? [] as $quantity)
                        {{ $quantity }}@if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>
                    <div class="{{ $sample->status == 1 ? 'badge bg-warning' : ($sample->status == 2 ? 'badge bg-primary' : 'badge bg-danger') }}" title="{{ $sample->reason }}">
                        {{ $sample->status == 1 ? 'Pending' : ($sample->status == 2 ? 'Approved' : 'Reject') }}
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
