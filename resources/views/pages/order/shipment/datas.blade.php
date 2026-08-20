@foreach($shipments as $shipment)
    <tr>
        <td>{{ $loop->iteration }} <i class="{{ request('id') == $shipment->id ? 'fas fa-bell text-red' : '' }}"></i></td>

        <td><a href="{{ route('shipment.print', ['order' => $shipment->order_id, 'invoice' => $shipment->invoice_no]) }}" target="_blank" class="text-primary">{{ $shipment->invoice_no }}</a></td>
        <td><a href="{{ route('shipment.print', ['order' => $shipment->order_id, 'invoice' => 'order']) }}" target="_blank" class="text-primary">{{ $shipment->order->order_no ?? '' }}</a></td>

        <td>
            {{ $shipment->user->name }}
        </td>
        <td>
            <b>{{ $shipment->details_sum_qty }}</b>
        </td>
        <td>
            <b>{{ currency_format($shipment->details_sum_total_cm) }}</b>
        </td>
        <td>
            {{ formatted_date($shipment->created_at, 'd M, Y H:m A') }}
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('shipment.print', ['order' => $shipment->order_id, 'invoice' => $shipment->invoice_no]) }}" target="_blank"><i class="fas fa-print"></i>
                            {{__('Print')}}
                        </a>
                    </li>
                    @if(check_permission($shipment->created_at, 'shipments', $shipment->user_id))
                        @can('shipments-update')
                            <li>
                                <a href="{{ route('shipments.edit', $shipment->id) }}">
                                    <i class="fal fa-pencil-alt"></i>
                                    {{('Edit')}}
                                </a>
                            </li>
                        @endcan
                        @can('shipments-delete')
                            <li>
                                <a href="{{ route('shipments.destroy', $shipment->id) }}" class="confirm-action"
                                   data-method="DELETE">
                                    <i class="fal fa-trash-alt"></i>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                    @elseif ($shipment->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $shipment->id, 'model' => 'Shipment']) }}"
                               class="confirm-action" data-method="GET">
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
