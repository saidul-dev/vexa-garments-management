@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ ucfirst(request('parties') ?? request('parties-type')) . __(' List')}}

@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{ __('Party name') }}</th>
            <th>{{ __('Phone') }}</th>
            <th>{{ __('country') }}</th>
            <th>{{ __('Total Bill') }}</th>
            <th>{{ __('Advance Amount') }}</th>
            <th>{{ __('Pay Amount') }}</th>
            <th>{{ __('Due Amount') }}</th>
            <th>{{ __('Balance') }}</th>
            <th>{{ __('Remarks') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $party)
            <tr>
                <td>{{ $party->name }}</td>
                <td>{{ optional($party->user)->phone }}</td>
                <td>{{ $party->user->country ?? '' }}</td>
                <td><b>{{ currency_format($party->total_bill) }}</b></td>

                <td><b class="text-success">{{ currency_format($party->advance_amount) }}</b></td>

                <td><b>{{ currency_format($party->pay_amount) }}</b></td>

                <td><b>{{ currency_format($party->due_amount) }}</b></td>

                <td><b>{{ currency_format($party->balance) }}</b></td>

                <td>{{ $party->remarks }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
