@foreach ($cheques as $cheque)
    <tr>
        <td>{{ $loop->index+1 }} <i class="{{ request('id') == $cheque->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td>{{ formatted_date($cheque->issue_date) }}</td>
        <td>{{ optional($cheque->voucher->income)->category_name ?? optional($cheque->voucher->expense)->category_name }}</td>
        <td>{{ $cheque->party->name ?? 'Others' }}</td>
        <td>{{ optional($cheque->bank)->bank_name }}</td>
        <td><b>{{ currency_format($cheque->amount) }}</b></td>
        <td>
            <div class="badge bg-{{ $cheque->type == 'credit' ? 'success':'danger' }}">
                {{ $cheque->type == 'credit' ? 'Credit':'Debit' }}
            </div>
        </td>
        <td>
            <div class="badge bg-{{ $cheque->status == 1 ? 'success':'danger' }}">
                {{ $cheque->status == 1 ? 'Passed':'Unused' }}
            </div>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a data-party_name="{{ optional($cheque->party)->name ?? 'Others' }}"
                           data-cheque_no="{{ $cheque->cheque_no }}"
                           data-issue_date="{{ formatted_date($cheque->issue_date) }}"
                           data-bank_name="{{ optional($cheque->bank)->bank_name }}"
                           data-status="{{ $cheque->status ? 'Passed':'Unused' }}"
                           data-amount="{{ currency_format($cheque->amount) }}"
                           href="javascript:void(0)" class="view-cheque"><i class="fal fa-eye"></i>
                            View
                        </a>
                    </li>
                    @if(check_permission($cheque->created_at, 'cheques', $cheque->user_id) || auth()->user()->can('cheques-list'))
                        @can('cheques-update')
                        @if ($cheque->status == 0)
                        <li>
                            <a href="{{ route('cheques.update', $cheque->id) }}" class="confirm-action" data-method="PUT">
                                <i class="far fa-check-circle"></i>
                                Withdraw
                            </a>
                        </li>
                        @endif
                        @endcan
                        @can('cheques-delete')
                        <li>
                            <a href="{{ route('cheques.destroy', $cheque->id) }}" class="confirm-action" data-method="DELETE">
                                <i class="fal fa-trash-alt"></i>
                                {{ __('Delete') }}
                            </a>
                        </li>
                        @endcan
                    @elseif ($cheque->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $cheque->id, 'model' => 'Cheque']) }}" class="confirm-action" data-method="GET">
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
