@foreach($orders as $order)
    <tr>
        <td>{{ $loop->iteration }} <i class="{{ request('id') == $order->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td>{{ formatted_date($order->created_at) }}</td>
        <td>
            <img class="table-img" src="{{ asset($order->image ?? 'assets/images/user-img/3.png') }}" alt="not found">
        </td>
        <td>{{ $order->order_no }}</td>
        <td>{{ $order->party->name ?? ''  }}</td>
        <td>{{ $order->party->type ?? ''  }}</td>
        <td>{{ optional($order->merchandiser)->name }}</td>
        <td>{{ $order->department }}</td>
        <td>{{ $order->gsm }}</td>
        <td>{{ $order->shipment_mode }}</td>
        <td>{{ $order->payment_mode }}</td>
        <td>{{ $order->year }}</td>
        <td>{{ $order->season }}</td>
        <td>{{ $order->status == 1 ? 'Pending' : ($order->status == 2 ? 'Approved' : ($order->status == 3 ? 'Completed' : 'Reject')) }} </td>
    </tr>
@endforeach
