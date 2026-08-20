@php
    $months = getMonths();
@endphp

@foreach ($expense_data as $key => $expense)
    <tr>
        <td>{{ $key }}</td>
        @foreach ($months as $month)
            <td>{{ currency_format(collect($expense)->firstWhere('month', $month)['total_amount'] ?? 0) }}</td>
        @endforeach
        <td>
            {{ currency_format(collect($expense)->pluck('total_amount')->sum()) }}
        </td>
    </tr>
@endforeach
<tr>
    <td>Others Expenses</td>
    @foreach ($months as $month)
        <td>{{ currency_format(collect($others_expense)->firstWhere('month', $month)['total_amount'] ?? 0) }}</td>
    @endforeach
    <td class="fw-bold">{{ currency_format($others_expense->sum('total_amount')) }}</td>
</tr>
<tr class="loss-profit-custom-color2">
    <td><strong>Total Expenses</strong></td>
    @foreach ($months as $month)
        <td class="fw-bold text-dark">{{ currency_format((collect($others_expense)->firstWhere('month', $month)['total_amount'] ?? 0) + ($monthly_totals[$month] ?? 0)) }}</td>
    @endforeach
    <td class="fw-bold text-dark">{{ currency_format(array_sum($monthly_totals->toArray()) + $others_expense->sum('total_amount')) }}</td>
</tr>