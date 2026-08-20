@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Monthly Transaction') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('Date')}}</th>
            <th>{{__('Prev. Amount')}}</th>
            <th>{{__('Current Amount')}}</th>
            <th>{{__('Type')}}</th>
            <th>{{__('Remarks')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $transaction)
            <tr>
                <td>{{ formatted_date($transaction->date) }}</td>
                <td>{{ currency_format($transaction->total_prev_balance) }}</td>
                <td>{{ currency_format($transaction->total_current_balance) }}</td>
                <td>{{ ucwords($transaction->all_type)}}</td>
                <td>{{ $transaction->all_remarks }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
