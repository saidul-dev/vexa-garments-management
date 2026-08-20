<div class="modal fade" id="edit-bank">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{__('Edit Bank')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body order-form-section">
                <form id="editForm" action="" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                    @csrf
                    @method('PUT')

                    <div class="add-suplier-modal-wrapper">
                        <div class="row">
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Account Holder Name')}}</label>
                                <input type="text" required name="holder_name" id="holder-name" class="form-control" placeholder="Enter Account Holder Name">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Bank Name')}}</label>
                                <input type="text" required name="bank_name" id="bank-name" class="form-control" placeholder="Enter Bank Name">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Account Number')}}</label>
                                <input type="number" required name="account_number" id="account-number" class="form-control" placeholder="Enter Account Number">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Branch Name')}} </label>
                                <input type="text" name="branch_name" id="branch-name" class="form-control" placeholder=" Enter Branch Name">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Routing/Swift Number')}} </label>
                                <input type="number" name="routing_number" id="routing-number" class="form-control" placeholder="Enter routing number">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Balance')}} </label>
                                <input type="number" step="any" required name="balance" id="balance" class="form-control" placeholder="Enter balance">
                            </div>
                            <div class="col-lg-12">
                                <div class="button-group text-center mt-5">
                                    <button type="reset" class="theme-btn border-btn m-2">{{__('Cancel')}}</button>
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
