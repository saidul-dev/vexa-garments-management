@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Employee List') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{ __('SL.') }}</th>
            <th>{{ __('Join Date') }}</th>
            <th>{{ __('Full Name') }}</th>
            <th>{{ __('Phone Number') }}</th>
            <th>{{ __('Designation') }}</th>
            <th>{{ __('Salary') }}</th>
            <th>{{ __('Status') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $employee)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ formatted_date($employee->join_date) }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->phone }}</td>
                <td>{{ optional($employee->designation)->name }}</td>
                <td class="fw-bold text-dark">{{ currency_format($employee->salary) }}</td>
                <td>
                    <div class="badge {{ $employee->status == 1 ? 'bg-success' : 'bg-danger' }}">{{ $employee->status == 1 ? 'Active' : 'Deactive' }}</div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
