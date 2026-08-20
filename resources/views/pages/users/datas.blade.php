@foreach($users as $user)
    <tr>
        <td>{{ $loop->index+1 }} <i class="{{ request('id') == $user->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->phone }}</td>
        <td>{{ $user->email }}</td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('users-update')
                    <li><a  href="{{ route('users.edit',[$user->id, 'users' => $user->role]) }}"><i class="fal fa-pencil-alt"></i>{{('Edit')}}</a></li>
                    @endcan
                    @can('users-delete')
                    <li>
                        <a href="{{ route('users.destroy', $user->id) }}" class="confirm-action" data-method="DELETE">
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
