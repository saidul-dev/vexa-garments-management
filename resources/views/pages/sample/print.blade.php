@extends('layouts.blank')

@section('title')
    {{__('Sample')}}
@endsection

@section('main_content')
    <div class="section-container print-wrapper p-0 erp-new-invice">
        <div class="erp-table-section">
            <div class="container">
                <button class="print-window theme-btn print-btn float-lg-end"><i class="fa fa-print"></i> Print</button>
                <div class="table-header justify-content-center border-0 d-block text-center">
                    <h3 class="text-center"><strong>{{ get_option('company')['name'] ?? '' }}</strong></h3>
                    <p>{{ get_option('company')['address'] ?? '' }}</p>
                    <h3>Sample</h3>
                </div>
                <div class="table-container">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="mb-2"><b>Party Name :</b> {{ $sample->order->party->name ?? '' }}</h6>
                        </div>
                        <div class="col-6 text-end">
                            <h6 class="mb-lg-2"><b>Date :</b> {{ formatted_date($sample->created_at) }}</h6>
                        </div>
                    </div>
                    <table class="table text-center commercial-invoice text-start table-bordered paking-detail-table" id="erp-table">
                        <thead>
                        <tr>
                            <th rowspan="2">Order No</th>
                            <th rowspan="2">Style</th>
                            <th rowspan="2" >Color</th>
                            <th rowspan="2">item</th>
                            <th rowspan="2">sample Type</th>
                            <th colspan="8">size</th>
                            <th rowspan="2">garments/qty</th>
                        </tr>
                        <tr>
                            <th>{{ $sample->header['size_xs'] ?? '' }}</th>
                            <th>{{ $sample->header['size_s'] ?? '' }}</th>
                            <th>{{ $sample->header['size_m'] ?? '' }}</th>
                            <th>{{ $sample->header['size_l'] ?? '' }}</th>
                            <th>{{ $sample->header['size_xl'] ?? '' }}</th>
                            <th>{{ $sample->header['size_xxl'] ?? '' }}</th>
                            <th>{{ $sample->header['size_3xl'] ?? '' }}</th>
                            <th>{{ $sample->header['size_4xl'] ?? '' }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $sample->styles ?? [] as $key => $style)
                        <tr>
                            @if($loop->first)
                                <td rowspan="{{ $key }}">{{ $sample->order->order_no ?? '' }}</td>
                            @endif
                            <td>{{ $style }}</td>
                            <td>{{ $sample->colors[$key] ?? '' }}</td>
                            <td>{{ $sample->items[$key] ?? '' }}</td>
                            <td>{{ $sample->types[$key] ?? '' }}</td>
                            <td>{{ $sample->sizes['xs'][$key] ?? '' }}</td>
                            <td>{{ $sample->sizes['s'][$key] ?? '' }}</td>
                            <td>{{ $sample->sizes['m'][$key] ?? '' }}</td>
                            <td>{{ $sample->sizes['l'][$key] ?? '' }}</td>
                            <td>{{ $sample->sizes['xl'][$key] ?? '' }}</td>
                            <td>{{ $sample->sizes['xxl'][$key] ?? '' }}</td>
                            <td>{{ $sample->sizes['3xl'][$key] ?? '' }}</td>
                            <td>{{ $sample->sizes['4xl'][$key] ?? '' }}</td>
                            <td>{{ $sample->quantities[$key] ?? '' }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="12" class="fw-bold text-end">Total </td>
                            <td class="fw-bold">{{ $qty }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
