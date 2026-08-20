@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Daily Cash Book') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Date')}}</th>
            <th>{{__('Receivable / Payment Purpose')}}</th>
            <th>{{__('C.V. / D.V. No.')}}</th>
            <th>{{__('Type')}}</th>
            <th>{{__('Credit Amount')}}</th>
            <th>{{__('Bill Date')}}</th>
            <th>{{__('Bill No')}}</th>
            <th>{{__('Debit Amount')}}</th>
            <th>{{__('Bill Amount')}}</th>
            <th>{{__('Due Bill')}}</th>
            <th>{{__('Remarks')}}</th>
        </tr>
        </thead>
        <tbody>
        @php
            $total_credit = 0;
            $total_debit = 0;
        @endphp
        @foreach($datas as $voucher)
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

        </tbody>
    </table>
@endsection
