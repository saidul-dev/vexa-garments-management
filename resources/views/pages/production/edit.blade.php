@extends('layouts.master')

@section('title')
    {{__('Production Report')}}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="table-header">
                <h4>{{__('Edit Daily Production')}}</h4>
            </div>
            <div class="order-form-section">
                <form action="{{ route('productions.update',$production->id) }}" method="post" class="ajaxform_instant_reload">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Order No.')}}</label>
                            <select name="order_id" required class="form-control table-select w-100 order-id">
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" @selected( $production->order_id == $order->id ) >{{ $order->order_no }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6 mt-2 party-name">
                            <label>{{__('Party Name')}}</label>
                            <input type="text" readonly value="{{ $production->order->party->name ?? '' }}" class="form-control" id="party_name" placeholder="Party Name">
                        </div>
                        @foreach($production->order_info['style'] ?? [] as $key=>$style)

                            <div class="col-lg-12 feature-row duplicate-feature1 sample-form-wrp production-wrap">
                                <button type="button" class="btn text-danger trash remove-btn-features duplicate-feature-remove"><i class="fas fa-trash"></i></button>
                                <div class="grid-4">
                                    <div class="grid-items mt-2 order-data">
                                        <label>{{ __('Style') }}</label>
                                        <input type="text" name="order_info[style][]" value="{{ $style ?? '' }}" readonly class="form-control" placeholder="Style">
                                    </div>
                                    <div class="grid-items mt-2 order-data">
                                        <label>{{ __('Item') }}</label>
                                        <input type="text" name="order_info[item][]" value="{{ $production->order_info['item'][$key] ?? '' }}" readonly class="form-control" placeholder="Item">
                                    </div>
                                    <div class="grid-items mt-2 order-data">
                                        <label>{{__('Colors')}}</label>
                                        <input type="text" name="order_info[color][]" value="{{ $production->order_info['color'][$key] ?? '' }}" readonly class="form-control" placeholder="Color">
                                    </div>
                                    <div class="grid-items mt-2 order-data">
                                        <label>{{__('Order Quantity')}}</label>
                                        <input type="number" name="order_info[qty][]" value="{{ $production->order_info['qty'][$key] ?? '' }}" readonly class="form-control" placeholder="Quantity">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-lg-12 mt-4">
                            <h6>{{__('Cutting')}}</h6>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('Daily')}}</label>
                            <input type="number" step="any" name="cutting[daily]" value="{{ $production->cutting['daily'] ?? '' }}" class="form-control" placeholder="Daily">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('TTL Cutting')}}</label>
                            <input type="number" step="any" name="cutting[ttl_cutting]" value="{{ $production->cutting['ttl_cutting'] ?? '' }}" class="form-control" placeholder="TTL Cutting">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('Balance 4%')}}</label>
                            <input type="number" step="any" name="cutting[balance]" value="{{ $production->cutting['balance'] ?? '' }}" class="form-control" placeholder="Cutting Balance">
                        </div>
                        <div class="col-lg-12 mt-4">
                            <h6>{{__('Print/Emb.')}}</h6>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('Today Send')}}</label>
                            <input type="number" step="any" name="print[today_send]" value="{{ $production->print['today_send'] ?? '' }}" class="form-control" placeholder="Today Send">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('TTL Send')}}</label>
                            <input type="number" step="any" name="print[ttl_send]" value="{{ $production->print['ttl_send'] ?? '' }}" class="form-control" placeholder="TTL Send">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('Balance')}}</label>
                            <input type="number" step="any" name="print[balance]" value="{{ $production->print['balance'] ?? '' }}" class="form-control" placeholder="Enter Balance">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('Received')}}</label>
                            <input type="number" step="any" name="print[received]" value="{{ $production->print['received'] ?? '' }}" class="form-control" placeholder="Received">
                        </div>

                        <div class="col-lg-12 mt-4">
                            <h6>{{__('Input')}}</h6>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Line')}}</label>
                            <input type="text" name="input_line[name]" value="{{ $production->input_line['name'] ?? '' }}" class="form-control" placeholder="Name">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Input Daily')}}</label>
                            <input type="number" step="any" name="input_line[daily]" value="{{ $production->input_line['daily'] ?? '' }}" class="form-control" placeholder="Input Daily">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Total Input')}}</label>
                            <input type="number" step="any" name="input_line[total]" value="{{ $production->input_line['total'] ?? '' }}" class="form-control" placeholder="Total Input">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label>{{__('TTL Input Balance')}}</label>
                            <input type="number" step="any" name="input_line[balance]" value="{{ $production->input_line['balance'] ?? '' }}" class="form-control" placeholder="TTL Input Balance">
                        </div>
                        <div class="col-lg-12 mt-4">
                            <h6>{{__('Output/Sewing')}}</h6>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('Daily Output')}}</label>
                            <input type="number" step="any" name="output_line[daily]" value="{{ $production->output_line['daily'] ?? '' }}" class="form-control" placeholder="Daily Output">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('TTL Output')}}</label>
                            <input type="number" step="any" name="output_line[total]" value="{{ $production->output_line['total'] ?? '' }}" class="form-control" placeholder="TTL Output">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('Sewing Balance')}}</label>
                            <input type="number" step="any" name="output_line[balance]" value="{{ $production->output_line['balance'] ?? '' }}" class="form-control" placeholder="Sewing Balance">
                        </div>
                        <div class="col-lg-12 mt-4">
                            <h6>{{__('Finishing')}}</h6>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Daily Finishing Receive')}}</label>
                            <input type="number" step="any" name="finishing[daily_receive]" value="{{ $production->finishing['daily_receive'] ?? '' }}" class="form-control" placeholder="Daily Finishing Receive">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Total Finishing')}}</label>
                            <input type="number" step="any" name="finishing[total]" value="{{ $production->finishing['total'] ?? '' }}" class="form-control" placeholder="Total Finishing">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Finishing Balance')}}</label>
                            <input type="number" step="any" name=finishing[balance]" value="{{ $production->finishing['balance'] ?? '' }}" class="form-control" placeholder="Finishing Balance">
                        </div>
                        <div class="col-lg-12 mt-4">
                            <h6>{{__('Poly Status')}}</h6>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('Daily')}}</label>
                            <input type="number" step="any" name="poly[daily]" value="{{ $production->poly['daily'] ?? '' }}" class="form-control" placeholder="Daily">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('Total')}}</label>
                            <input type="number" step="any" name="poly[total]" value="{{ $production->poly['total'] ?? '' }}" class="form-control" placeholder="Total">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>{{__('Poly Balance')}}</label>
                            <input type="number" step="any" name="poly[balance]" value="{{ $production->poly['balance'] ?? '' }}" class="form-control" placeholder="Poly Balance">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label>{{__('Remarks')}}</label>
                            <input type="text" name="remarks" value="{{ $production->remarks }}" class="form-control" placeholder="Remarks">
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
        </div>
    </div>
<input type="hidden" id="url" data-model="Production" value="{{ route('productions.get-order') }}">
@endsection

@push('js')
<script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
