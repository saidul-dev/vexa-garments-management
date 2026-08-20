@foreach($histories as $history)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ date("d F Y - h:i a", strtotime($history->created_at)) }}</td>
        <td><a href="{{ route('orders.show', $history->id) }}" target="_blank" class="text-primary">{{ $history->datas['order_no'] ?? '' }}</a></td>
        <td>
            @foreach($history->datas['order_details'] ?? '' as $key => $detail)
                {{ $detail['style'] ?? '' }}@if(!$loop->last),@endif
            @endforeach
        </td>
        <td>{{ $history->datas['party']['name'] ?? ''  }}</td>
        <td>
            @foreach($history->datas['order_details'] ?? '' as $key => $detail)
                {{ $detail['color'] ?? '' }}@if(!$loop->last),@endif
            @endforeach
        </td>
        <td>
            <b>
                {{
                    currency_format(array_reduce($history->datas['order_details'], function ($carry, $item) {
                        return $carry + (int)$item['qty'];
                    }, 0) )
                }}
            </b>
        </td>
        <td>
            <b>
                {{
                    currency_format(array_reduce($history->datas['order_details'], function ($carry, $item) {
                        return $carry + (int)$item['unit_price'];
                    }, 0))
                }}
            </b>
        </td>
        <td>
            <b>
                {{
                    currency_format(array_reduce($history->datas['order_details'], function ($carry, $item) {
                        return $carry + (int)$item['total_price'];
                    }, 0))
                }}
            </b>
        </td>
        <td>
            <div class="{{ $history->datas['status'] ?? 0 == 1 ? 'badge bg-warning' : ($history->datas['status'] ?? 0 == 2 ? 'badge bg-primary' : ($history->datas['status'] ?? 0 == 3 ? 'badge bg-success' : 'badge bg-danger')) }}" title="{{ $history->datas['meta']['reason'] ?? '' }}">
                {{ $history->datas['status'] ?? 0 == 1 ? 'Pending' : ($history->datas['status'] ?? 0 == 2 ? 'Approved' : ($history->datas['status'] ?? 0 == 3 ? 'Completed' : 'Reject')) }}
            </div>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('order.history.print', $history->id) }}" target="_blank"><i class="fas fa-print"></i>
                            {{__('Print')}}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
