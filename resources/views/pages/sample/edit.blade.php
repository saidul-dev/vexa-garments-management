@extends('layouts.master')

@section('main_content')
    <div class="container-fluid mt-3">
        <div class="table-header">
            <h4>{{__('Edit Sample')}}</h4>
        </div>
        <div class="order-form-section">
            <form action="{{ route('samples.update',$sample->id) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-lg-6 mt-2">
                        <label>{{__('Order No')}}</label>
                        <select name="order_id" required class="form-control order-id table-select w-100 form-control">
                            <option value="">{{__('Select a Order')}}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected($order->id == $sample->order_id)>{{ $order->order_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label>{{__('Consignee')}}</label>
                        <input type="text" name="consignee" value="{{ $sample->consignee }}" class="form-control" placeholder="Enter Consignee">
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
                                    <th><input type="text" name="header[size_xs]" value="{{ $sample->header['size_xs'] ?? '' }}" class="form-control"></th>
                                    <th><input type="text" name="header[size_s]" value="{{ $sample->header['size_s'] ?? '' }}" class="form-control"></th>
                                    <th><input type="text" name="header[size_m]" value="{{ $sample->header['size_m'] ?? '' }}" class="form-control"></th>
                                    <th><input type="text" name="header[size_l]" value="{{ $sample->header['size_l'] ?? '' }}" class="form-control"></th>
                                    <th><input type="text" name="header[size_xl]" value="{{ $sample->header['size_xl'] ?? '' }}" class="form-control"></th>
                                    <th><input type="text" name="header[size_xxl]" value="{{ $sample->header['size_xxl'] ?? '' }}" class="form-control"></th>
                                    <th><input type="text" name="header[size_3xl]" value="{{ $sample->header['size_3xl'] ?? '' }}" class="form-control"></th>
                                    <th><input type="text" name="header[size_4xl]" value="{{ $sample->header['size_4xl'] ?? '' }}" class="form-control"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="position-relative">
                                    <td><div class="add-btn-one"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                                    <td><div class="tr-remove-btn remove-one"><i class="fas fa-trash"></i></div></td>
                                </tr>

                                @foreach($sample->colors ?? [] as $key=>$color)
                                    <tr class="duplicate-one">
                                        <td><input type="text" name="styles[]" value="{{ $color ?? '' }}" class="form-control clear-input" required placeholder="Style"></td>
                                        <td><input type="text" name="colors[]" value="{{ $sample->colors[$key] ?? '' }}" class="form-control clear-input" placeholder="Color"></td>
                                        <td><input type="text" name="items[]" value="{{ $sample-> items[$key] ?? '' }}" class="form-control clear-input" placeholder="Item description"></td>
                                        <td><input type="number" name="quantities[]" value="{{ $sample-> quantities[$key] ?? '' }}" class="form-control count-length qty {{ $key }}" data-length="{{ $key }}" required placeholder="Qty"></td>
                                        <td><input type="text" name="types[]" value="{{ $sample-> types[$key] ?? '' }}" class="form-control clear-input"></td>

                                        <td><input type="number" name="sizes[xs][]" value="{{ $sample->sizes['xs'][$key] ?? '' }}" class="form-control clear-input"></td>
                                        <td><input type="number" name="sizes[s][]" value="{{ $sample->sizes['s'][$key] ?? '' }}" class="form-control clear-input"></td>
                                        <td><input type="number" name="sizes[m][]" value="{{ $sample->sizes['m'][$key] ?? '' }}" class="form-control clear-input"></td>
                                        <td><input type="number" name="sizes[l][]" value="{{ $sample->sizes['l'][$key] ?? '' }}" class="form-control clear-input"></td>
                                        <td><input type="number" name="sizes[xl][]" value="{{ $sample->sizes['xl'][$key] ?? '' }}" class="form-control clear-input"></td>
                                        <td><input type="number" name="sizes[xxl][]" value="{{ $sample->sizes['xxl'][$key] ?? '' }}" class="form-control clear-input"></td>
                                        <td><input type="number" name="sizes[3xl][]" value="{{ $sample->sizes['3xl'][$key] ?? '' }}" class="form-control clear-input"></td>
                                        <td><input type="number" name="sizes[4xl][]" value="{{ $sample->sizes['4xl'][$key] ?? '' }}" class="form-control clear-input"></td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="button-group text-center mt-5">
                            <a href="{{ route('samples.index') }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                            <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <input type="hidden" id="url" data-model="Sample" value="{{ route('sample.get-order') }}">

@endsection
@push('js')
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
