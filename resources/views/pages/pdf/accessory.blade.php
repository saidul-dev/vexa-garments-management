@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Accessory List') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{__('SL.')}}</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Unit')}}</th>
            <th>{{__('Price')}}</th>
            <th>{{__('Description')}}</th>
            <th>{{__('Status')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $accessory)
            <tr>
                <td>{{ $loop->iteration }}<i class="{{ request('id') == $accessory->id ? 'fas fa-bell text-red' : '' }}"></i></td>
                <td>{{ $accessory->name }}</td>
                <td>{{ optional($accessory->unit)->name }}</td>
                <td><b>{{ currency_format($accessory->unit_price) }}</b></td>
                <td>
                    {{ Str::limit(strip_tags($accessory->description), 50) }}
                </td>
                <td>{{ $accessory->status == 1 ? 'Active' : 'Inactive' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
