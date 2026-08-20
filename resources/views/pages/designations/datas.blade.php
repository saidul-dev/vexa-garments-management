@foreach($designations as $designation)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $designation->name }}</td>
        <td>{{ $designation->description }}</td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('designations-update')
                    <li><a href="#edit-designation" class="edit-btn" data-bs-toggle="modal"
                           data-url="{{ url('/designations') }}"
                           id="edit_{{ $designation->id }}" data-id="{{ $designation->id }}"
                           data-name="{{ $designation->name }}"
                           data-description="{{ $designation->description }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    @endcan
                    @can('designations-delete')
                    <li>
                        <a href="{{ route('designations.destroy', $designation->id) }}" class="confirm-action" data-method="DELETE">
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
