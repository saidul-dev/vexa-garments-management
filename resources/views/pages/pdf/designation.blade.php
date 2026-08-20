@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Designation List') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Designation')}}</th>
            <th>{{__('Description')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $designation)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $designation->name }}</td>
                <td>{{ $designation->description }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
