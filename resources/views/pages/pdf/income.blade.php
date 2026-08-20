@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Income List') }}
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
        @foreach($datas as $income)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $income->category_name }}</td>
                <td>
                    {{ Str::limit(strip_tags($income->income_description),50) }}
                </td>
                <td>{{ $income->status == 1 ? 'Active' : 'Inactive' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
