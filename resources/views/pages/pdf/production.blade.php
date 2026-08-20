@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Production List') }}
@endsection

@section('pdf_content')
    <table class="styled-table production-pdf-table">
        <thead>
        <tr>
            <th colspan="8">{{__('order Details')}}</th>
            <th colspan="3">{{__('Cutting')}}</th>
            <th colspan="4">{{__('Print/emb.')}}</th>
            <th colspan="2">{{__('Input')}}</th>
            <th colspan="3">{{__('Sewing')}}</th>
            <th colspan="3">{{__('Finishing')}}</th>
            <th colspan="3">{{__('Poly')}}</th>
            <th colspan="1">Remarks</th>
        </tr>
        <tr>
            <td>{{__('SL.')}}</td>
            <td>{{__('Order No.')}}</td>
            <td>{{__('Party')}}</td>
            <td>{{__('Type')}}</td>
            <td>{{__('Styles')}}</td>
            <td>{{__('Items')}}</td>
            <td>{{__('Colors')}}</td>
            <td>{{__('Order Quantity')}}</td>
            <td>{{__('Today')}}</td>
            <td>{{__('TTL Cutting')}}</td>
            <td>{{__('Balance 4%')}}</td>
            <td>{{__('Today Send')}}</td>
            <td>{{__('TTL Send')}}</td>
            <td>{{__('Balance')}}</td>
            <td>{{__('Received')}}</td>
            <td>{{__('Total Input')}}</td>
            <td>{{__('TTL Input Balance')}}</td>
            <td>{{__('Day Output')}}</td>
            <td>{{__('TTL Output')}}</td>
            <td>{{__('Output Balance')}}</td>
            <td>{{__('Day Finishing Receive')}}</td>
            <td>{{__('Total Finishing')}}</td>
            <td>{{__('Finishing Balance')}}</td>
            <td>{{__('Daily')}}</td>
            <td>{{__('Total')}}</td>
            <td>{{__('Poly Balance')}}</td>
            <td>{{__('Remarks')}}</td>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $key=>$production)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $production->order->order_no ?? '' }}</td>
                <td>{{ $production->order->party->name ?? '' }}</td>
                <td>{{ $production->order->party->type ?? '' }}</td>
                <td>
                    @foreach($production->order_info['style'] ?? [] as $style)
                        {{ $style ?? '' }}@if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>
                    @foreach($production->order_info['item'] ?? [] as $item)
                        {{ $item ?? '' }}@if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>
                    @foreach($production->order_info['color'] ?? [] as $color)
                        {{ $color ?? '' }}@if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>
                    @foreach($production->order_info['qty'] ?? [] as $qty)
                        {{ $qty ?? '' }}@if(!$loop->last),@endif
                    @endforeach
                </td>
                <td>{{ $production->cutting['daily'] ?? '' }}</td>
                <td>{{ $production->cutting['ttl_cutting'] ?? '' }}</td>
                <td>{{ $production->cutting['balance'] ?? '' }}</td>
                <td>{{ $production->print['today_send'] ?? '' }}</td>
                <td>{{ $production->print['ttl_send'] ?? '' }}</td>
                <td>{{ $production->print['balance'] ?? '' }}</td>
                <td>{{ $production->print['received'] ?? '' }}</td>
                <td>{{ $production->input_line['total'] ?? '' }}</td>
                <td>{{ $production->input_line['balance'] ?? '' }}</td>
                <td>{{ $production->output_line['daily'] ?? '' }}</td>
                <td>{{ $production->output_line['total'] ?? '' }}</td>
                <td>{{ $production->output_line['balance'] ?? '' }}</td>
                <td>{{ $production->finishing['daily_receive'] ?? '' }}</td>
                <td>{{ $production->finishing['total'] ?? '' }}</td>
                <td>{{ $production->finishing['balance'] ?? '' }}</td>
                <td>{{ $production->poly['daily'] ?? '' }}</td>
                <td>{{ $production->poly['total'] ?? '' }}</td>
                <td>{{ $production->poly['balance'] ?? '' }}</td>
                <td>{{ $production->remarks }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
