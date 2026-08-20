<div class="modal fade" id="create-designation">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            @can('designations-create')
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{__('Add New Designation')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @endcan
            <div class="modal-body order-form-section">
                <form action="{{ route('designations.store') }}" method="post" class="ajaxform">
                    @csrf
                    <div class="add-suplier-modal-wrapper">
                        <div class="row">
                            <div class="col-lg-12 mt-1">
                                <label>{{__('Designation Name *')}}</label>
                                <input type="text" name="name" required class="form-control" placeholder="Enter Designation Name">
                            </div>
                            <div class="col-lg-12 mt-1">
                                <label>{{__('Description')}}</label>
                                <textarea name="description" class="form-control" placeholder="Enter Description"></textarea>
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
