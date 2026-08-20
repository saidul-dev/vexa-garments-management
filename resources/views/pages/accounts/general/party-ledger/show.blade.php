@extends('layouts.blank')

@section('main_content')
    <div class="section-container print-wrapper p-0 erp-new-invice">
        <div class="erp-table-section">
            <button class="theme-btn print-btn print-window float-end"><i class="fa fa-print"></i> Print</button>
            <div class="container">
                <div class="table-header justify-content-center border-0 d-block text-center pb-1">
                    <h3 class="text-center"><strong>{{ get_option('company')['name'] ?? '' }}</strong></h3>
                </div>
                <div class="bill-invoice-wrp mt-0">
                    <h2>Party Ledger </h2>
                    <p class="mt-1 fw-bold">{{ $vouchers->isNotEmpty() ? formatted_date($vouchers->first()->date, 'd/m/Y') . ' TO ' . formatted_date($vouchers->last()->date, 'd/m/Y') : '' }}</p>
                </div>
                <div class="table-container">

                    <div class="d-flex flex-wrap">
                        <div>
                            <h5 class="fw-bold">Party name:</h5>
                        </div>
                        <div class="ps-3">
                            <h5 class="fw-bold">{{ ucwords($party->name) }} </h5>
                            <h5>{{ optional($party->user)->phone }} </h5>
                            <h5>{{ ucwords($party->address) }} </h5>
                        </div>
                    </div>
                    <table class="table mt-3 text-center commercial-invoice party-ledger text-start table-bordered paking-detail-table nowrap" id="erp-table">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Transaction Mode</th>
                            <th>A/C Name</th>
                            <th>Narration</th>
                            <th>Chq No.</th>
                            <th>Received By</th>
                            <th>Invoice No.</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($party->opening_balance_type != 'advance_payment')
                        <tr>
                            <td class="text-start">{{ formatted_date($party->created_at, 'd/m/Y') }}</td>
                            <td class="text-start"></td>
                            <td class="text-start">Balance Forword</td>
                            <td class="text-start"></td>
                            <td class="text-start"></td>
                            <td class="text-start"></td>
                            <td class="text-start">N/A</td>
                            <td class="text-start"></td>
                            <td class="fw-bold text-end">{{ $party->opening_balance_type == 'due_bill' ? currency_format($party->opening_balance) : currency_format(0) }}</td>
                            <td class="fw-bold text-end">{{ $party->opening_balance_type != 'due_bill' ? currency_format($party->opening_balance) : currency_format(0) }}</td>
                            <td class="fw-bold text-end">{{ $party->opening_balance_type == 'due_bill' ? currency_format($party->opening_balance) : currency_format(0) }}</td>
                        </tr>
                        @endif

                        @php
                            $totalCredit = 0;
                            $totalDebit = $party->opening_balance_type == 'due_bill' ? $party->opening_balance : 0;
                        @endphp
                        @foreach ($vouchers as $voucher)
                            @php
                                if (in_array($party->type, ['buyer', 'customer'])) {
                                    $totalCredit += $voucher->type == 'credit' ? $voucher->amount : 0;
                                    $totalDebit += in_array($voucher->type, ['debit', 'order_invoice']) ? $voucher->amount : 0;
                                } else {
                                    $totalCredit += $voucher->type == 'debit' ? $voucher->amount : 0;
                                    $totalDebit += in_array($voucher->type, ['credit', 'order_invoice']) ? $voucher->amount : 0;
                                }
                            @endphp
                            <tr>
                                <td class="text-start">{{ formatted_date($voucher->date, 'd/m/Y') }}</td>
                                <td class="text-start">{{ $voucher->type == 'order_invoice' ? 'Sales' : 'Money Receipt' }}</td>
                                <td class="text-start">
                                    @if ($voucher->type == 'order_invoice')
                                        Credit Sales
                                    @elseif (in_array($voucher->payment_method, ['cash', 'bank', 'cheque']))
                                        Received By {{ ucfirst($voucher->payment_method) }}
                                    @elseif ($voucher->payment_method == 'party_balance')
                                        Received From Wallet
                                    @endif
                                </td>
                                <td class="text-start">
                                    @if ($voucher->payment_method == 'cash')
                                        Cash In Hand / Cash
                                    @elseif ($voucher->payment_method == 'bank')
                                        <span class="d-block text-start">{{ optional($voucher->bank)->bank_name }}</span>
                                        <span class="d-block text-start">BANK</span>
                                        <span class="d-block text-start">{{ optional($voucher->bank)->account_number }}</span>
                                    @elseif ($voucher->payment_method == 'cheque')
                                        Cheque
                                    @endif
                                </td>
                                <td class="text-start">{{ $voucher->remarks }}</td>
                                <td class="text-start">{{ $voucher->meta['cheque_no'] ?? '' }}</td>
                                <td class="text-start">{{ $voucher->user->name ?? '' }}</td>
                                <td class="text-start">{{ optional($voucher->income)->category_name ?? optional($voucher->expense)->category_name ?? $voucher->voucher_no ?? $voucher->bill_no }}</td>
                                <td class="fw-bold text-end">{{ in_array($voucher->type, ['debit', 'order_invoice']) ? currency_format($voucher->amount ?? 0) : currency_format(0) }}</td>

                                <td class="fw-bold text-end">{{ $voucher->type == 'credit' ? currency_format($voucher->amount ?? 0) : currency_format(0) }}</td>

                                <td class="fw-bold text-end">
                                    {{ currency_format($totalDebit - $totalCredit) }}
                                </td>
                            </tr>
                            @endforeach

                            <tr>
                                <td colspan="7" class="border-0"></td>
                                <td class="fw-bold">Grand Total:</td>

                                <td class="fw-bold">{{ currency_format($totalDebit) }}</td>

                                <td class="fw-bold">{{ currency_format($totalCredit) }}</td>

                                <td class="fw-bold">
                                    {{ currency_format($totalDebit - $totalCredit) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p class="closing-balance fw-bold">
                        <b>Closing Balance :</b>
                        {{ currency_format($totalDebit - $totalCredit) }}
                    </p>

                    <div class="signature">
                        <p>Accounts Manager</p>
                        <p>Prepared by</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
