@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Cash In Hand') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL')}}</th>
            <th>{{__('Date')}}</th>
            <th>{{__('Bank Name / Note')}}</th>
            <th>{{__('Account Name / Note')}}</th>
            <th>{{__('Type')}}</th>
            <th>{{__('Amount')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $cash)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ formatted_date($cash->date) }}</td>
                <td>{{ $cash->bank->bank_name ?? ucfirst(str_replace('_', ' ', $cash->cash_type)) }}</td>
                <td>{{ $cash->bank->holder_name ?? ucfirst(str_replace('_', ' ', $cash->cash_type)) }}</td>
                <td>
                    {{ $cash->type == 'credit' ? 'Cash Increase	' : 'Cash Reduce' }}
                </td>
                <td>
                    <span class="fw-bold {{ $cash->type == 'credit' ? 'text-success' : 'text-danger' }}">{{ currency_format($cash->amount) }}</span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
