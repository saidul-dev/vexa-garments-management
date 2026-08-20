<div class="modal fade" id="add-new-folder">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{__('Add New Folder')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body order-form-section">
                <form action="{{ route('folders.store',['party'=>$party->id]) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                    @csrf
                    <div class="add-suplier-modal-wrapper">
                        <div class="row">
                            <div class="col-lg-12 mt-3">
                                <input type="text" name="name" required class="form-control" placeholder="Enter Folder Name">
                                <input type="hidden" name="parent_id" required value="{{ request('parent_id') }}">
                                <input type="hidden" name="party_id" required value="{{ request('parties') }}">
                            </div>
                            <div class="col-lg-12">
                                <div class="button-group text-center mt-2">
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
<div class="modal fade" id="upload-file">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{__('Upload File')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body order-form-section">
                <form action="{{ route('files-uploads.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                    @csrf
                    <div class="add-suplier-modal-wrapper">
                        <div class="row">
                            <div class="col-lg-12 mt-3">
                                <input type="file" name="name" required class="form-control" placeholder="Enter File Name">
                                <input type="hidden" name="folder_id" required value="{{ request('parent_id') }}">
                                <input type="hidden" name="party_id" required value="{{ request('parties') }}">
                            </div>
                            <div class="col-lg-12">
                                <div class="button-group text-center mt-2">
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
