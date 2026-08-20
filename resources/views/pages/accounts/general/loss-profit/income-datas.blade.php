@php
    $months = getMonths();
@endphp
@foreach ($income_data as $key => $income)
    <tr>
        <td>{{ $key }}</td>
        @foreach ($months as $month)
            <td>{{ currency_format(collect($income)->firstWhere('month', $month)['total_amount'] ?? 0) }}</td>
        @endforeach
        <td>
            {{ currency_format(collect($income)->pluck('total_amount')->sum()) }}
        </td>
    </tr>
@endforeach
<tr class="loss-profit-custom-color2">
    <td><strong>{{ __('Total Income') }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['January'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['February'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['March'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['April'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['May'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['June'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['July'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['August'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['September'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['October'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['November'] ?? 0) }}</strong></td>
    <td><strong>{{ currency_format($monthly_totals['December'] ?? 0) }}</strong></td>
    <td class="fw-bold">{{ currency_format(array_sum($monthly_totals->toArray())) }}</td>
</tr>
