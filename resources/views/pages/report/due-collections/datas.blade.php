@foreach($incomes as $income)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ formatted_date($income->created_at) }}</td>
        <td>{{ $income->party->name ?? '' }}</td>
        <td>{{ $income->party->type ?? '' }}</td>
        <td>{{ $income->category_name }}</td>
        <td><b>{{ currency_format($income->total_bill) }}</b></td>
        <td><b>{{ currency_format($income->total_paid) }}</b></td>
        <td><b>{{ currency_format($income->total_due) }}</b></td>
        <td>
            <div class="badge bg-{{ $income->status == 1 ? 'primary' : 'danger' }}">
                {{ $income->status == 1 ? 'Active' : 'Inactive' }}
            </div>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('partial-payment.voucher', ['income_id' => $income->id]) }}" target="_blank"><i class="fas fa-print"></i>
                            {{ __('Partial Payment') }}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
