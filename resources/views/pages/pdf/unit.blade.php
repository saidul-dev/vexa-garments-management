@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Unit List') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Status')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $unit)
            <tr>
                <td>{{ $loop->iteration }}<i class="{{ request('id') == $unit->id ? 'fas fa-bell text-red' : '' }}"></i></td>
                <td>{{ $unit->name }}</td>
                <td>{{ $unit->status == 1 ? 'Active' : 'Inactive' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
