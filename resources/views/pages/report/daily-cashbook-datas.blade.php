@php
    $total_credit = 0;
    $total_debit = 0;
@endphp
@foreach($vouchers as $voucher)
    @php
        $voucher->type == 'credit' ? $total_credit += $voucher->amount : 0;
        $voucher->type == 'debit' ? $total_debit += $voucher->amount : 0;
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ formatted_date(now()) }}</td>
        <td>{{ $voucher->income->category_name ?? $voucher->expense->category_name ?? '' }}</td>
        <td>{{ $voucher->voucher_no }}</td>

        <td>
            <div class="{{ $voucher->type == 'credit' ? 'badge bg-success' : 'badge bg-danger' }}">
                {{ ucfirst($voucher->type) }}
            </div>
        </td>

        <td class="fw-bold {{ $voucher->type == 'credit' ? 'text-success' : 'text-danger' }}">{{ currency_format($total_credit) }}</td>

        <td>{{ formatted_date($voucher->date) }}</td>

        <td>{{ $voucher->bill_no }}</td>

        <td class="fw-bold {{ $voucher->type == 'credit' ? 'text-success' : 'text-danger' }}">{{ currency_format($total_debit) }}</td>

        <td>{{ currency_format($voucher->bill_amount) }}</td>
        <td>{{ currency_format($voucher->meta['due_bill'] ?? 0) }}</td>
        <td>{{ $voucher->remarks }}</td>
    </tr>
@endforeach
<tr>
    <td class="text-dark fw-bold"></td>
    <td></td>
    <td></td>
    <td></td>
    <td class="text-dark fw-bold">Total:</td>
    <td class="text-dark fw-bold">{{ currency_format($total_credit) }}</td>
    <td></td>
    <td></td>
    <td class="text-dark fw-bold">{{ currency_format($total_debit) }}</td>
    <td></td>
    <td></td>
    <td></td>
</tr>

