@extends('layouts.blank')

@section('title')
    {{ __('Invoice') }}
@endsection

@section('main_content')
    <div class="section-container print-wrapper p-0 erp-new-invice">
        <div class="erp-table-section">
            <div class="container p-0">
                <button class="print-window theme-btn print-btn float-end"><i class="fa fa-print"></i> Print</button>
                @include('pages.invoice.header',['title' => __('Payment Receipt')])
                <div class="d-flex justify-content-between">
                    <div>
                        <p><strong>{{ __('Party Name') }}:</strong> {{ ucwords($voucher->party->name ?? '') }}</p>
                        <p><strong>{{ __('Address') }}:</strong> {{ $voucher->party->address ?? '' }}</p>
                        <p><strong>{{ __('Mobile') }}:</strong> {{ $voucher->user->phone ?? '' }}</p>
                        <p><strong>{{ __('Remarks') }}:</strong> {{ $voucher->remarks }}</p>
                    </div>
                    <div>
                        <p><strong>{{ __('Received By') }}:</strong> {{ ucwords($voucher->user->name ?? '') }}</p>
                        <p><strong>{{ __('Payment Method') }}:</strong> {{ ucfirst($voucher->payment_method == 'party_balance' ? 'Wallet' : $voucher->payment_method) }}</p>
                        <p><strong>{{ __('Payment Date') }}:</strong> {{ formatted_date($voucher->created_at, 'd M, Y h:i:s A') }}</p>
                    </div>
                </div>
                <table class="table commercial-invoice text-start table-bordered text-center invoice-two border-0 mt-2" id="erp-table">
                    <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Bill No') }}</th>
                        <th>{{ __('Received Amount') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ formatted_date($voucher->date) }}</td>
                        <td>{{ $voucher->bill_no }}</td>
                        <td class="fw-bold">{{ currency_format($voucher->amount ?? 0) }}</td>
                    </tr>
                    </tbody>
                </table>
                <h5><b>{{ __('Amount in word') }}: </b>{{ ucfirst(amountInWords($voucher->amount) . ' dollar') }}.</h5>
                <div class="signature">
                    <p>{{ __('Client Signature') }}</p>
                    <p>{{ __('Authorized Signature') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
