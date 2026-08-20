@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Bank Accounts') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Account Holder Name')}}</th>
            <th>{{__('Bank Name')}}</th>
            <th>{{__('Account Number')}}</th>
            <th>{{__('Branch Name')}}</th>
            <th>{{__('Routing/Swift Number')}}</th>
            <th>{{__('Amount')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $bank)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $bank->holder_name }}</td>
                <td>{{ $bank->bank_name }}</td>
                <td>{{ $bank->account_number  }}</td>
                <td>{{ $bank->branch_name }}</td>
                <td>{{ $bank->routing_number }}</td>
                <td class="fw-bold text-dark">{{ currency_format($bank->balance) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
