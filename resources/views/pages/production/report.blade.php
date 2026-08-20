@extends('layouts.blank')

@section('title')
    {{__('Production Report')}}
@endsection

@section('main_content')
    <div class="section-container print-wrapper p-0 ">
        <div class="erp-table-section">
            <div class="container-fluid">
                <button class="print-window theme-btn print-btn float-lg-end"><i class="fa fa-print"></i> Print</button>
                <div class="table-header justify-content-center border-0 d-block text-center">
                    <h3 class="text-center"><strong>{{ get_option('company')['name'] ?? '' }}</strong></h3>
                    <h4>Daily Production Report</h4>
                </div>
                 <h5 class="mb-lg-2"><b>Date :</b> {{ formatted_date(Now()) }}</h5>
                <div class="responsive-table mt-0">
                    <table class="table table-two daily-production-table-print" id="erp-table">
                        <thead>
                        <tr>
                            <td rowspan="2">{{__('Buyer')}}</td>
                            <td rowspan="2">{{__('Order No.')}}</td>
                            <td rowspan="2">{{__('Item')}}</td>
                            <td rowspan="2">{{__('Color')}}</td>
                            <td rowspan="2">{{__('Order Quantity')}}</td>
                            <td colspan="2">{{__('Cutting Status')}}</td>
                            <td rowspan="2">{{__('Cutting Balance 4%')}}</td>
                            <td colspan="4">{{__('Print/emb/Status')}}</td>
                            <td colspan="3">{{__('Line Input Status')}}</td>
                            <td rowspan="2">{{__('Input Balance')}}</td>
                            <td colspan="2">{{__('Line Output')}}</td>
                            <td rowspan="2">{{__('Sewing Balance')}}</td>
                            <td colspan="2">{{__('Finishing Rcv')}}</td>
                            <td rowspan="2">{{__('Recevd Balance')}}</td>
                            <td colspan="2">{{__('Poly Status')}}</td>
                            <td rowspan="2">{{__('Poly Balance')}}</td>
                            <td rowspan="2">{{__('Remarks')}}</td>
                            <td rowspan="2">{{__('Production Date')}}</td>
                        </tr>
                        <tr>
                            <td>{{__('Daily')}}</td>
                            <td>{{__('Total')}}</td>
                            <td>{{__('Today Send')}}</td>
                            <td>{{__('Tlt Send')}}</td>
                            <td>{{__('Recevd')}}</td>
                            <td>{{__('Balnc')}}</td>
                            <td>{{__('Line')}}</td>
                            <td>{{__('Daily')}}</td>
                            <td>{{__('Total')}}</td>
                            <td>{{__('Daily')}}</td>
                            <td>{{__('Total')}}</td>
                            <td>{{__('Daily')}}</td>
                            <td>{{__('Total')}}</td>
                            <td>{{__('Daily')}}</td>
                            <td>{{__('Total')}}</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $cutting_daily = 0 ; $cutting_total = 0; $cutting_balance = 0; $print_today = 0; $print_ttl = 0; $print_received = 0 ; $print_balance = 0; $input_line_daily = 0; $input_line_total = 0; $input_line_balance = 0; $output_line_daily = 0; $output_line_total = 0; $output_line_balance = 0; $finishing_daily = 0; $finishing_total = 0; $finishing_balance = 0; $packing_daily = 0; $packing_total = 0; $packing_balance = 0;
                        @endphp
                        @foreach($productions as $production)
                            <tr>
                                @if($loop->first)
                                    <td rowspan="{{ $productions->count() }}">{{ $production->order->party->name ?? '' }}</td>
                                    <td rowspan="{{ $productions->count() }}">{{ $production->order->order_no ?? '' }}</td>
                                @endif
                                <td>
                                    @foreach($production->order_info['item'] ?? [] as $item)
                                        {{ $item ?? '' }} <br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($production->order_info['color'] ?? [] as $color)
                                       {{ $color ?? '' }} <br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($production->order_info['qty'] ?? [] as $qty)
                                        {{ $qty ?? '' }} <br>
                                    @endforeach
                                </td>
                                <td>{{ $production->cutting['daily'] ?? '' }}</td>
                                <td>{{ $production->cutting['ttl_cutting'] ?? '' }}</td>
                                <td>{{ $production->cutting['balance'] ?? '' }}</td>
                                <td>{{ $production->print['today_send'] ?? '' }}</td>
                                <td>{{ $production->print['ttl_send'] ?? '' }}</td>
                                <td>{{ $production->print['received'] ?? '' }}</td>
                                <td>{{ $production->print['balance'] ?? '' }}</td>
                                <td>{{ $production->input_line['name'] ?? '' }}</td>
                                <td>{{ $production->input_line['daily'] ?? '' }}</td>
                                <td>{{ $production->input_line['total'] ?? '' }}</td>
                                <td>{{ $production->input_line['balance'] ?? '' }}</td>
                                <td>{{ $production->output_line['daily'] ?? '' }}</td>
                                <td>{{ $production->output_line['total'] ?? '' }}</td>
                                <td>{{ $production->output_line['balance'] ?? '' }}</td>
                                <td>{{ $production->finishing['daily'] ?? '' }}</td>
                                <td>{{ $production->finishing['total'] ?? '' }}</td>
                                <td>{{ $production->finishing['balance'] ?? '' }}</td>
                                <td>{{ $production->poly['daily'] ?? '' }}</td>
                                <td>{{ $production->poly['total'] ?? '' }}</td>
                                <td>{{ $production->poly['balance'] ?? '' }}</td>
                                <td>{{ $production->remarks }}</td>
                                <td>{{ formatted_date($production->created_at) }}</td>
                                @php
                                    $cutting_daily += $production->cutting['daily'] ?? 0;
                                    $cutting_total += $production->cutting['ttl_cutting'] ?? 0;
                                    $cutting_balance += $production->cutting['balance'] ?? 0;
                                    $print_today += $production->print['today_send'] ?? 0;
                                    $print_ttl += $production->print['ttl_send'] ?? 0;
                                    $print_received += $production->print['received'] ?? 0;
                                    $print_balance += $production->print['balance'] ?? 0;
                                    $input_line_daily += $production->input_line['daily'] ?? 0;
                                    $input_line_total += $production->input_line['total'] ?? 0;
                                    $input_line_balance += $production->input_line['balance'] ?? 0;
                                    $output_line_daily += $production->output_line['daily'] ?? 0;
                                    $output_line_total += $production->output_line['total'] ?? 0;
                                    $output_line_balance += $production->output_line['balance'] ?? 0;
                                    $finishing_daily += $production->finishing['daily'] ?? 0;
                                    $finishing_total += $production->finishing['total'] ?? 0;
                                    $finishing_balance += $production->finishing['balance'] ?? 0;
                                    $packing_daily += $production->packing['daily'] ?? 0;
                                    $packing_total += $production->packing['total'] ?? 0;
                                    $packing_balance += $production->packing['balance'] ?? 0;
                                @endphp
                            </tr>
                           @endforeach
                        <tr>
                            <td class="bg-gray" colspan="3">Sub Total</td>
                            <td class="bg-gray" colspan="1"></td>
                            <td class="bg-gray" colspan="1">{{ $productions->sum('order_qty') }}</td>
                            <td class="bg-gray" colspan="1">{{ $cutting_daily }}</td>
                            <td class="bg-gray" colspan="1">{{ $cutting_total }}</td>
                            <td class="bg-gray" colspan="1">{{ $cutting_balance }}</td>
                            <td class="bg-gray" colspan="1">{{ $print_today }}</td>
                            <td class="bg-gray" colspan="1">{{ $print_ttl }}</td>
                            <td class="bg-gray" colspan="1">{{ $print_received }}</td>
                            <td class="bg-gray" colspan="1">{{ $print_balance }}</td>
                            <td class="bg-gray" colspan="1"></td>
                            <td class="bg-gray" colspan="1">{{$input_line_daily }}</td>
                            <td class="bg-gray" colspan="1">{{$input_line_total }}</td>
                            <td class="bg-gray" colspan="1">{{$input_line_balance }}</td>
                            <td class="bg-gray" colspan="1">{{$output_line_daily }}</td>
                            <td class="bg-gray" colspan="1">{{$output_line_total }}</td>
                            <td class="bg-gray" colspan="1">{{$output_line_balance }}</td>
                            <td class="bg-gray" colspan="1">{{$finishing_daily }}</td>
                            <td class="bg-gray" colspan="1">{{$finishing_total }}</td>
                            <td class="bg-gray" colspan="1">{{$finishing_balance }}</td>
                            <td class="bg-gray" colspan="1">{{$packing_daily }}</td>
                            <td class="bg-gray" colspan="1">{{$packing_total }}</td>
                            <td class="bg-gray" colspan="1">{{$packing_balance }}</td>
                            <td class="bg-gray" colspan="1"></td>
                            <td class="bg-gray" colspan="1"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

