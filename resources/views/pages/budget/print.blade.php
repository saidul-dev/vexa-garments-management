@extends('layouts.blank')

@section('main_content')
    <div class="section-container print-wrapper p-0 A4-paper">
        <div class="erp-table-section">
            <div class="container-fluid">
                <a target="_blank" href="{{ route('budget.print', $budget->id) }}" class="theme-btn print-btn float-end"><i class="fa fa-print"></i> Print</a>
                <div class="table-header justify-content-center border-0 d-block text-center">
                   @include('pages.invoice.header2')
                    <h4 class="mt-2">{{__('COST BUDGET')}}</h4>
                </div>
                <div class="row">
                    <div class="col-4 mt-30">
                        <table class="table table-bordered small-table clr-black">
                            <tr>
                                <td>{{__('PRE-COSTING DATE')}}</td>
                                <td>{{ $budget->pre_cost_date }}</td>
                            </tr>
                            <tr>
                                <td>{{__('POST-COSTING DATE')}}</td>
                                <td>{{ $budget->post_cost_date }}</td>
                            </tr>
                            <tr>
                                <td>{{__('Party Name')}}</td>
                                <td>{{ $budget->order->party->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{__('Type')}}</td>
                                <td>{{ $budget->order->party->type ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{__('PAYMENT MODE:')}}</td>
                                <td>{{ $budget->order->payment_mode ?? '' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4 mt-30">
                        <table class="table table-bordered small-table clr-black">
                            <tr>
                                <td>{{__('STYLE')}}</td>
                                <td>{{ $budget->order_info['style'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{__('COLOR')}}:</td>
                                <td>{{ $budget->order_info['color'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><b>{{__('QUANTITY')}}</b></td>
                                <td>{{ $budget->order_info['qty'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{__('UNIT PRICE:')}}</td>
                                <td>{{ currency_format($budget->order_info['unit_price'] ?? '') }}</td>
                            </tr>
                            <tr>
                                <td>{{__('LC VALUE:')}}</td>
                                <td>{{ currency_format($budget->order_info['lc'] ?? '') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-4 mt-20">
                        <div class="order-management-image">
                            <label><b>{{__('Item Picture')}}</b></label>
                            <label id="upload" class="upload-img budget-upload-img">
                                <i><img src="{{ asset($budget->order->image ?? 'assets/images/icons/upload.png') }}" class="img-preview"></i>
                            </label>
                        </div>
                    </div>
                
                    <div class="col-lg-12 mt-30">
                        <div class="responsive-table m-0">
                            <table class="table table-bordered small-table">
                                <thead>
                                <tr>
                                    <th>{{__('DESCRIPTION - FABRIC')}}</th>
                                    <th>{{__('supplier name')}}</th>
                                    <th>{{__('Yarn Count')}}</th>
                                    <th>{{__('unit price ($)')}}</th>
                                    <th>{{__('Consumptions (kg/dz)')}}</th>
                                    <th>{{__('W%')}}</th>
                                    <th>{{__('Total Qty (kg.)')}}</th>
                                    <th>{{__('Total Cost')}}</th>
                                    <th>{{__('pre-Cost%')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($budget->yarn_desc['fab_desc'] ?? [] as $key=>$fab)
                                    <tr class="duplicate-three">
                                        <td>{{ $fab ?? '' }}</td>
                                        <td>{{ $budget->yarn_desc['supplier_name'][$key] ?? '' }}</td>
                                        <td>{{ $budget->yarn_desc['yarn_count'][$key] ?? '' }}</td>
                                        <td>{{ currency_format($budget->yarn_desc['unit_price'][$key] ?? 0) }}</td>
                                        <td>{{ $budget->yarn_desc['consumption'][$key] ?? '' }}</td>
                                        <td>{{ $budget->yarn_desc['w'][$key] ?? '' }}</td>
                                        <td>{{ $budget->yarn_desc['total_qty'][$key] ?? '' }}</td>
                                        <td>{{ currency_format($budget->yarn_desc['total_cost'][$key] ?? 0) }}</td>
                                        <td>{{ $budget->yarn_desc['pre_cost'][$key] ?? '' }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><b>{{__('Total yarn cost')}}</b></td>
                                    <td colspan="5"></td>
                                    <td><b class="total-yarn-qty">{{ $budget->yarn_qty }}</b></td>
                                    <td><b class="total-yarn-cost">{{ currency_format($budget->yarn_cost) }}</b></td>
                                    <td><b class="total-yarn-pre-cost">{{ $budget->pre_cost_desc['yarn'] ?? '' }}</b></td>
                                </tr>
                                @foreach($budget->knitting_desc['fab_desc'] ?? [] as $key=>$fab)
                                    <tr class="duplicate-four">
                                        <td>{{ $fab ?? '' }}</td>
                                        <td>{{ $budget->knitting_desc['supplier_name'][$key] ?? '' }}</td>
                                        <td>{{ $budget->knitting_desc['yarn_count'][$key] ?? '' }}</td>
                                        <td>{{ currency_format($budget->knitting_desc['unit_price'][$key] ?? 0) }}</td>
                                        <td>{{ $budget->knitting_desc['consumption'][$key] ?? '' }}</td>
                                        <td>{{ $budget->knitting_desc['w'][$key] ?? '' }}</td>
                                        <td>{{ $budget->knitting_desc['total_qty'][$key] ?? '' }}</td>
                                        <td>{{ currency_format($budget->knitting_desc['total_cost'][$key] ?? 0) }}</td>
                                        <td>{{ $budget->knitting_desc['pre_cost'][$key] ?? '' }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><b>{{__('Total Knitting cost')}}</b></td>
                                    <td colspan="5"></td>
                                    <td><b class="total-knit-qty">{{ $budget->knitting_qty }}</b></td>
                                    <td><b class="total-knit-cost">{{ currency_format($budget->knitting_cost) }}</b></td>
                                    <td><b class="total-knit-pre-cost">{{ $budget->pre_cost_desc['knitting'] ?? '' }}</b></td>
                                </tr>
                                @foreach($budget->dfa_desc['fab_desc'] ?? [] as $key=>$fab)
                                    <tr class="duplicate-five">
                                        <td>{{ $fab ?? '' }}</td>
                                        <td>{{ $budget->dfa_desc['supplier_name'][$key] ?? '' }}</td>
                                        <td>{{ $budget->dfa_desc['yarn_count'][$key] ?? '' }}</td>
                                        <td>{{ currency_format($budget->dfa_desc['unit_price'][$key] ?? 0) }}</td>
                                        <td>{{ $budget->dfa_desc['consumption'][$key] ?? '' }}</td>
                                        <td>{{ $budget->dfa_desc['w'][$key] ?? '' }}</td>
                                        <td>{{ $budget->dfa_desc['total_qty'][$key] ?? '' }}</td>
                                        <td>{{ currency_format($budget->dfa_desc['total_cost'][$key] ?? 0) }}</td>
                                        <td>{{ $budget->dfa_desc['pre_cost'][$key] ?? '' }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><b>{{__('Total dyeing+finishing, AOP cost')}}</b></td>
                                    <td colspan="5"></td>
                                    <td><b class="total-dfa-qty"></b></td>
                                    <td><b class="total-dfa-cost">{{ currency_format($budget->dfa_cost) }}</b></td>
                                    <td><b class="total-dfa-pre-cost">{{ $budget->pre_cost_desc['dfa'] ?? '' }}</b></td>
                                </tr>
                                <tr>
                                    <td><b>{{__('Total fabric cost')}}</b></td>
                                    <td colspan="5"></td>
                                    <td><b class="total-fabric-qty">{{ $budget->fabric_qty }}</b></td>
                                    <td><b class="total-fabric-cost">{{ currency_format($budget->fabric_cost) }}</b></td>
                                    <td><b class="total-fabric-pre-cost">{{ number_format($budget->pre_cost_desc['fabric'] ?? 0, 3) }}</b></td>
                                </tr>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-30">
                        <div class="responsive-table m-0">
                            <table class="table table-bordered small-table">
                                <thead>
                                <tr>
                                    <th>{{__('Accessories - Description')}}</th>
                                    <th>{{__('supplier name')}}</th>
                                    <th>{{__('unit price ($)')}}</th>
                                    <th>{{__('unit (in number)')}}</th>
                                    <th>{{__('Consumption/ Pc')}}</th>
                                    <th>{{__('W%')}}</th>
                                    <th>{{__('Total Qty')}}</th>
                                    <th>{{__('Total Cost')}}</th>
                                    <th>{{__('pre-Cost%')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($budget->accessories_desc['accessories_des'] ?? [] as $key=>$accessories_des)
                                    <tr class="duplicate-six">
                                        <td>{{ $accessories_des ?? '' }}</td>
                                        <td>{{ $budget->accessories_desc['supplier_name'][$key] ?? '' }}</td>
                                        <td>{{ currency_format($budget->accessories_desc['unit_price'][$key] ?? 0) }}</td>
                                        <td>{{ $budget->accessories_desc['unit_number'][$key] ?? '' }}</td>
                                        <td>{{ $budget->accessories_desc['consumption'][$key] ?? '' }}</td>
                                        <td>{{ $budget->accessories_desc['w'][$key] ?? '' }}</td>
                                        <td>{{ $budget->accessories_desc['total_qty'][$key] ?? '' }}</td>
                                        <td>{{ currency_format($budget->accessories_desc['total_cost'][$key] ?? 0) }}</td>
                                        <td>{{ $budget->accessories_desc['pre_cost'][$key] ?? '' }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><b>{{__('Total Accessories Cost')}}</b></td>
                                    <td colspan="6"></td>
                                    <td><b class="total-accessories-cost">{{ currency_format($budget->accessories_cost) }}</b></td>
                                    <td><b class="total-accessories-pre-cost">{{ $budget->pre_cost_desc['accessories'] ?? '' }}</b></td>
                                </tr>

                                <tr>
                                    <td><b>{{__('Total Fabric & Accessories Cost')}}</b></td>
                                    <td colspan="6"></td>
                                    <td><b>{{ currency_format($budget->print_cost) }}</b></td>
                                    <td><b>{{ $budget->pre_cost_desc['print'] ?? '' }}</b></td>
                                </tr>
                                <tr>
                                    <td>{{__('Finance/Commercial & Logistic Cost')}}</td>
                                    <td colspan="4"></td>
                                    <td>{{ currency_format($budget->finance_value) }}</td>
                                    <td></td>
                                    <td>{{ currency_format($budget->finance_cost) }}</td>
                                    <td>{{ $budget->finance_pre_cost }}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Deferred payment with interest')}}</td>
                                    <td colspan="4"></td>
                                    <td>{{ currency_format($budget->deferred_value) }}</td>
                                    <td></td>
                                    <td>{{ currency_format($budget->deferred_cost) }}</td>
                                    <td>{{ $budget->deferred_pre_cost }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{__('Other Cost')}}</b></td>
                                    <td colspan="6"></td>
                                    <td><b>{{ currency_format($budget->other_cost) }}</b></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><b>{{__('Grand total')}}</b></td>
                                    <td colspan="6"></td>
                                    <td><b>{{ currency_format($budget->grand_cost) }}</b></td>
                                    <td><b>{{ $budget->pre_cost_desc['grand'] ?? '' }}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-12 mt-30">
                        <div class="responsive-table m-0">
                            <table class="table table-bordered small-table m-0">
                                <tbody>
                                <tr>
                                    <td colspan="2"><b>{{__('FACTORY CM TOTAL')}}</b></td>
                                    <td><b>{{ currency_format($budget->total_making_cost) }}</b></td>
                                    <td><b>{{ $budget->total_making_pre_cost }}</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><b>{{__('TOTAL EXPENSE')}}</b></td>
                                    <td><b>{{ currency_format($budget->total_expense_cost) }}</b></td>
                                    <td><b>{{ $budget->total_expense_pre_cost }}</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><b>{{__('NET EARNING')}}</b></td>
                                    <td><b>{{ currency_format($budget->net_earning_cost) }}</b></td>
                                    <td><b>{{ $budget->net_earning_pre_cost }}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-5">
                        <div class="row mt-5">
                            <div class="col-lg-3">
                                <div class="signature">
                                    <p>{{__('Merchandiser')}}</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="signature">
                                    <p>{{__('Asst. Manager of Merchandising')}}</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="signature">
                                    <p>{{__('Head of Merchandising')}}</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="signature">
                                    <p>{{__('Managing Director')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


