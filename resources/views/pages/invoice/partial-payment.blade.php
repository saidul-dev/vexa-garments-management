@extends('layouts.blank')

@section('title')
    {{ __('Invoice') }}
@endsection

@section('main_content')
    <div class="section-container print-wrapper p-0 erp-new-invice">
        <div class="erp-table-section">
            <button class="print-window theme-btn print-btn float-end"><i class="fa fa-print"></i> Print</button>

            <div class="container p-0">
                @include('pages.invoice.header',['title' => __('Partial Payment')])
            </div>
            <table class="table commercial-invoice text-start table-bordered text-center invoice-two border-0 mt-2"
                   id="erp-table">
                <thead>
                <tr>
                    <th>{{ __('SL.') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Bill No') }}</th>
                    <th>{{ __('Voucher No') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Payment Method') }}</th>
                    <th>{{ __('Received BY') }}</th>
                    <th>{{ __('Remarks') }}</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $amount = 0;
                @endphp
                @forelse($vouchers as $voucher)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ formatted_date($voucher->date) }}</td>
                        <td>{{ $voucher->bill_no }}</td>
                        <td>{{ optional($voucher->income)->category_name ?? optional($voucher->expense)->category_name ?? $voucher->voucher_no ?? $voucher->bill_no }}</td>
                        <td>{{ $voucher->amount  }}</td>
                        <td>
                            @if ($voucher->payment_method == 'cheque')
                                Cheque
                            @elseif ($voucher->payment_method == 'cash')
                                Cash
                            @elseif ($voucher->payment_method == 'bank')
                                Bank
                            @elseif ($voucher->payment_method == 'party_balance')
                                Wallet
                            @endif
                        </td>
                        <td>{{ $voucher->user->name ?? '' }}</td>
                        <td>{{ $voucher->remarks }}</td>
                    </tr>
                    @php
                        $amount += $voucher->amount
                    @endphp
                @empty
                    <tr>
                        <td colspan="8" class="text-danger text-center">No data was found!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            @if($vouchers->isNotEmpty())
                <h5><b>{{ __('Amount in word') }}: </b>{{ amountInWords($amount) . ' dollar' }}.</h5>
            @endif
            <div class="signature">
                <p>{{ __('Client Signature') }}</p>
                <p>{{ __('Authorized Signature') }}</p>
            </div>
        </div>
    </div>
@endsection
