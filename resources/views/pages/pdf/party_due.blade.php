@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    All {{ ucfirst(request('type')) }} Due List
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>SL.</th>
            <th>Party Name</th>
            <th>Phone</th>
            <th>Total Bill</th>
            <th>Advance Amount</th>
            <th>Pay Amount</th>
            <th>Due Amount</th>
            <th>Remarks</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($datas as $party)
            <tr class="odd">
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $party->name }}</td>
                <td>{{ $party->phone }}</td>
                <td>
                    <b>{{ currency_format($party->total_bill) }}</b>
                </td>
                <td>
                    <p><b class="text-success">{{ currency_format($party->advance_amount) }}</b></p>
                </td>
                <td>
                    <b>{{ currency_format($party->pay_amount) }}</b>
                </td>
                <td>
                    <b>{{ currency_format($party->due_amount) }}</b>
                </td>
                <td>{{ $party->remarks }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
