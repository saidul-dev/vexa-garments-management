@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ ucfirst(request('role') ?? request('role')) }} {{__('List')}}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Phone')}}</th>
            <th>{{__('User Name')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $user)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->email }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
