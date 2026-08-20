<div class="row">
    <div class="col-4 mt-30">
        <table class="table table-bordered small-table clr-black form-table-sm budget-form">
            <tr>
                <td>{{__('Party Name:')}}</td>
                <td>{{ $costing->order->party->name ?? '' }}</td>
            </tr> <tr>
                <td>{{__('Type:')}}</td>
                <td>{{ $costing->order->party->type ?? '' }}</td>
            </tr>

            <tr>
                <td>{{__('Order No')}}</td>
                <td>{{ $costing->order->order_no ?? '' }}</td>
            </tr>
            <tr>
                <td>{{__('Fabrication')}}</td>
                <td>{{ $costing->order->fabrication ?? '' }}</td>
            </tr>
            <tr>
                <td>{{__('Style')}}</td>
                <td>{{ $costing->order_info['style'] ?? null }}</td>
            </tr>
            <tr>
                <td>{{__('Order Qty')}}</td>
                <td>{{ $costing->order_info['qty'] ?? '' }}</td>
            </tr>
            <tr>
                <td>{{__('Unit Price')}}</td>
                <td>{{ currency_format($costing->order_info['unit_price'] ?? '') }}</td>
            </tr>
            <tr>
                <td>{{__('Total L/C Value')}}</td>
                <td>{{ currency_format( $costing->order_info['lc'] ?? '' ) }}</td>
            </tr>
        </table>
    </div>
    <div class="responsive-table budget-form">
        <table class="table table-bordered" >
            <thead>
            <tr>
                <th>{{__('items details')}}</th>
                <th>{{__('yarn composition & count')}}</th>
                <th>{{__('type')}}</th>
                <th>{{__('qty(KGS)')}}</th>
                <th>{{__('unit')}}</th>
                <th>{{__('price(KGS)')}}</th>
                <th>{{__('total')}}</th>
                <th>{{__('G. total')}}</th>
                <th>{{__('Payment')}}</th>
                <th>{{__('Remarks')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($costing->yarn_desc['items'] ?? [] as $key=>$items)
                <tr class="duplicate-one">
                    <td>{{ $items ?? '' }}</td>
                    <td>{{ $costing->yarn_desc['composition'][$key] ?? '' }}</td>
                    <td>{{ $costing->yarn_desc['type'][$key] ?? '' }}</td>
                    <td>{{ $costing->yarn_desc['qty'][$key] ?? '' }}</td>
                    <td>{{ $costing->yarn_desc['unit'][$key] ?? '' }}</td>
                    <td>{{ currency_format($costing->yarn_desc['price'][$key] ?? 0) }}</td>
                    <td>{{ currency_format($costing->yarn_desc['total'][$key] ?? 0) }}</td>
                    <td rowspan="1">{{ currency_format($costing->yarn_desc['grand_total'][$key] ?? 0) }}</td>
                    <td>{{ $costing->yarn_desc['payment'][$key] ?? '' }}</td>
                    <td>{{ $costing->yarn_desc['remarks'][$key] ?? '' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" class="text-start"><b>{{__('Yarn')}}</b></td>
                <td>{{ currency_format($costing->yarn_total) }}</td>
                <td colspan="2"></td>
            </tr>

            @foreach($costing->knitting_desc['items'] ?? [] as $key=>$items)
                <tr class="duplicate-two">
                    <td>{{ $items ?? '' }}</td>
                    <td>{{ $costing->knitting_desc['composition'][$key] ?? '' }}</td>
                    <td>{{ $costing->knitting_desc['type'][$key] ?? '' }}</td>
                    <td>{{ $costing->knitting_desc['qty'][$key] ?? '' }}</td>
                    <td>{{ $costing->knitting_desc['unit'][$key] ?? '' }}</td>
                    <td>{{ currency_format($costing->knitting_desc['price'][$key] ?? 0) }}</td>
                    <td>{{ currency_format($costing->knitting_desc['total'][$key] ?? 0) }}</td>
                    <td>{{ currency_format($costing->knitting_desc['grand_total'][$key] ?? 0) }}</td>
                    <td>{{ $costing->knitting_desc['payment'][$key] ?? '' }}</td>
                    <td>{{ $costing->knitting_desc['remarks'][$key] ?? '' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" class="text-start"><b>{{__('Knitting Type')}}</b></td>
                <td>{{ currency_format($costing->knitting_total) }}</td>
                <td colspan="2"></td>
            </tr>

            @foreach($costing->dyeing_desc['items'] ?? [] as $key=>$items)
                <tr class="duplicate-three">
                    <td>{{ $items ?? '' }}</td>
                    <td>{{ $costing->dyeing_desc['composition'][$key] ?? '' }}</td>
                    <td>{{ $costing->dyeing_desc['type'][$key] ?? '' }}</td>
                    <td>{{ $costing->dyeing_desc['qty'][$key] ?? '' }}</td>
                    <td>{{ $costing->dyeing_desc['unit'][$key] ?? '' }}</td>
                    <td>{{ currency_format($costing->dyeing_desc['price'][$key]) ?? 0 }}</td>
                    <td>{{ currency_format($costing->dyeing_desc['total'][$key]) ?? 0 }}</td>
                    <td>{{ currency_format($costing->dyeing_desc['grand_total'][$key]) ?? 0 }}</td>
                    <td>{{ $costing->dyeing_desc['payment'][$key] ?? '' }}</td>
                    <td>{{ $costing->dyeing_desc['remarks'][$key] ?? '' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" class="text-start"><b>{{__('Dyeing & Finishing')}}</b></td>
                <td>{{ currency_format($costing->dyeing_total) }}</td>
                <td colspan="2"></td>
            </tr>

            @foreach($costing->print_desc['items'] ?? [] as $key=>$items)
                <tr class="duplicate-four">
                    <td>{{ $items ?? '' }}</td>
                    <td>{{ $costing->print_desc['composition'][$key] ?? '' }}</td>
                    <td>{{ $costing->print_desc['type'][$key] ?? '' }}</td>
                    <td>{{ $costing->print_desc['qty'][$key] ?? '' }}</td>
                    <td>{{ $costing->print_desc['unit'][$key] ?? '' }}</td>
                    <td>{{ currency_format($costing->print_desc['price'][$key] ?? 0) }}</td>
                    <td>{{ currency_format($costing->print_desc['total'][$key] ?? 0) }}</td>
                    <td>{{ currency_format($costing->print_desc['grand_total'][$key] ?? 0) }}</td>
                    <td>{{ $costing->print_desc['payment'][$key] ?? '' }}</td>
                    <td>{{ $costing->print_desc['remarks'][$key] ?? '' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" class="text-start"><b>{{__('Print/Embo')}}</b></td>
                <td>{{ currency_format($costing->print_total) }}</td>
                <td colspan="2"></td>
            </tr>

            @foreach($costing->trim_desc['items'] ?? [] as $key=>$items)
                <tr class="duplicate-five">
                    <td>{{ $items ?? '' }}</td>
                    <td>{{ $costing->trim_desc['composition'][$key] ?? '' }}</td>
                    <td>{{ $costing->trim_desc['type'][$key] ?? '' }}</td>
                    <td>{{ $costing->trim_desc['qty'][$key] ?? '' }}</td>
                    <td>{{ $costing->trim_desc['unit'][$key] ?? '' }}</td>
                    <td>{{ currency_format($costing->trim_desc['price'][$key] ?? 0) }}</td>
                    <td>{{ currency_format($costing->trim_desc['total'][$key] ?? 0) }}</td>
                    <td>{{ currency_format($costing->trim_desc['grand_total'][$key] ?? 0) }}</td>
                    <td>{{ $costing->trim_desc['payment'][$key] ?? '' }}</td>
                    <td>{{ $costing->trim_desc['remarks'][$key] ?? '' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" class="text-start"><b>{{__('Trims & Accessories')}}</b></td>
                <td>{{ currency_format($costing->trim_total) }}</td>
                <td colspan="2"></td>
            </tr>

            <tr>
                <td colspan="3" class="text-start"><b>{{__('Commercial Cost')}}</b></td>
                <td>{{ $costing->commercial_qty }}</td>
                <td>{{ $costing->commercial_unit }}</td>
                <td>{{ currency_format($costing->commercial_price) }}</td>
                <td>{{ currency_format($costing->commercial_total) }}</td>
                <td>{{ currency_format($costing->commercial_total) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="3" class="text-start"><b>{{__('CM Cost/DZ.')}}</b></td>
                <td>{{ $costing->cm_cost_composition }}</td>
                <td>{{ $costing->cm_cost_qty }}</td>
                <td>{{ $costing->cm_cost_unit }}</td>
                <td>{{ currency_format($costing->cm_cost_price) }}</td>
                <td>{{ currency_format($costing->cm_cost_total) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="7"></td>
                <td>{{ currency_format($costing->grand_total) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="7" class="text-end text-uppercase"><b>{{__('Total Costing Per Pc Fob')}}</b></td>
                <td>{{ currency_format($costing->grand_total_in_dzn) }}</td>
                <td colspan="2"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="button-group text-center mt-5"></div>
</div>
