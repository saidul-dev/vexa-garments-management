@foreach($orders as $order)
    <tr>
        <td class="text-start">
            <span class="text-primary">{{ $order->order_no }}</span>
            <br>
            <strong>{{ $order->title }}</strong>
        </td>
        <td>{{ formatted_date($order->created_at )}} <br> {{ $order->created_at->format('h:i A') }}</td>
        <td>
            <div class="{{ $order->status == 1 ? 'badge bg-warning' : ($order->status == 2 ? 'badge bg-primary' : ($order->status == 3 ? 'badge bg-success' : 'badge bg-danger')) }}">
                {{ $order->status == 1 ? 'Pending' : ($order->status == 2 ? 'Approved' : ($order->status == 3 ? 'Completed' : 'Reject')) }}
            </div>
        </td>
    </tr>
@endforeach

