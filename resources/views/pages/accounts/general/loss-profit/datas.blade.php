@php
    $months = getMonths();
@endphp

<div class="responsive-table mt-0">
    <table class="table table-two daily-production-table-print" id="erp-table">
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
                    @if ($r = collect($revenues)->firstWhere('month', $month))
                        <td>{{ $r->total_qty }} Pc</td>
                    @else
                        <td>0 Pc</td>
                    @endif
                @endforeach
                <td class="fw-bold">{{ $revenues->sum('total_qty') }} Pc</td>
            </tr>
            @php
                $cm_in_january = $total_cm_by_month['January'] ?? 0;
                $cm_in_february = $total_cm_by_month['February'] ?? 0;
                $cm_in_march = $total_cm_by_month['March'] ?? 0;
                $cm_in_april = $total_cm_by_month['April'] ?? 0;
                $cm_in_may = $total_cm_by_month['May'] ?? 0;
                $cm_in_june = $total_cm_by_month['June'] ?? 0;
                $cm_in_july = $total_cm_by_month['July'] ?? 0;
                $cm_in_august = $total_cm_by_month['August'] ?? 0;
                $cm_in_september = $total_cm_by_month['September'] ?? 0;
                $cm_in_october = $total_cm_by_month['October'] ?? 0;
                $cm_in_november = $total_cm_by_month['November'] ?? 0;
                $cm_in_december = $total_cm_by_month['December'] ?? 0;
                $totals_cm = array_sum($total_cm_by_month);
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
                $revenues_of_january = $monthly_total_revenues['January'] ?? 0;
                $revenues_of_february = $monthly_total_revenues['February'] ?? 0;
                $revenues_of_march = $monthly_total_revenues['March'] ?? 0;
                $revenues_of_april = $monthly_total_revenues['April'] ?? 0;
                $revenues_of_may = $monthly_total_revenues['May'] ?? 0;
                $revenues_of_june = $monthly_total_revenues['June'] ?? 0;
                $revenues_of_july = $monthly_total_revenues['July'] ?? 0;
                $revenues_of_august = $monthly_total_revenues['August'] ?? 0;
                $revenues_of_september = $monthly_total_revenues['September'] ?? 0;
                $revenues_of_october = $monthly_total_revenues['October'] ?? 0;
                $revenues_of_november = $monthly_total_revenues['November'] ?? 0;
                $revenues_of_december = $monthly_total_revenues['December'] ?? 0;
            @endphp
            <tr>
                <td>Revenue From CM</td>
                <td>{{ currency_format($monthly_total_revenues['January'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['February'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['March'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['April'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['May'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['June'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['July'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['August'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['September'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['October'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['November'] ?? 0) }}</td>
                <td>{{ currency_format($monthly_total_revenues['December'] ?? 0) }}</td>
                <td class="fw-bold">{{ currency_format(array_sum($monthly_total_revenues) - $totals_cm) }}</td>
            </tr>
            @php
                $expenses_of_january = $expenses_by_month['January'] ?? 0;
                $expenses_of_february = $expenses_by_month['February'] ?? 0;
                $expenses_of_march = $expenses_by_month['March'] ?? 0;
                $expenses_of_april = $expenses_by_month['April'] ?? 0;
                $expenses_of_may = $expenses_by_month['May'] ?? 0;
                $expenses_of_june = $expenses_by_month['June'] ?? 0;
                $expenses_of_july = $expenses_by_month['July'] ?? 0;
                $expenses_of_august = $expenses_by_month['August'] ?? 0;
                $expenses_of_september = $expenses_by_month['September'] ?? 0;
                $expenses_of_october = $expenses_by_month['October'] ?? 0;
                $expenses_of_november = $expenses_by_month['November'] ?? 0;
                $expenses_of_december = $expenses_by_month['December'] ?? 0;
                $total_others_expenses = array_sum($expenses_by_month->toArray());
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
                $salaries_of_january = $salaries_by_month['January'] ?? 0;
                $salaries_of_february = $salaries_by_month['February'] ?? 0;
                $salaries_of_march = $salaries_by_month['March'] ?? 0;
                $salaries_of_april = $salaries_by_month['April'] ?? 0;
                $salaries_of_may = $salaries_by_month['May'] ?? 0;
                $salaries_of_june = $salaries_by_month['June'] ?? 0;
                $salaries_of_july = $salaries_by_month['July'] ?? 0;
                $salaries_of_august = $salaries_by_month['August'] ?? 0;
                $salaries_of_september = $salaries_by_month['September'] ?? 0;
                $salaries_of_october = $salaries_by_month['October'] ?? 0;
                $salaries_of_november = $salaries_by_month['November'] ?? 0;
                $salaries_of_december = $salaries_by_month['December'] ?? 0;
                $total_salaries = array_sum($salaries_by_month->toArray());
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
                <td class="fw-bold">{{ currency_format(array_sum($monthly_total_revenues) - ($totals_cm + $total_others_expenses + $total_salaries)) }}</td>
            </tr>
        </tbody>
    </table>
</div>
