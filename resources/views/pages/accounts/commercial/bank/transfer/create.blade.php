<div class="modal fade" id="bank-cash-transfer">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{__('Balance transfer')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body order-form-section">
                <form action="{{ route('transfers.store') }}" method="post" class="ajaxform transfer-create-form">
                    @csrf
                    <div class="add-suplier-modal-wrapper">
                        <div class="row">
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Transfer Type')}}</label>
                                <select name="transfer_type" class="w-100 form-control transfer_type" required>
                                    <option selected value="bank_to_bank" >{{__('Bank to Bank Transfer')}}</option>
                                    <option value="bank_to_cash" >{{__('Bank to Cash Transfer')}}</option>
                                    <option value="cash_to_bank" >{{__('Cash to Bank Transfer')}}</option>
                                    <option value="adjust_bank" >{{__('Adjust Bank Balance')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Date')}}</label>
                                <input type="date" name="date" value="{{ now()->format('Y-m-d') }}"  class="form-control datepicker date" placeholder="Adjustment Date" required>
                            </div>
                            <div class="col-sm-6 mt-2 type d-none">
                                <label>{{__('Adjust Type')}}</label>
                                <select name="adjust_type" class="w-100 form-control adjust_type" required>
                                    <option value="">-{{ __('Select Type') }}-</option>
                                    <option value="deposit">{{ __('Deposit') }}</option>
                                    <option value="withdraw">{{ __('Withdraw') }}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mt-2" id="bank_from">
                                <label>{{__('Bank From')}}</label>
                                <select name="bank_from" class="form-control w-100 bank_from" required>
                                    <option value="">-{{ __('Select Bank') }}-</option>
                                    @foreach($bankInfo as $bank)
                                        <option value="{{ $bank->id }}" >{{ $bank->bank_name }} ({{ $bank->account_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mt-2" id="bank_to">
                                <label>{{__('Bank To')}}</label>
                                <select name="bank_to" class="form-control w-100 bank_to" required>
                                    <option value="">-{{ __('Select Bank') }}-</option>
                                    @foreach($bankInfo as $bank)
                                        <option value="{{ $bank->id }}" >{{ $bank->bank_name }} ({{ $bank->account_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Amount')}}</label>
                                <input type="number" name="amount" required class="form-control amount" placeholder="$5000">
                            </div>
                            <div class="col-lg-6 mt-2 d-none cash_type">
                                <label>{{__('Cash Type')}}</label>
                                <input type="text" name="cash_type" class="form-control cash_type" placeholder="Petty Cash / Main Cash">
                            </div>

                            <div class="col-lg-12 mt-2">
                                <label>{{__('Note')}}</label>
                                <textarea name="note" class="form-control"></textarea>
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
