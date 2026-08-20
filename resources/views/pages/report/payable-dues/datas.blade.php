@foreach($expenses as $expense)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ formatted_date($expense->created_at) }}</td>
        <td>{{ $expense->party->name ?? '' }}</td>
        <td>{{ $expense->party->type ?? '' }}</td>
        <td>{{ $expense->category_name }}</td>
        <td><b>{{ currency_format($expense->total_bill) }}</b></td>
        <td><b>{{ currency_format($expense->total_paid) }}</b></td>
        <td><b>{{ currency_format($expense->total_due) }}</b></td>
        <td>
            <div class="badge bg-{{ $expense->status == 1 ? 'primary' : 'danger' }}">
                {{ $expense->status == 1 ? 'Active' : 'Inactive' }}
            </div>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('partial-payment.voucher', ['expense_id' => $expense->id]) }}" target="_blank"><i class="fas fa-print"></i>
                            {{__('Partial Payment')}}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
