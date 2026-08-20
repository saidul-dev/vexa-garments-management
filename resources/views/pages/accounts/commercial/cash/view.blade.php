<div class="modal fade" id="cash-view">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{__('Cash View')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body order-form-section loan-view-modal-wrapper">
                <ul class="bank-status-list">
                    <li><span class="w-140">{{__('Account Name')}}</span> <span>:</span> <span id="cash_account_name">	</span></li>
                    <li><span class="w-140">{{__('Bank Name')}}	</span> <span>:</span> <span id="cash_bank_name">	</span></li>
                    <li><span class="w-140">{{__('Type')}}	</span> <span>:</span> <span id="cash_type">	</span></li>
                    <li><span class="w-140">{{__('Amount')}} </span> <span>:</span> <span id="cash_amount"></span></li>
                    <li><span class="w-140">{{__('Date')}} </span> <span>:</span> <span id="cash_date"></span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
