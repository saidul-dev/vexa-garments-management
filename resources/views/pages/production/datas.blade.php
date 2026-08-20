@foreach($productions as $key=>$production)
    <tr>
        <td>{{ $loop->iteration }} <i class="{{ request('id') == $production->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td>{{ $production->order->order_no ?? '' }}</td>
        <td>{{ $production->order->party->name ?? '' }}</td>
        <td>{{ $production->order->party->type ?? '' }}</td>
        <td>
            @foreach($production->order_info['style'] ?? [] as $style)
               {{ $style ?? '' }}@if(!$loop->last),@endif
            @endforeach
        </td>
        <td>
            @foreach($production->order_info['item'] ?? [] as $item)
              {{ $item ?? '' }}@if(!$loop->last),@endif
            @endforeach
        </td>
        <td>
            @foreach($production->order_info['color'] ?? [] as $color)
               {{ $color ?? '' }}@if(!$loop->last),@endif
            @endforeach
        </td>
        <td>
            @foreach($production->order_info['qty'] ?? [] as $qty)
                {{ $qty ?? '' }}@if(!$loop->last),@endif
            @endforeach
        </td>
        <td>{{ $production->cutting['daily'] ?? '' }}</td>
        <td>{{ $production->cutting['ttl_cutting'] ?? '' }}</td>
        <td>{{ $production->cutting['balance'] ?? '' }}</td>
        <td>{{ $production->print['today_send'] ?? '' }}</td>
        <td>{{ $production->print['ttl_send'] ?? '' }}</td>
        <td>{{ $production->print['balance'] ?? '' }}</td>
        <td>{{ $production->print['received'] ?? '' }}</td>
        <td>{{ $production->input_line['total'] ?? '' }}</td>
        <td>{{ $production->input_line['balance'] ?? '' }}</td>
        <td>{{ $production->output_line['daily'] ?? '' }}</td>
        <td>{{ $production->output_line['total'] ?? '' }}</td>
        <td>{{ $production->output_line['balance'] ?? '' }}</td>
        <td>{{ $production->finishing['daily_receive'] ?? '' }}</td>
        <td>{{ $production->finishing['total'] ?? '' }}</td>
        <td>{{ $production->finishing['balance'] ?? '' }}</td>
        <td>{{ $production->poly['daily'] ?? '' }}</td>
        <td>{{ $production->poly['total'] ?? '' }}</td>
        <td>{{ $production->poly['balance'] ?? '' }}</td>
        <td>{{ $production->remarks }}</td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @if(check_permission($production->created_at, 'productions', $production->user_id))
                        @can('productions-update')
                        <li>
                            <a href="{{ route('productions.edit', $production->id) }}"><i class="fal fa-pencil-alt"></i>{{('Edit')}}</a>
                        </li>
                        @endcan
                        @can('productions-delete')
                        <li>
                            <a href="{{ route('productions.destroy', $production->id) }}" class="confirm-action" data-method="DELETE">
                                <i class="fal fa-trash-alt"></i>
                                {{ __('Delete') }}
                            </a>
                        </li>
                        @endcan
                    @elseif ($production->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $production->id, 'model' => 'Production']) }}" class="confirm-action" data-method="GET">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('Permission Request') }}
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('productions.show', $production->order_id) }}" target="_blank"><i class="fas fa-print"></i> {{__('Production Report')}}</a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
