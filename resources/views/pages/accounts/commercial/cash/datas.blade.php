@foreach( $cashes as $cash)
    <tr>
        <td>{{ $loop->iteration }} <i class="{{ request('id') == $cash->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td>{{ formatted_date($cash->date) }}</td>
        <td>{{ $cash->bank->bank_name ?? ucfirst(str_replace('_', ' ', $cash->cash_type)) }}</td>
        <td>{{ $cash->bank->holder_name ?? ucfirst(str_replace('_', ' ', $cash->cash_type)) }}</td>
        <td>
            {{ $cash->type == 'credit' ? 'Cash Increase	' : 'Cash Reduce' }}
        </td>
        <td>
            <span class="fw-bold {{ $cash->type == 'credit' ? 'text-success' : 'text-danger' }}">{{ currency_format($cash->amount) }}</span>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#cash-view" class="cash-view-btn" data-bs-toggle="modal" id="view_{{ $cash->id }}"
                           data-id="{{ $cash->id }}" data-date="{{ $cash->date }}"
                           data-account-name="{{ $cash->bank->holder_name ?? ucfirst(str_replace('_', ' ', $cash->cash_type)) }}"
                           data-bank-name="{{ $cash->bank->bank_name ?? ucfirst(str_replace('_', ' ', $cash->cash_type)) }}"
                           data-type=" {{ $cash->type == 'credit' ? 'Cash Increase	' : 'Cash Reduce' }}"
                           data-amount="{{ $cash->amount }}" data-date="{{ $cash->date }}">
                           <i class="fal fa-eye"></i> {{__('View')}} </a></li>
                    <li>
                    @if(check_permission($cash->created_at, 'cashes', $cash->user_id))
                        @if ($cash->bank_id != 'new_party_create')
                            @can('cashes-update')
                            <li>
                                <a href="{{ route('cashes.edit', $cash->id) }}">
                                    <i class="fal fa-pencil-alt"></i>
                                    {{ __('Edit') }}
                                </a>
                            </li>
                            @endcan
                            @can('cashes-delete')
                            <li>
                                <a href="{{ route('cashes.destroy', $cash->id) }}" class="confirm-action" data-method="DELETE">
                                    <i class="fal fa-trash-alt"></i>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                            @endcan
                        @endif
                    @elseif ($cash->user_id == auth()->id())
                        <li>
                            <a href="{{ route('notifications.send-request', ['id' => $cash->id, 'model' => 'Cash']) }}" class="confirm-action" data-method="GET">
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
