@foreach($budgets as $budget)
    <tr>
        <td>{{ $loop->iteration }} <i class="{{ request('id') == $budget->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td class="fw-bold"><a href="{{ route('budget.print', $budget->id) }}" target="_blank" class="text-primary">{{ $budget->order->order_no ?? '' }}</a></td>
        <td>{{ $budget->order->party->name ?? '' }}</td>
        <td>{{ $budget->order->party->type ?? '' }}</td>
        <td>{{ $budget->order_info['style'] ?? '' }}</td>
        <td class="fw-bold text-dark">{{ $budget->order_info['qty'] ?? '' }} </td>
        <td class="fw-bold text-dark">{{ currency_format($budget->order_info['unit_price'] ?? '') }} </td>
        <td class="fw-bold text-dark">{{ currency_format($budget->order_info['lc'] ?? '') }} </td>
        <td>
            <span class="{{ $budget->status == 1 ? 'badge bg-warning' : ($budget->status == 2 ? 'badge bg-primary' : ($budget->status == 3 ? 'badge bg-success' : 'badge bg-danger')) }}" title="{{ $budget->meta['reason'] ?? '' }}">
                {{ $budget->status == 1 ? 'Pending' : ($budget->status == 2 ? 'Approved' : 'Reject') }}
            </span>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('budget.print',$budget->id) }}" target="_blank"><i class="fas fa-print"></i> {{__('Print')}}</a></li>
                    @if($budget->status == 1)
                        <li>
                            <a href="javascript:void(0)" action="{{ route('budget.status', ['id' => $budget->id, 'status' => 2]) }}" class="change-status">
                                <i class="far fa-check-circle"></i>
                                {{__('Approved') }}
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" action="{{ route('budget.status', ['id' => $budget->id, 'status' => 0]) }}" class="change-status">
                                <i class="far fa-times-circle"></i>
                                {{__('Reject') }}
                            </a>
                        </li>
                    @endif
                    @if(check_permission($budget->created_at,'budgets', $budget->user_id))
                        @can('budgets-update')
                            <li><a  href="{{ route('budgets.edit', $budget->id) }}"><i class="fal fa-pencil-alt"></i> {{__('Edit')}} </a></li>
                        @endcan
                        @can('budgets-delete')
                        <li>
                            <a href="{{ route('budgets.destroy', $budget->id) }}" class="confirm-action" data-method="DELETE">
                                <i class="fal fa-trash-alt"></i>
                                {{ __('Delete') }}
                            </a>
                        </li>
                        @endcan
                    @elseif ($budget->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $budget->id, 'model' => 'Costbudget']) }}" class="confirm-action" data-method="GET">
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

@push('modal')
@include('pages.components.approve-reject-modal')
@endpush
