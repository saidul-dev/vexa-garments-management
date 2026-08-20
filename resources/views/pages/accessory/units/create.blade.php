<div class="table-header">
    <h4>{{__('Create New Unit')}}</h4>
</div>
<div class="order-form-section">
    <form action="{{ route('units.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
        @csrf
        <div class="row">
            <div class="col-lg-6 mt-2">
                <label>{{__('Unit Name')}}</label>
                <input type="text" name="name" required class="form-control" placeholder="Unit Name">
            </div>
            <div class="col-lg-12">
                <div class="button-group text-center mt-5">
                    <a href="{{ route('units.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                    <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>

