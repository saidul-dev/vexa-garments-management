@foreach($employees as $employee)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ formatted_date($employee->join_date) }}</td>
        <td>{{ $employee->name }}</td>
        <td>{{ $employee->phone }}</td>
        <td>{{ optional($employee->designation)->name }}</td>
        <td class="fw-bold text-dark">{{ currency_format($employee->salary) }}</td>
        <td>
            <div class="badge {{ $employee->status == 1 ? 'bg-success' : 'bg-danger' }}">{{ $employee->status == 1 ? 'Active' : 'Deactive' }}</div>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('employees-update')
                    <li>
                        <a href="{{ route('employees.edit', $employee->id) }}" class="edit-btn">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    @endcan
                    @can('employees-delete')
                    <li>
                        <a href="{{ route('employees.destroy', $employee->id) }}" class="confirm-action" data-method="DELETE">
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
