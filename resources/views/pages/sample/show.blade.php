@extends('layouts.master')

@section('main_content')
    <div class="container-fluid mt-3">
        <div class="table-header">
            <h3>{{__('View Sample')}}</h3>
            <a href="{{ route('samples.index') }}" class="theme-btn print-btn text-light"><i class="fa fa-arrow-left"></i> {{__('Back')}}</a>
        </div>
        <div class="order-form-section">
            <form>
                <div class="row">
                    <div class="col-lg-4 mt-2">
                        <p><b>{{__('Order No:')}}</b><span> {{ $sample->order->order_no ?? '' }}</span></p>
                    </div>
                    <div class="col-lg-4 mt-2">
                        <p><b>{{__('Consignee:')}}</b><span> {{ $sample->consignee }}</span></p>
                    </div>
                    <div class="col-lg-4 mt-2">
                        <p><b>{{__('Status:')}}</b><span>  {{ $sample->status == 1 ? 'Pending' : ($sample->status == 2 ? 'Approved' : 'Reject') }}</span></p>
                    </div>
                    <div class="responsive-table">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>{{__('Style')}}</th>
                                <th>{{__('Color')}}</th>
                                <th>{{__('Item')}}</th>
                                <th>{{__('Sample Type')}}</th>
                                <th>{{__('Garments Qty')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $sample->styles ?? [] as $key => $style)
                                <tr>
                                    <td>{{ $style ?? '' }}</td>
                                    <td>{{ $sample->colors[$key] ?? '' }}</td>
                                    <td>{{ $sample->items[$key] ?? '' }}</td>
                                    <td>{{ $sample->types[$key] ?? '' }}</td>
                                    <td>{{ $sample->quantities[$key] ?? '' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

