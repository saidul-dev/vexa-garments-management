<div class="modal fade" id="adjust-cash">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{__('Adjust Cash')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body order-form-section">
                <form action="{{ route('cashes.index') }}" method="post" class="ajaxform">
                    @csrf
                    <div class="add-suplier-modal-wrapper">
                        <div class="row">
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Adjustment')}}</label>
                                <select name="bank_id" class="form-control w-100 table-select" required>
                                    <option value="petty_cash" selected>{{__('Adjust Cash')}}</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" >{{ $bank->bank_name }} ({{ $bank->account_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Type')}}</label>
                                <select name="type" class="form-control w-100 form-control" required>
                                    <option value="">{{__('Select Type')}}</option>
                                    <option value="debit">{{__('Debit')}}</option>
                                    <option value="credit">{{__('Credit')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Enter Amount')}}</label>
                                <input type="number" step="any" name="amount" class="form-control amount" placeholder="50,000.00" required>
                            </div>
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Note')}}</label>
                                <input type="text" name="cash_type" class="form-control" placeholder="Main cash / Petty cash" required>
                            </div>
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Adjustment Date')}}</label>
                                <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" class="form-control" placeholder="Adjustment Date" required>
                            </div>
                            <div class="col-lg-12 mt-1">
                                <label>{{__('Description')}} </label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="col-lg-12">
                                <div class="button-group text-center mt-3">
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
