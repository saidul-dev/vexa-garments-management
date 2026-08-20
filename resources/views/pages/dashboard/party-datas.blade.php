@foreach($parties as $party)
    <tr>
        <td class="text-start">
            <small>{{ optional($party->user)->email ?? '' }}</small> <br>
            <strong>{{ ucwords($party->name) }}</strong>
        </td>
        <td class="text-end">
            <small>Location</small> <br>
            <strong>{{ optional($party->user)->country ?? '' }}</strong>
        </td>
    </tr>
@endforeach


