@foreach($transactions as $transaction)
    <tr>
        <td><a href="{{ route('transaction.daily', ['transaction_date' => $transaction->date]) }}" target="_blank" class="text-primary">{{ formatted_date($transaction->date) }}</a></td>
        <td class="fw-bold">{{ $transaction->transaction_times }} Times</td>
        <td class="fw-bold text-dark">{{ currency_format($transaction->total_amount) }}</td>
        <td>{{ ucwords($transaction->all_type)}}</td>
        <td>{{ $transaction->all_remarks }}</td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('transaction.daily', ['transaction_date' => $transaction->date]) }}" target="_blank"><i class="fas fa-print"></i> {{__('Daily Transaction')}}</a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
