@foreach($salaries as $salary)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td class="text-dark fw-bold">{{ optional($salary->employee)->name .' - '. optional($salary->employee)->phone }}</td>
        <td>{{ $salary->month }}</td>
        <td>{{ $salary->year }}</td>
        <td class="text-dark fw-bold">{{ currency_format(optional($salary->employee)->salary) }}</td>
        <td class="text-dark fw-bold">{{ currency_format($salary->amount) }}</td>
        <td class="text-dark fw-bold">{{ currency_format($salary->due_salary) }}</td>
        <td>
            @if ($salary->payment_method == 'cash')
                Cash
            @elseif ($salary->payment_method == 'bank')
                Bank
            @elseif ($salary->payment_method == 'cheque')
                Cheque
            @endif
        </td>
        <td>{{ formatted_date($salary->created_at) }}</td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('salaries-update')
                    <li>
                        <a href="{{ route('salaries.edit', $salary->id) }}" class="edit-btn">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    @endcan
                    @can('salaries-delete')
                    <li>
                        <a href="{{ route('salaries.destroy', $salary->id) }}" class="confirm-action" data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </td>
    </tr>
@endforeach
