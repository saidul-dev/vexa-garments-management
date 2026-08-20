@foreach ($costings as $cost)
    <tr>
        <td>{{ $loop->iteration }} <i class="{{ request('id') == $cost->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td><a href="{{ route('costings.show', $cost->id) }}" target="_blank" class="text-primary">{{ $cost->order->order_no ?? '' }}</a></td>
        <td>{{ $cost->order->party->name ?? '' }}</td>
        <td>{{ $cost->order->party->type ?? '' }}</td>
        <td>{{ $cost->order_info['style'] ?? '' }}</td>
        <td>{{ $cost->order_info['qty'] ?? '' }} </td>
        <td>{{ currency_format($cost->order_info['unit_price'] ?? '') }} </td>
        <td>{{ currency_format($cost->order_info['lc'] ?? '') }} </td>

        <td>
            <div class="{{ $cost->status == 1 ? 'badge bg-warning' : ($cost->status == 2 ? 'badge bg-primary' : 'badge bg-danger') }}" title="{{ $cost->reason }}">
                {{ $cost->status == 1 ? 'Pending' : ($cost->status == 2 ? 'Approved' : 'Reject') }}
            </div>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('costings.show', $cost->id) }}" target="_blank"><i class="fas fa-print"></i>
                            {{ __('Print') }}
                        </a>
                    </li>
                    <li>
                        <a class="view-costing" href="javascript:void(0)" data-url="{{ route('costing.view', $cost->id) }}"><i class="fal fa-eye"></i>
                            {{ __('View') }}
                        </a>
                    </li>
                    @can('costing-status')
                        @if($cost->status == 1)
                            <li>
                                <a href="javascript:void(0)" action="{{ route('costing.status', ['id' => $cost->id, 'status' => 2]) }}" class="change-status">
                                    <i class="far fa-check-circle"></i>
                                    {{__('Approved') }}
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" action="{{ route('costing.status', ['id' => $cost->id, 'status' => 0]) }}" class="change-status">
                                    <i class="far fa-times-circle"></i>
                                    {{__('Reject') }}
                                </a>
                            </li>
                        @endif
                    @endcan
                    @if(check_permission($cost->created_at, 'costings', $cost->user_id))
                        @can('costings-update')
                            <li>
                                <a href="{{ route('costings.edit', $cost->id) }}"><i class="fal fa-pencil-alt"></i>
                                    {{ __('Edit') }}
                                </a>
                            </li>
                        @endcan
                        @can('costings-delete')
                            <li>
                                <a href="{{ route('costings.destroy', $cost->id) }}" class="confirm-action" data-method="DELETE">
                                    <i class="fal fa-trash-alt"></i>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                    @elseif ($cost->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $cost->id, 'model' => 'Costing']) }}" class="confirm-action" data-method="GET">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('Permission Request') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </td>
    </tr>
@endforeach
