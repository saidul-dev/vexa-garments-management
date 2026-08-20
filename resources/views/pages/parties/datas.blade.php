@foreach($parties as $party)
    <tr>
        <td>{{ $party->name }} <i class="{{ request('id') == $party->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td>{{ optional($party->user)->phone }}</td>
        <td>{{ $party->user->country ?? '' }}</td>
       <td><b>{{ currency_format($party->total_bill) }}</b></td>

        <td><b class="text-success">{{ currency_format($party->advance_amount) }}</b></td>

        <td><b>{{ currency_format($party->pay_amount) }}</b></td>

        <td><b>{{ currency_format($party->due_amount) }}</b></td>

        <td><b>{{ currency_format($party->balance) }}</b></td>

        <td>{{ $party->remarks }}</td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#details-view" data-bs-toggle="modal" class="view-btn" id="details-view_{{ $party->id }}" data-id="{{ $party->id }}" data-party-id="{{ $party->id }}" data-name="{{ $party->name }}" data-phone="{{ $party->user->phone }}" data-email="{{ $party->user->email }}" data-address="{{ $party->address }}" data-country="{{ $party->user->country }}" data-total-bill="{{ currency_format($party->total_bill) }}" data-advance-payment="{{ currency_format($party->advance_amount) }}" data-due-payment="{{ currency_format($party->due_amount) }}" data-balance="{{ currency_format($party->balance) }}" data-remarks="{{ $party->remarks }}"><i class="fal fa-eye"></i>{{__('View')}}</a></li>
                    @can('parties-folder')
                    <li>
                        <a href="{{ route('folders.index', ['parties' => $party->id]) }}">
                            <i class="far fa-folder"></i>
                            {{ __('Folders') }}
                        </a>
                    </li>
                    @endcan
                    @can('parties-update')
                    <li>
                        <a href="{{ route('parties.edit', [$party->id, 'parties-type' => request('parties-type') ?? request('parties') ]) }}"><i class="fal fa-pencil-alt"></i>{{('Edit')}}</a>
                    </li>
                    @endcan
                    @can('parties-delete')
                    <li>
                        <a href="{{ route('parties.destroy', $party->id) }}" class="confirm-action" data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </td>
    </tr>
@endforeach