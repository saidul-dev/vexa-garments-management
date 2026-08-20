@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Credit Voucher') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>SL.</th>
            <th>Bill No.</th>
            <th>Voucher no</th>
            <th>Transaction By</th>
            <th>Party</th>
            <th>Date</th>
            <th>Prev balance</th>
            <th>Current balance</th>
            <th>Payment Type</th>
            <th>Payment Method</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($datas as $voucher)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $voucher->bill_no }}</td>
                <td>{{ $voucher->voucher_no ?? optional($voucher->income)->category_name }}</td>
                <td>{{ optional($voucher->user)->name }}</td>
                <td>{{ optional($voucher->party)->name ?? 'Others' }}</td>
                <td>{{ formatted_date($voucher->date) }}</td>
                <td><b>{{ currency_format($voucher->prev_balance) }}</b></td>
                <td><b>{{ currency_format($voucher->current_balance) }}</b></td>
                <td>
                    @if ($voucher->bill_type == 'due_bill')
                        <div class="badge bg-success">
                            Due Bill
                        </div>
                    @elseif ($voucher->bill_type == 'advance_payment')
                        <div class="badge bg-primary">
                            Advance Payment
                        </div>
                    @elseif ($voucher->bill_type == 'balance_withdraw')
                        <div class="badge bg-warning">
                            Balance Withdraw
                        </div>
                    @endif
                </td>
                <td>
                    @if ($voucher->payment_method == 'cheque')
                        <div class="badge bg-warning">
                            Cheque
                        </div>
                    @elseif ($voucher->payment_method == 'cash')
                        <div class="badge bg-success">
                            Cash
                        </div>
                    @elseif ($voucher->payment_method == 'bank')
                        <div class="badge bg-primary">
                            Bank
                        </div>
                    @elseif ($voucher->payment_method == 'party_balance')
                        <div class="badge bg-danger">
                            Wallet
                        </div>
                    @endif
                </td>
                <td><b class="text-{{ $voucher->bill_type == 'balance_withdraw' ? 'danger':'success' }}">{{ $voucher->bill_type == 'balance_withdraw' ? '-':'+' }}{{ currency_format($voucher->amount) }}</b></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
