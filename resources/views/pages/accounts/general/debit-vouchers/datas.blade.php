@foreach ($vouchers as $voucher)
    <tr>
        <td>{{ $loop->index + 1 }} <i class="{{ request('id') == $voucher->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td><a href="{{ route('invoices.voucher',$voucher->id) }}" target="_blank" class="text-primary">{{ $voucher->bill_no }}</a></td>
        <td>{{ $voucher->voucher_no }}</td>
        <td>{{ optional($voucher->user)->name }}</td>
        <td>{{ optional($voucher->party)->name }}</td>
        <td>{{ optional($voucher->expense)->category_name }}</td>
        <td>{{ formatted_date($voucher->date) }}</td>
        <td>
            @if ($voucher->bill_type == 'due_bill')
            <div class="badge bg-success">
                Due Bill
            </div>
            @elseif ($voucher->bill_type == 'advance_payment')
            <div class="badge bg-primary">
                Advance Payment
            </div>
            @elseif ($voucher->bill_type == 'balance_withdraw')
            <div class="badge bg-warning">
                Balance Withdraw
            </div>
            @endif
        </td>
        <td>
            @if ($voucher->payment_method == 'cheque')
            <div class="badge bg-warning">
                Cheque
            </div>
            @elseif ($voucher->payment_method == 'cash')
            <div class="badge bg-success">
                Cash
            </div>
            @elseif ($voucher->payment_method == 'bank')
            <div class="badge bg-primary">
                Bank
            </div>
            @elseif ($voucher->payment_method == 'party_balance')
            <div class="badge bg-danger">
                Wallet
            </div>
            @endif
        </td>
        <td><b class="text-danger">{{ $voucher->bill_type == 'party_balance' ? '':'-' }}{{ currency_format($voucher->amount) }}</b></td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('invoices.voucher',$voucher->id) }}" target="_blank"><i class="fas fa-print"></i>
                            {{__('Print')}}
                        </a>
                    </li>
                    @if (check_permission($voucher->created_at,'debit-vouchers', $voucher->user_id))
                        @can('debit-vouchers-update')
                        <li>
                            <a href="{{ route('debit-vouchers.edit', $voucher->id) }}" class="edit-btn">
                                <i class="fal fa-pencil-alt"></i>
                                {{ __('Edit') }}
                            </a>
                        </li>
                        @endcan
                        @can('debit-vouchers-delete')
                            <li>
                                <a href="{{ route('debit-vouchers.destroy', $voucher->id) }}" class="confirm-action" data-method="DELETE">
                                    <i class="fal fa-trash-alt"></i>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                    @elseif ($voucher->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $voucher->id, 'model' => 'Voucher']) }}" class="confirm-action" data-method="GET">
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
