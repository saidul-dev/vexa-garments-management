@foreach ($notifications  as $notification)
    <tr>
        <td>{{ $loop->index+1 }}</td>
        <td>{{ $notification->data['message'] ?? '' }}</td>
        <td><span class="text-success">{{ $notification->data['type'] ?? '' }}</span> - <span class="text-red">{{ $notification->data['user'] ?? '' }}</span></td>
        <td>{{ date('d M Y H:i A', strtotime($notification->created_at)) }}</td>
        <td>{{ date('d M Y H:i A', strtotime($notification->read_at)) }}</td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('notifications.mtView', $notification->id) }}"><i class="fas fa-eye"></i> @lang('View')</a>
                    </li>
                    @if (isset($notification->data['type']) && $notification->data['type'] == 'permissions' && $notification->read_at == null)
                        <li>
                            <a href="{{ route('notifications.accept-permissions', $notification->id) }}"  class="confirm-action" data-method="GET"><i class="fas fa-badge-check"></i> @lang('Accept')</a>
                        </li>
                    @endif
                </ul>
            </div>
        </td>
    </tr>
@endforeach
