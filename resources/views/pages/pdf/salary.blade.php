@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Salary List') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{ __('SL.') }}</th>
            <th>{{ __('Employee') }}</th>
            <th>{{ __('Month') }}</th>
            <th>{{ __('Year') }}</th>
            <th>{{ __('Salary Amount') }}</th>
            <th>{{ __('Paid Amount') }}</th>
            <th>{{ __('Due Salary') }}</th>
            <th>{{ __('Payment Method') }}</th>
            <th>{{ __('Pay Date') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $salary)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-dark fw-bold">{{ optional($salary->employee)->name .' - '. optional($salary->employee)->phone }}</td>
                <td>{{ $salary->month }}</td>
                <td>{{ $salary->year }}</td>
                <td class="text-dark fw-bold">{{ currency_format(optional($salary->employee)->salary) }}</td>
                <td class="text-dark fw-bold">{{ currency_format($salary->amount) }}</td>
                <td class="text-dark fw-bold">{{ currency_format($salary->due_salary) }}</td>
                <td>
                    @if ($salary->payment_method == 'cash')
                        Cash
                    @elseif ($salary->payment_method == 'bank')
                        Bank
                    @elseif ($salary->payment_method == 'cheque')
                        Cheque
                    @endif
                </td>
                <td>{{ formatted_date($salary->created_at) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
