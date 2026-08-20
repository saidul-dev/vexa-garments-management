@foreach ($parties as $party)
<tr class="odd">
    <td>{{ $loop->index+1 }}</td>
    <td><a href="#" class="text-primary">{{ $party->name }}</a></td>
    <td>{{ $party->phone }}</td>
    <td>
        <b>{{ currency_format($party->total_bill) }}</b>
    </td>
    <td>
        <p><b class="text-success">{{ currency_format($party->advance_amount) }}</b></p>
    </td>
    <td>
        <b>{{ currency_format($party->pay_amount) }}</b>
    </td>
    <td>
        <b>{{ currency_format($party->due_amount) }}</b>
    </td>
    <td>{{ $party->remarks }}</td>
    <td class="print-d-none">
        <div class="dropdown table-action">
            <button type="button" data-bs-toggle="dropdown" aria-expanded="false" class="">
                <i class="far fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu">
                @can('transactions-read')
                    <li>
                        <a target="_blank" href="{{ route('party-ledger.show', $party->id) }}">
                            <i class="fal fa-eye"></i>
                            {{__('Ledger')}}
                        </a>
                    </li>
                @endcan
                @if (in_array(request('type'), ['buyer', 'customer']))
                @canany(['credit-vouchers-read'])
                <li>
                    <a href="#due-collection" class="payment-received" data-id="{{ $party->id }}" data-name="{{ $party->name }}" data-url="{{ route('get-invoices', $party->id) }}" data-bs-toggle="modal">
                        <i class="fab fa-amazon-pay"></i>
                        {{__('Pay Now')}}
                    </a>
                </li>
                @endcanany
                @endif
                @can('parties-update')
                    <li>
                        <a href="{{ route('parties.edit', $party->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{__('Edit')}}
                        </a>
                    </li>
                @endcan
                @can('parties-delete')
                    <li>
                        <a href="{{ route('parties.destroy', $party->id) }}" class="confirm-action" data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
    </td>
</tr>
@endforeach

@push('modal')
<div class="modal fade" id="due-collection">
<div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5">{{__('Payment Received From') }} (<b class="party-name"></b>)</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body order-form-section">
            <form action="{{ route('due-collections.store') }}" method="post" class="ajaxform transfer-create-form">
                @csrf
                <div class="add-suplier-modal-wrapper">
                    <div class="row">
                        <div class="col-sm-6 col-lg-4 mt-2">
                            <label>{{__('Date')}}</label>
                            <input type="hidden" name="party_id" class="party_id">
                            <input type="date" name="date" value="{{ now()->format('Y-m-d') }}"  class="form-control datepicker date" placeholder="Adjustment Date" required>
                        </div>
                        <div class="col-sm-6 col-lg-4 mt-2">
                            <label><span class="invoice_label">Select Invoice</span></label>
                            <div class="input-group">
                                <select name="income_id" class="form-control invoice income_id remove-bg-select">
                                    <option value="">{{ __('Select') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 mt-2">
                            <label>{{__('Payment method')}}</label>
                            <div class="input-group">
                                <select name="payment_method" class="form-control table-select debit_payment_method" required>
                                    <option value="">{{__('Select')}}</option>
                                    <option value="bank">{{__('Bank')}}</option>
                                    <option value="cash">{{__('Cash')}}</option>
                                    <option value="cheque">{{__('Cheque')}}</option>
                                    <option value="party_balance">{{__('Wallet')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 mt-2 cheque_input d-none">
                            <label>{{__('Cheque No')}}</label>
                            <input type="number" name="cheque_no" class="form-control" placeholder="0202982883" required>
                        </div>
                        <div class="col-sm-6 col-lg-4 mt-2 cheque_input d-none">
                            <label>Issue Date</label>
                            <input type="date" name="issue_date" value="{{ now()->format('Y-m-d') }}"  class="form-control datepicker date" required>
                        </div>
                        <div class="col-sm-6 col-lg-4 mt-2 bank_cheque_input d-none">
                            <label>{{ __('Select Bank') }}</label>
                            <select name="bank_id" class="form-control w-100" required>
                                <option value="">-{{ __('Select Bank') }}-</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" >{{ $bank->bank_name .' - '. $bank->account_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-4 mt-2">
                            <label>{{__('Due Amount')}}</label>
                            <input type="number" name="amount" required class="form-control due-amount" readonly placeholder="$5000">
                        </div>
                        <div class="col-sm-6 col-lg-4 mt-2">
                            <label>{{__('Paying Amount')}}</label>
                            <input type="number" name="amount" required class="form-control amount paying-amount" placeholder="$5000">
                        </div>
                        <div class="col-sm-6 col-lg-4 mt-2">
                            <label class="change-lable">{{__('Change Return')}}</label>
                            <input type="number" step="any" class="form-control change-amount" value="{{ currency_format(0) }}" readonly>
                        </div>

                        <div class="col-lg-12 mt-2">
                            <label>{{__('Note')}}</label>
                            <textarea name="remarks" class="form-control"></textarea>
                        </div>
                        <div class="col-lg-12">
                            <div class="button-group text-center mt-5">
                                <button type="reset" class="theme-btn border-btn m-2">{{__('Reset')}}</button>
                                <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endpush

@push('js')
<script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
