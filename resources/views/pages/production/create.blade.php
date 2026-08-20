<div class="order-form-section">
    <form action="{{ route('productions.store') }}" method="post" class="ajaxform_instant_reload">
        @csrf
        <div class="row">
            <div class="col-lg-6 mt-2">
                <label>{{__('Order No.')}}</label>
                <select name="order_id" required class="form-control table-select w-100 order-id">
                    <option value="">{{ __('Select Order') }}</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}">{{ $order->order_no }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 mt-2 party-name">
                <label>{{__('Party Name')}}</label>
                <input type="text" readonly class="form-control" id="party_name" placeholder="Party Name">
            </div>
            {{-- Added field from js --}}

            <div class="col-lg-12 mt-4">
                <h6>{{__('Cutting')}}</h6>
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('Daily')}}</label>
                <input type="number" step="any" name="cutting[daily]" class="form-control" placeholder="Daily">
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('TTL Cutting')}}</label>
                <input type="number" step="any" name="cutting[ttl_cutting]" class="form-control" placeholder="TTL Cutting">
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('Balance 4%')}}</label>
                <input type="number" step="any" name="cutting[balance]" class="form-control" placeholder="Cutting Balance">
            </div>
            <div class="col-lg-12 mt-4">
                <h6>{{__('Print/Emb.')}}</h6>
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('Today Send')}}</label>
                <input type="number" step="any" name="print[today_send]" class="form-control" placeholder="Today Send">
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('TTL Send')}}</label>
                <input type="number" step="any" name="print[ttl_send]" class="form-control" placeholder="TTL Send">
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('Received')}}</label>
                <input type="number" step="any" name="print[received]" class="form-control" placeholder="Received">
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('Balance')}}</label>
                <input type="number" step="any" name="print[balance]" class="form-control" placeholder="Enter Balance">
            </div>

            <div class="col-lg-12 mt-4">
                <h6>{{__('Input')}}</h6>
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('Line')}}</label>
                <input type="text" name="input_line[name]" class="form-control" placeholder="Name">
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('Input Daily')}}</label>
                <input type="number" step="any" name="input_line[daily]" class="form-control" placeholder="Input Daily">
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('Total Input')}}</label>
                <input type="number" step="any" name="input_line[total]" class="form-control" placeholder="Total Input">
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('TTL Input Balance')}}</label>
                <input type="number" step="any" name="input_line[balance]" class="form-control" placeholder="TTL Input Balance">
            </div>
            <div class="col-lg-12 mt-4">
                <h6>{{__('Output/Sewing')}}</h6>
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('Daily Output')}}</label>
                <input type="number" step="any" name="output_line[daily]" class="form-control" placeholder="Daily Output">
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('TTL Output')}}</label>
                <input type="number" step="any" name="output_line[total]" class="form-control" placeholder="TTL Output">
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('Sewing Balance')}}</label>
                <input type="number" step="any" name="output_line[balance]" class="form-control" placeholder="Sewing Balance">
            </div>
            <div class="col-lg-12 mt-4">
                <h6>{{__('Finishing')}}</h6>
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('Daily Finishing Receive')}}</label>
                <input type="number" step="any" name="finishing[daily_receive]" class="form-control" placeholder="Daily Finishing Receive">
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('Total Finishing')}}</label>
                <input type="number" step="any" name="finishing[total]" class="form-control" placeholder="Total Finishing">
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('Finishing Balance')}}</label>
                <input type="number" step="any" name=finishing[balance]" class="form-control" placeholder="Finishing Balance">
            </div>
            <div class="col-lg-12 mt-4">
                <h6>{{__('Poly Status')}}</h6>
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('Daily')}}</label>
                <input type="number" step="any" name="poly[daily]" class="form-control" placeholder="Daily">
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('Total')}}</label>
                <input type="number" step="any" name="poly[total]" class="form-control" placeholder="Total">
            </div>
            <div class="col-lg-4 mt-2">
                <label>{{__('Poly Balance')}}</label>
                <input type="number" step="any" name="poly[balance]" class="form-control" placeholder="Poly Balance">
            </div>
            <div class="col-lg-6 mt-2">
                <label>{{__('Remarks')}}</label>
                <input type="text" name="remarks" class="form-control" placeholder="Remarks">
            </div>
            <div class="col-lg-12">
                <div class="button-group text-center mt-5">
                    <a href="{{ route('productions.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                    <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>

