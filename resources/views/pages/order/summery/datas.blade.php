@foreach($orders as $order)
    <tr>
        <td>{{ $loop->iteration }} <i class="{{ request('id') == $order->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td><a href="{{ route('orders.show',$order->id) }}" target="_blank" class="text-primary">{{ $order->order_no }}</a></td>
        <td>
            <img class="table-img" src="{{ asset($order->image ?? 'assets/images/user-img/3.png') }}" alt="not found">
        </td>
        <td>{{ $order->party->name ?? ''  }}</td>
        <td>{{ optional($order->merchandiser)->name }}</td>
        <td>{{ $order->gsm }}</td>
        <td>{{ $order->shipment_mode }}</td>
        <td>{{ $order->payment_mode }}</td>
        <td>{{ $order->year }}</td>
        <td>{{ $order->season }}</td>
        <td><b>{{ $order->orderDetails->sum('qty') ?? 0 }}</b></td>
        <td><b>{{ currency_format($order->orderDetails->sum('unit_price') ?? 0) }}</b></td>
        <td><b>{{ currency_format($order->orderDetails->sum('total_price') ?? 0) }}</b></td>
        <td>
            <div class="{{ $order->status == 1 ? 'badge bg-warning' : ($order->status == 2 ? 'badge bg-primary' : ($order->status == 3 ? 'badge bg-success' : 'badge bg-danger')) }}" title="{{ $order->meta['reason'] ?? '' }}">
                {{ $order->status == 1 ? 'Pending' : ($order->status == 2 ? 'Approved' : ($order->status == 3 ? 'Completed' : 'Reject')) }}
            </div>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action custom-dropdown-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('orders.show',$order->id) }}" target="_blank"><i class="fas fa-print"></i>
                            {{__('Print')}}
                        </a>
                    </li>
                    @can('orders-status')
                        @if($order->status == 1)
                            <li>
                                <a href="javascript:void(0)" action="{{ route('order.status', ['id' => $order->id, 'status' => 2]) }}" class="change-status">
                                    <i class="far fa-check-circle"></i>
                                    {{__('Approved') }}
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" action="{{ route('order.status', ['id' => $order->id, 'status' => 0]) }}" class="change-status">
                                    <i class="far fa-times-circle"></i>
                                    {{__('Reject') }}
                                </a>
                            </li>
                        @endif
                    @endcan
                    @if(check_permission($order->created_at, 'orders', $order->user_id ))
                        @can('orders-update')
                            <li>
                                <a href="{{ route('orders.edit',$order->id) }}"><i class="fal fa-pencil-alt"></i>
                                    {{('Edit')}}
                                </a>
                            </li>
                        @endcan
                        @can('orders-delete')
                            <li>
                                <a href="{{ route('orders.destroy', $order->id) }}" class="confirm-action" data-method="DELETE"><i class="fal fa-trash-alt"></i>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                    @elseif ($order->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $order->id, 'model' => 'Order']) }}" class="confirm-action" data-method="GET">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('Permission Request') }}
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('order.history', $order->id) }}">
                            <i class="fas fa-sort-amount-down"></i>
                            {{ __('Order Summary') }}
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="{{ route('order.details',$order->id) }}">
                            <i class="fas fa-print"></i>
                            {{('Order Details')}}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach

@push('modal')
    @include('pages.components.approve-reject-modal')
@endpush
