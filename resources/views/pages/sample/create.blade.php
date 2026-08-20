<div class="table-header">
    <h4>{{__('Add New Sample')}}</h4>
</div>
<div class="order-form-section">
    <form action="{{ route('samples.store') }}" method="post" enctype="multipart/form-data"
          class="ajaxform_instant_reload">
        @csrf
        <div class="row">
            <div class="col-lg-6 mt-2">
                <label>{{__('Order No.')}}</label>
                <select name="order_id" required class="form-control order-id table-select w-100">
                    <option value="">{{__('Select a Order')}}</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}">{{ $order->order_no }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 mt-2">
                <label>{{__('Consignee')}}</label>
                <input type="text" name="consignee" id="consignee" readonly class="form-control" placeholder="Enter Consignee">
            </div>
            <div class="col-lg-12 table-form-section add-form-table">
                <div class="table-responsive responsive-table mt-4 pb-0">
                    <table class="table table-two daily-production-table-print mw-1000 booking-table" id="erp-table">
                        <thead>
                        <tr>
                            <th rowspan="2"><strong>{{__('Style')}}</strong></th>
                            <th rowspan="2"><strong>{{__('Color')}}</strong></th>
                            <th rowspan="2"><strong>{{__('Item')}}</strong></th>
                            <th rowspan="2"><strong>{{__('Garments QTY')}}</strong></th>
                            <th rowspan="2"><strong>{{__('Sample Type')}}</strong></th>
                            <th colspan="8"><strong>{{__('Size')}}</strong></th>
                        </tr>
                        <tr>
                            <th><input type="text" name="header[size_xs]" value="XS" class="form-control reset-input"></th>
                            <th><input type="text" name="header[size_s]" value="S" class="form-control reset-input"></th>
                            <th><input type="text" name="header[size_m]" value="M" class="form-control reset-input"></th>
                            <th><input type="text" name="header[size_l]" value="L" class="form-control reset-input"></th>
                            <th><input type="text" name="header[size_xl]" value="XL" class="form-control reset-input"></th>
                            <th><input type="text" name="header[size_xxl]" value="XXL" class="form-control reset-input"></th>
                            <th><input type="text" name="header[size_3xl]" value="3XL" class="form-control reset-input"></th>
                            <th><input type="text" name="header[size_4xl]" value="4XL" class="form-control reset-input"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="position-relative">
                            <td><div class="add-btn-one"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                            <td><div class="tr-remove-btn remove-one"><i class="fas fa-trash"></i></div></td>
                        </tr>

                        {{--  Here add duplicate-one tr from js  --}}

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="button-group text-center mt-5">
                    <a href="" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                    <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>

<input type="hidden" id="url" data-model="Sample" value="{{ route('sample.get-order') }}">

