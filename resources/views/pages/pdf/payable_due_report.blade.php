@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Purchase Invoice Reports') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Date')}}</th>
            <th>{{__('Party Name')}}</th>
            <th>{{__('Party Type')}}</th>
            <th>{{__('Category Name')}}</th>
            <th>{{__('Total Bill')}}</th>
            <th>{{__('Total Paid')}}</th>
            <th>{{__('Total Due')}}</th>
            <th>{{__('Status')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $expense)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ formatted_date($expense->created_at) }}</td>
                <td>{{ $expense->party->name ?? '' }}</td>
                <td>{{ $expense->party->type ?? '' }}</td>
                <td>{{ $expense->category_name }}</td>
                <td><b>{{ currency_format($expense->total_bill) }}</b></td>
                <td><b>{{ currency_format($expense->total_paid) }}</b></td>
                <td><b>{{ currency_format($expense->total_due) }}</b></td>
                <td>
                    <div class="badge bg-{{ $expense->status == 1 ? 'primary' : 'danger' }}">
                        {{ $expense->status == 1 ? 'Active' : 'Inactive' }}
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
