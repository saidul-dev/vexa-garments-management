@extends('pages.pdf.pdf_layout')

@section('pdf_title')
    {{ __('Cheque List') }}
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
        <tr>
            <th>{{ __('SL.') }}</th>
            <th>{{ __('Issue Date') }}</th>
            <th>{{ __('Income/Expense') }}</th>
            <th>{{ __('Party Name') }}</th>
            <th>{{ __('Bank Name') }}</th>
            <th>{{ __('Amount') }}</th>
            <th>{{ __('Type') }}</th>
            <th>{{ __('Status') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($datas as $cheque)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ formatted_date($cheque->issue_date) }}</td>
                <td>{{ optional($cheque->voucher->income)->category_name ?? optional($cheque->voucher->expense)->category_name }}</td>
                <td>{{ $cheque->party->name ?? 'Others' }}</td>
                <td>{{ optional($cheque->bank)->bank_name }}</td>
                <td><b>{{ currency_format($cheque->amount) }}</b></td>
                <td>
                    <div class="badge bg-{{ $cheque->type == 'credit' ? 'success':'danger' }}">
                        {{ $cheque->type == 'credit' ? 'Credit':'Debit' }}
                    </div>
                </td>
                <td>
                    <div class="badge bg-{{ $cheque->status == 1 ? 'success':'danger' }}">
                        {{ $cheque->status == 1 ? 'Passed':'Unused' }}
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
