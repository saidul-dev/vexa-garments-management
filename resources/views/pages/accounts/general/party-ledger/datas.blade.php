@foreach ($parties as $party)
    <tr>
        <td>{{ $loop->index+1 }}</td>
        <td><a href="{{ route('party-ledger.show', $party->id) }}" target="_blank" class="text-primary">{{ $party->name }}</a></td>
        <td>
            <div class="badge bg-success">{{ ucfirst($party->type) }}</div>
        </td>
        <td>{{ currency_format($party->total_bill) }}</td>
        <td>{{ currency_format($party->pay_amount) }}</td>
        <td>{{ currency_format($party->advance_amount) }}</td>
        <td>{{ currency_format($party->due_amount) }}</td>
        <td>{{ currency_format($party->balance) }}</td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('transactions-read')
                    <li>
                        <a target="_blank" href="{{ route('party-ledger.show', $party->id) }}"><i class="fal fa-eye"></i>
                            {{ __('Ledger') }}</a>
                    </li>
                    @endcan
                </ul>
            </div>
        </td>
    </tr>
@endforeach
