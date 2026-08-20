@foreach($bookings as $booking)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td><a href="{{ route('bookings.show',$booking->id) }}" target="_blank" class="text-primary">{{ $booking->order->order_no }}</a></td>
        <td> {{ formatted_date($booking->booking_date) }}</td>
        <td>{{ $booking->order->party->name ?? '' }}</td>
        <td>{{ $booking->order->party->type ?? '' }}</td>
        <td>{{ $booking->composition }}</td>
        <td>
            <img class="table-img" src="{{ asset( $booking->order->image ?? 'assets/images/user-img/3.png') }}">
        </td>
        <td>{{ $booking->meta['process_loss'] ?? null }}</td>
        <td>{{ $booking->meta['other_fabric'] ?? null }}</td>
        <td>{{ $booking->meta['rib'] ?? null }}</td>
        <td>{{ $booking->meta['collar'] ?? null }}</td>
        <td>{{ $booking->order->merchandiser->name ?? '' }}</td>
        <td>
            <div class="{{ $booking->status == 1 ? 'badge bg-warning' : ($booking->status == 2 ? 'badge bg-primary' : 'badge bg-danger') }}" title="{{ $booking->reason }}">
                {{ $booking->status == 1 ? 'Pending' : ($booking->status == 2 ? 'Approved' : 'Reject') }}
            </div>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action custom-dropdown-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('bookings-status')
                        @if($booking->status == 1)
                            <li>
                                <a href="javascript:void(0)" action="{{ route('booking.status', ['id' => $booking->id, 'status' => 2]) }}" class="change-status">
                                    <i class="far fa-check-circle"></i>
                                    {{__('Approved') }}
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" action="{{ route('booking.status', ['id' => $booking->id, 'status' => 0]) }}" class="change-status">
                                    <i class="far fa-times-circle"></i>
                                    {{__('Reject') }}
                                </a>
                            </li>
                        @endif
                    @endcan
                    @if(check_permission($booking->created_at, 'bookings', $booking->user_id))
                        @can('bookings-update')
                            <li><a  href="{{ route('bookings.edit',$booking->id) }}"><i class="fal fa-pencil-alt"></i>{{__('Edit')}}</a></li>
                        @endcan
                        @can('bookings-delete')
                            <li>
                                <a href="{{ route('bookings.destroy', $booking->id) }}" class="confirm-action"
                                   data-method="DELETE">
                                    <i class="fal fa-trash-alt"></i>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                    @elseif ($booking->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $booking->id, 'model' => 'Booking']) }}" class="confirm-action" data-method="GET">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('Permission Request') }}
                            </a>
                        </li>
                    @endif
                    <li>
                        <a target="_blank" href="{{ route('booking.fabric',$booking->id) }}"><i class="fas fa-print"></i>{{('Fabric')}}</a>
                    </li>
                    <li>
                        <a target="_blank" href="{{ route('booking.collar-cuff',$booking->id) }}"><i class="fas fa-print"></i>{{('Collar And Cuff')}}</a>
                    </li>
                    <li>
                        <a target="_blank" href="{{ route('bookings.show',$booking->id) }}" >
                            <i class="fal fa-print"></i>
                            {{__('Fabric Booking Details')}}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
