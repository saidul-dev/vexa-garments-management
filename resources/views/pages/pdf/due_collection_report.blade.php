@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Sales Invoice Reports') }}
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
        @foreach($datas as $income)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ formatted_date($income->created_at) }}</td>
                <td>{{ $income->party->name ?? '' }}</td>
                <td>{{ $income->party->type ?? '' }}</td>
                <td>{{ $income->category_name }}</td>
                <td><b>{{ currency_format($income->total_bill) }}</b></td>
                <td><b>{{ currency_format($income->total_paid) }}</b></td>
                <td><b>{{ currency_format($income->total_due) }}</b></td>
                <td>
                    <div class="badge bg-{{ $income->status == 1 ? 'primary' : 'danger' }}">
                        {{ $income->status == 1 ? 'Active' : 'Inactive' }}
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
