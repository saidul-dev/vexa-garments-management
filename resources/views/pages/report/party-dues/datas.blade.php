@foreach ($parties as $party)
<tr class="odd">
    <td>{{ $loop->index+1 }}</td>
    @can('transactions-read')
    <td><a target="_blank" href="{{ route('party-ledger.show', $party->id) }}" class="text-primary">{{ $party->name }}</a></td>
    @else
    <td>{{ $party->name }}</td>
    @endcan
    <td>{{ optional($party->user)->phone }}</td>
    <td>{{ $party->address }}</td>
    <td>
        <b>{{ currency_format($party->due_amount) }}</b>
    </td>
    <td>{{ $party->remarks }}</td>
</tr>
@endforeach

@push('js')
<script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
