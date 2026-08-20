@foreach( $transactions as $transaction )
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ formatted_date($transaction->date) }}</td>
        <td>{{ $transaction->voucher_no ?? $transaction->income->category_name ?? $transaction->bill_no }}</td>
        <td>{{ $transaction->party->name ?? '' }}</td>
        <td>{{ $transaction->party->type ?? '' }}</td>
        <td>
            <div class="{{ $transaction->type == 'credit' ? 'badge bg-success' : 'badge bg-danger' }}">
                {{ ucfirst($transaction->type) }}
            </div>
        </td>
        <td>
            @if ($transaction->payment_method == 'cheque')
                <div class="badge bg-warning">
                    Cheque
                </div>
            @elseif ($transaction->payment_method == 'cash')
                <div class="badge bg-success">
                    Cash
                </div>
            @elseif ($transaction->payment_method == 'bank')
                <div class="badge bg-primary">
                    Bank
                </div>
            @elseif ($transaction->payment_method == 'party_balance')
                <div class="badge bg-danger">
                    Wallet
                </div>
            @endif
        </td>
        <td>
            @if ($transaction->bill_type == 'due_bill')
                <div class="badge bg-success">
                    Due Bill
                </div>
            @elseif ($transaction->bill_type == 'advance_payment')
                <div class="badge bg-primary">
                    Advance Payment
                </div>
            @elseif ($transaction->bill_type == 'balance_withdraw')
                <div class="badge bg-warning">
                    Balance Withdraw
                </div>
            @endif
        </td>
        <td>
            @if($transaction->type == 'credit')
            <b class="text-{{ $transaction->bill_type == 'balance_withdraw' ? 'danger':'success' }}">{{ $transaction->bill_type == 'balance_withdraw' ? '-':'+' }}{{ currency_format($transaction->amount) }}</b>
            @elseif($transaction->type == 'debit')
            <b class="text-danger">{{ $transaction->bill_type == 'party_balance' ? '':'-' }}{{ currency_format($transaction->amount) }}</b>
            @endif
        </td>
        <td>{{ $transaction->user->name ?? '' }}</td>
        <td>{{ $transaction->remarks }}</td>
    </tr>
@endforeach
