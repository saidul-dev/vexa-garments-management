<div class="modal fade" id="pay-salary">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{__('Pay Salary')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body order-form-section">
                <form action="" method="post" class="ajaxform" id="editForm">
                    @csrf
                    @method('put')
                    <div class="add-suplier-modal-wrapper">
                        <div class="row">
                            <div class="col-lg-6 mt-1">
                                <label>{{__('Amount')}}</label>
                                <input type="number" step="any" name="name" id="employee_edit_salary" readonly class="form-control">
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Year')}}</label>
                                <select name="type" required class="table-select form-control w-100">
                                    <option value="">{{__('Select Year')}}</option>
                                    @for ($i = date('Y'); $i >= 2000 ; $i--)
                                    <option value="{{ $i }}" @selected($i == date('Y'))>{{ $i }}</option>
                                   @endfor

                                </select>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label>{{__('Month')}}</label>
                                <select name="month" required class="table-select form-control w-100">
                                    <option value="">{{__('Select Month')}}</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" @selected($i == date('n'))>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
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
