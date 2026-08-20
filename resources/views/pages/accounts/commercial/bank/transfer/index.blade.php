@extends('layouts.master')

@section('title')
    {{__('Balance Transfer')}}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="table-header">
                <h4>{{__('Transfer List')}}</h4>
                <a href="{{ route('banks.index') }}" class="theme-btn print-btn text-light"><i class="fa fa-arrow-left"></i> {{__('Back')}}</a>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="bank-account-info">
                        <div class="row">
                            <div class="col-7">
                                <div class="costing-list">
                                    <ul>
                                        <li><span>Account Name</span> <span>:</span> <span> {{ $bank->holder_name }}</span></li>
                                        <li><span>Bank Name</span> <span>:</span> <span>{{ $bank->bank_name }}</span></li>
                                        <li><span>Account Number</span> <span>:</span> <span>{{ $bank->account_number }}</span></li>
                                        <li><span>Branch</span> <span>:</span> <span>{{ $bank->branch_name }}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-5">
                                <h5 class="text-end">Balance: <span class="text-success">{{ currency_format($bank->balance) }}</span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="responsive-table">
                <table class="table" id="erp-table">
                    <thead>
                    <tr>
                        <th>{{__('SL.')}}</th>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Transfer type')}}</th>
                        <th>{{__('From') }}</th>
                        <th>{{__('To') }}</th>
                        <th>{{__('Amount')}}</th>
                        <th>{{__('Note')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transfers as $transfer)
                        <tr>
                            <td>{{ $loop->index+1 }}<i class="{{ request('bank') != -1 &&  request('id') == $transfer->id ? 'fas fa-bell text-red' : '' }}"></i></td>
                            <td>{{ formatted_date($transfer->date) }}</td>
                            <td>
                                @if ($transfer->transfer_type == 'bank_to_bank')
                                    <div class="badge bg-success">
                                        @lang('Bank to bank')
                                    </div>
                                @elseif ($transfer->transfer_type == 'bank_to_cash')
                                <div class="badge bg-danger">
                                    @lang('Bank to cash')
                                </div>
                                @elseif ($transfer->transfer_type == 'cash_to_bank')
                                <div class="badge bg-primary">
                                    @lang('Cash to bank')
                                </div>
                                @elseif ($transfer->transfer_type == 'adjust_bank')
                                <div class="badge bg-warning">
                                    @lang('Adjust bank')
                                </div>
                                @endif
                                @if ($transfer->adjust_type)
                                <div class="badge bg-{{ $transfer->adjust_type == 'debit' || $transfer->adjust_type == 'withdraw' ? 'danger':'success' }}">
                                    {{ ucfirst($transfer->adjust_type) }}
                                </div>
                                @endif
                            </td>
                            <td class="text-danger">
                                @if($transfer->sender_bank->bank_name ?? '')
                                    {{ $transfer->sender_bank->bank_name }}
                                @else
                                    {{ in_array($transfer->transfer_type, ['bank_to_cash', 'cash_to_bank']) ? 'Cash' : '' }}
                                @endif

                            </td>
                            <td class="text-success">
                                @if($transfer->receiver_bank->bank_name ?? '')
                                    {{ $transfer->receiver_bank->bank_name }}
                                @else
                                    {{ in_array($transfer->transfer_type, ['bank_to_cash', 'cash_to_bank']) ? 'Cash' : '' }}
                                @endif
                            </td>
                            <td class="fw-bold {{ $transfer->bank_from == $bank->id ? 'text-danger':'text-success' }}">
                                {{ currency_format($transfer->amount) }}
                            </td>
                            <td>{{ $transfer->note }}</td>
                            <td>
                                <div class="dropdown table-action">
                                    <button type="button" data-bs-toggle="dropdown">
                                        <i class="far fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if(check_permission($transfer->created_at, 'transfers', $transfer->user_id))
                                            @if (auth()->user()->can('transfers-update'))
                                            <li>
                                                <a  href="{{ route('transfers.edit', $transfer->id) }}">
                                                    <i class="fal fa-pencil-alt"></i>
                                                    {{ __('Edit') }}
                                                </a>
                                            </li>
                                            @endif
                                        @elseif ($transfer->user_id == auth()->id())
                                            <li>
                                                <a href="{{ route('notifications.send-request', ['id' => $transfer->id, 'model' => 'Transfer']) }}" class="confirm-action" data-method="GET">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    {{ __('Permission Request') }}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <nav>
                <ul class="pagination">
                    <li class="page-item">{{ $transfers->links() }}</li>
                </ul>
            </nav>
        </div>
    </div>

@endsection


