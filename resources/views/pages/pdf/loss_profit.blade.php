@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    Loss / Profit
@endsection

@section('pdf_content')
    @php
        $months = getMonths();
    @endphp
    
    <table class="styled-table">
        <thead>
        <tr>
            <th><strong>Revenue</strong></th>
            @foreach ($months as $month)
                <th><strong>{{ $month }}</strong></th>
            @endforeach
            <th class="fw-bold"><strong>Total</strong></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><strong>Shipment Qty</strong></td>
            @foreach ($months as $month)
                @if ($r = collect($datas['revenues'])->firstWhere('month', $month))
                    <td>{{ $r->total_qty }} Pc</td>
                @else
                    <td>0 Pc</td>
                @endif
            @endforeach
            <td class="fw-bold">{{ $datas['revenues']->sum('total_qty') }} Pc</td>
        </tr>
        <tr class="loss-profit-custom-color1">
            <td><strong>Total Quantity</strong></td>
            <td>{{ $monthly_sales['January'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['February'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['March'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['April'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['May'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['June'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['July'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['August'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['September'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['October'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['November'] ?? 0 }} Pc</td>
            <td>{{ $monthly_sales['December'] ?? 0 }} Pc</td>
            <td class="fw-bold">{{ $datas['revenues']->sum('total_qty') }} Pc</td>
        </tr>
        @php
            $cm_in_january = $datas['total_cm_by_month']['January'] ?? 0;
            $cm_in_february = $datas['total_cm_by_month']['February'] ?? 0;
            $cm_in_march = $datas['total_cm_by_month']['March'] ?? 0;
            $cm_in_april = $datas['total_cm_by_month']['April'] ?? 0;
            $cm_in_may = $datas['total_cm_by_month']['May'] ?? 0;
            $cm_in_june = $datas['total_cm_by_month']['June'] ?? 0;
            $cm_in_july = $datas['total_cm_by_month']['July'] ?? 0;
            $cm_in_august = $datas['total_cm_by_month']['August'] ?? 0;
            $cm_in_september = $datas['total_cm_by_month']['September'] ?? 0;
            $cm_in_october = $datas['total_cm_by_month']['October'] ?? 0;
            $cm_in_november = $datas['total_cm_by_month']['November'] ?? 0;
            $cm_in_december = $datas['total_cm_by_month']['December'] ?? 0;
            $totals_cm = array_sum($datas['total_cm_by_month']);
        @endphp
        <tr>
            <td>Total CM</td>
            <td>{{ currency_format($cm_in_january) }}</td>
            <td>{{ currency_format($cm_in_february) }}</td>
            <td>{{ currency_format($cm_in_march) }}</td>
            <td>{{ currency_format($cm_in_april) }}</td>
            <td>{{ currency_format($cm_in_may) }}</td>
            <td>{{ currency_format($cm_in_june) }}</td>
            <td>{{ currency_format($cm_in_july) }}</td>
            <td>{{ currency_format($cm_in_august) }}</td>
            <td>{{ currency_format($cm_in_september) }}</td>
            <td>{{ currency_format($cm_in_october) }}</td>
            <td>{{ currency_format($cm_in_november) }}</td>
            <td>{{ currency_format($cm_in_december) }}</td>
            <td class="fw-bold">{{ ($totals_cm) }}</td>
        </tr>
        @php
            $revenues_of_january = $datas['monthly_total_revenues']['January'] ?? 0;
            $revenues_of_february = $datas['monthly_total_revenues']['February'] ?? 0;
            $revenues_of_march = $datas['monthly_total_revenues']['March'] ?? 0;
            $revenues_of_april = $datas['monthly_total_revenues']['April'] ?? 0;
            $revenues_of_may = $datas['monthly_total_revenues']['May'] ?? 0;
            $revenues_of_june = $datas['monthly_total_revenues']['June'] ?? 0;
            $revenues_of_july = $datas['monthly_total_revenues']['July'] ?? 0;
            $revenues_of_august = $datas['monthly_total_revenues']['August'] ?? 0;
            $revenues_of_september = $datas['monthly_total_revenues']['September'] ?? 0;
            $revenues_of_october = $datas['monthly_total_revenues']['October'] ?? 0;
            $revenues_of_november = $datas['monthly_total_revenues']['November'] ?? 0;
            $revenues_of_december = $datas['monthly_total_revenues']['December'] ?? 0;
        @endphp
        <tr>
            <td>Revenue From CM</td>
            <td>{{ currency_format($revenues_of_january) }}</td>
            <td>{{ currency_format($revenues_of_february) }}</td>
            <td>{{ currency_format($revenues_of_march) }}</td>
            <td>{{ currency_format($revenues_of_april) }}</td>
            <td>{{ currency_format($revenues_of_may) }}</td>
            <td>{{ currency_format($revenues_of_june) }}</td>
            <td>{{ currency_format($revenues_of_july) }}</td>
            <td>{{ currency_format($revenues_of_august) }}</td>
            <td>{{ currency_format($revenues_of_september) }}</td>
            <td>{{ currency_format($revenues_of_october) }}</td>
            <td>{{ currency_format($revenues_of_november) }}</td>
            <td>{{ currency_format($revenues_of_december) }}</td>
            <td class="fw-bold">{{ currency_format(array_sum($datas['monthly_total_revenues']) - $totals_cm) }}</td>
        </tr>
        @php
            $expenses_of_january = $datas['expenses_by_month']['January'] ?? 0;
            $expenses_of_february = $datas['expenses_by_month']['February'] ?? 0;
            $expenses_of_march = $datas['expenses_by_month']['March'] ?? 0;
            $expenses_of_april = $datas['expenses_by_month']['April'] ?? 0;
            $expenses_of_may = $datas['expenses_by_month']['May'] ?? 0;
            $expenses_of_june = $datas['expenses_by_month']['June'] ?? 0;
            $expenses_of_july = $datas['expenses_by_month']['July'] ?? 0;
            $expenses_of_august = $datas['expenses_by_month']['August'] ?? 0;
            $expenses_of_september = $datas['expenses_by_month']['September'] ?? 0;
            $expenses_of_october = $datas['expenses_by_month']['October'] ?? 0;
            $expenses_of_november = $datas['expenses_by_month']['November'] ?? 0;
            $expenses_of_december = $datas['expenses_by_month']['December'] ?? 0;
            $total_others_expenses = array_sum($datas['expenses_by_month']->toArray());
        @endphp
        <tr>
            <td>Others Expenses</td>
            <td>{{ currency_format($expenses_of_january) }}</td>
            <td>{{ currency_format($expenses_of_february) }}</td>
            <td>{{ currency_format($expenses_of_march) }}</td>
            <td>{{ currency_format($expenses_of_april) }}</td>
            <td>{{ currency_format($expenses_of_may) }}</td>
            <td>{{ currency_format($expenses_of_june) }}</td>
            <td>{{ currency_format($expenses_of_july) }}</td>
            <td>{{ currency_format($expenses_of_august) }}</td>
            <td>{{ currency_format($expenses_of_september) }}</td>
            <td>{{ currency_format($expenses_of_october) }}</td>
            <td>{{ currency_format($expenses_of_november) }}</td>
            <td>{{ currency_format($expenses_of_december) }}</td>
            <td class="fw-bold">{{ currency_format($total_others_expenses) }}</td>
        </tr>
        @php
            $salaries_of_january = $datas['salaries_by_month']['January'] ?? 0;
            $salaries_of_february = $datas['salaries_by_month']['February'] ?? 0;
            $salaries_of_march = $datas['salaries_by_month']['March'] ?? 0;
            $salaries_of_april = $datas['salaries_by_month']['April'] ?? 0;
            $salaries_of_may = $datas['salaries_by_month']['May'] ?? 0;
            $salaries_of_june = $datas['salaries_by_month']['June'] ?? 0;
            $salaries_of_july = $datas['salaries_by_month']['July'] ?? 0;
            $salaries_of_august = $datas['salaries_by_month']['August'] ?? 0;
            $salaries_of_september = $datas['salaries_by_month']['September'] ?? 0;
            $salaries_of_october = $datas['salaries_by_month']['October'] ?? 0;
            $salaries_of_november = $datas['salaries_by_month']['November'] ?? 0;
            $salaries_of_december = $datas['salaries_by_month']['December'] ?? 0;
            $total_salaries = array_sum($datas['salaries_by_month']->toArray());
        @endphp
        <tr>
            <td>Employees Salaries</td>
            <td>{{ currency_format($salaries_of_january) }}</td>
            <td>{{ currency_format($salaries_of_february) }}</td>
            <td>{{ currency_format($salaries_of_march) }}</td>
            <td>{{ currency_format($salaries_of_april) }}</td>
            <td>{{ currency_format($salaries_of_may) }}</td>
            <td>{{ currency_format($salaries_of_june) }}</td>
            <td>{{ currency_format($salaries_of_july) }}</td>
            <td>{{ currency_format($salaries_of_august) }}</td>
            <td>{{ currency_format($salaries_of_september) }}</td>
            <td>{{ currency_format($salaries_of_october) }}</td>
            <td>{{ currency_format($salaries_of_november) }}</td>
            <td>{{ currency_format($salaries_of_december) }}</td>
            <td class="fw-bold">{{ currency_format($total_salaries) }}</td>
        </tr>
        <tr class="loss-profit-custom-color2">
            <td class="fw-bold">Total Revenues</td>
            <td class="fw-bold">{{ currency_format($revenues_of_january - ($cm_in_january + $expenses_of_january + $salaries_of_january)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_february - ($cm_in_february + $expenses_of_february + $salaries_of_february)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_march - ($cm_in_march + $expenses_of_march + $salaries_of_march)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_april - ($cm_in_april + $expenses_of_april + $salaries_of_april)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_may - ($cm_in_may + $expenses_of_may + $salaries_of_may)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_june - ($cm_in_june + $expenses_of_june + $salaries_of_june)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_july - ($cm_in_july + $expenses_of_july + $salaries_of_july)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_august - ($cm_in_august + $expenses_of_august + $salaries_of_august)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_september - ($cm_in_september + $expenses_of_september + $salaries_of_september)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_october - ($cm_in_october + $expenses_of_october + $salaries_of_october)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_november - ($cm_in_november + $expenses_of_november + $salaries_of_november)) }}</td>
            <td class="fw-bold">{{ currency_format($revenues_of_december - ($cm_in_december + $expenses_of_december + $salaries_of_december)) }}</td>
            <td class="fw-bold">{{ currency_format(array_sum($datas['monthly_total_revenues']) - ($totals_cm + $total_others_expenses + $total_salaries)) }}</td>
        </tr>
        </tbody>
    </table>
@endsection
