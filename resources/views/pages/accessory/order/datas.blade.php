@foreach($accessoriesOrder as $order )
    <tr>
        <td>{{ $loop->iteration }} <i class="{{ request('id') == $order->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td>{{ $order->invoice_no }}</td>
        <td>{{ ucwords($order->party->name ?? '') }}</td>
        <td>{{ $order->accessory->name ?? '' }}</td>
        <td>{{ optional($order->accessory->unit)->name ?? '' }}</td>
        <td><b>{{ $order->qty_unit }}</b></td>
        <td><b>{{ currency_format($order->unit_price) }}</b></td>
        <td><b>{{ currency_format($order->ttl_amount) }}</b></td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#details-view" data-bs-toggle="modal" class="view-btn" id="details-view_{{ $order->id }}" data-id="{{ $order->id }}" data-accessories-name="{{ ucwords(optional($order->accessory)->name) }}" data-unit-name="{{ optional(optional($order->accessory)->unit)->name ?? '' }}" data-party-name="{{ optional($order->party)->name }}" data-quantity="{{ $order->qty_unit }}" data-unit-price="{{ currency_format($order->unit_price) }}" data-total-amount="{{ currency_format($order->ttl_amount) }}"><i class="fal fa-eye"></i>{{__('View')}}</a>
                    </li>

                    @if(check_permission($order->created_at, 'accessory-orders', $order->user_id))
                        @can('accessories-update')
                            <li><a  href="{{ route('accessory-orders.edit',$order->id) }}"><i class="fal fa-pencil-alt"></i>{{__('Edit')}}</a></li>
                        @endcan
                        @can('accessories-delete')
                            <li>
                                <a href="{{ route('accessory-orders.destroy', $order->id) }}" class="confirm-action" data-method="DELETE">
                                    <i class="fal fa-trash-alt"></i>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                    @elseif ($order->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $order->id, 'model' => 'AccessoryOrder']) }}" class="confirm-action" data-method="GET">
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
