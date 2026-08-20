@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Expense List') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Category Name')}}</th>
            <th>{{__('Description')}}</th>
            <th>{{__('Status')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $expense)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $expense->category_name }}</td>
                <td>
                    {{ Str::limit(strip_tags($expense->expense_description), 50) }}
                </td>
                <td>{{ $expense->status == 1 ? 'Active' : 'Inactive' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
