@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ ucfirst(request('type')) }} {{ __('Ledger Details') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{ __('SL.') }}</th>
            <th>{{ __('Party Name') }}</th>
            <th>{{ __('Party Type') }}</th>
            <th>{{ __('Total Bill') }}</th>
            <th>{{ __('Pay Amount') }}</th>
            <th>{{ __('Advance Amount') }}</th>
            <th>{{ __('Due Amount') }}</th>
            <th>{{ __('Balance') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($datas as $party)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $party->name }}</td>
                <td>
                    <div class="badge bg-success">{{ ucfirst($party->type) }}</div>
                </td>
                <td>{{ currency_format($party->total_bill) }}</td>
                <td>{{ currency_format($party->pay_amount) }}</td>
                <td>{{ currency_format($party->advance_amount) }}</td>
                <td>{{ currency_format($party->due_amount) }}</td>
                <td>{{ currency_format($party->balance) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
