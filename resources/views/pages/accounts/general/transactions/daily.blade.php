@extends('layouts.master')

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="table-header">
                <h4>{{__('Daily Transaction')}}</h4>
                <button class="print-window theme-btn print-btn"><i class="fa fa-print"></i> {{ __('Print') }}</button>
            </div>
            <div class="responsive-table">
                <div class="table-header justify-content-center border-0 d-block text-center print-inner-page">
                    @include('pages.invoice.header2')
                    <h4 class="mt-2">{{__('Daily Transaction')}}</h4>
                </div>
                <div class="total-count-area">
                    <div class="count-item light-blue">
                        <h5>{{ currency_format($total_amount ?? 0) }}</h5>
                        <p>{{__('Total Transactions')}}</p>
                    </div>
                    <div class="count-item light-green">
                        <h5>{{ currency_format($credit_amount ?? 0) }}</h5>
                        <p>{{__('Credit Amount')}}</p>
                    </div>
                    <div class="count-item light-orange">
                        <h5>{{ currency_format($debit_amount ?? 0) }}</h5>
                        <p>{{__('Debit Amount')}}</p>
                    </div>
                </div>

                <table class="table" id="erp-table">
                    <thead>
                    <tr>
                        <th>{{__('SL.')}}</th>
                        <th>{{__('Bill No')}}</th>
                        <th>{{__('Amount')}}</th>
                        <th>{{__('Voucher No.')}}</th>
                        <th>{{__('Receivable / Payment Purpose')}}</th>
                        <th>{{__('Type')}}</th>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Remarks')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach( $transactions as $transaction )
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $transaction->bill_no }}</td>
                            <td class="fw-bold {{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">{{ currency_format($transaction->amount) }}</td>
                            <td>{{ $transaction->voucher_no }}</td>
                            <td>{{ $transaction->income->category_name ?? '' }} {{ $transaction->expense->category_name ?? '' }}</td>
                            <td>
                                <div class="{{ $transaction->type == 'credit' ? 'badge bg-success' : 'badge bg-danger' }}">
                                    {{ ucfirst($transaction->type) }}
                                </div>
                            </td>
                            <td>{{ formatted_date($transaction->date) }}</td>
                            <td>{{ $transaction->remarks }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
