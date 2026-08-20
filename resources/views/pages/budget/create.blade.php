<div class="table-header">
    <h4>{{__('Create budget')}}</h4>
</div>
<form action="{{ route('budgets.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
    @csrf
    <div class="row table-form-section budget-form">
        <div class="col-4 mt-30">
            <label>{{ __("Select a order") }}</label>
            <select name="order_id" id="order" class="table-select form-control select-tow mb-30 order-class">
                <option value="">{{__('Select a Order')}}</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}">{{ $order->order_no }}</option>
                @endforeach
            </select>

            <select name="order_info[style]" required="required" class="form-control costing-style select-tow mb-30 w-100 style-dropdown-container">
                <option value="">{{__('Style (Select Order First)')}}</option>
                {{-- Add dropdown options dynamically from JavaScript --}}
            </select>

            <table class="table table-bordered small-table clr-black form-table-sm">
                <tr>
                    <td>{{__('Pre-costing Date')}}</td>
                    <td><input type="date" name="pre_cost_date" required class="form-control datepicker" placeholder="Pre-costing"></td>
                </tr>
                <tr>
                    <td>{{__('Post-costing Date')}}</td>
                    <td><input type="date" name="post_cost_date" required class="form-control datepicker" placeholder="Post-costing"></td>
                </tr>
            </table>
        </div>
        <div class="col-4 mt-30">
            <table class="table table-bordered small-table clr-black form-table-sm">
                <tr>
                    <td>{{__('Payment Mode')}}</td>
                    <td><input type="text" readonly id="payment" class="form-control" placeholder="Payment Mode"></td>
                </tr>
                <tr>
                    <td>{{__('Party Name')}}</td>
                    <td><input type="text" readonly id="party_name" class="form-control" placeholder="Party Name"></td>
                </tr>
                <tr>
                    <td>{{__('Type')}}</td>
                    <td><input type="text" readonly id="party_type" class="form-control" placeholder="Party Type"></td>
                </tr>
                <tr class="all-hide">
                    <td>{{__('Color')}}</td>
                    <td><input type="text" name="order_info[color]"  readonly id="color" class="form-control clear" placeholder="Color"></td>
                </tr>
                <tr class="all-hide">
                    <td>{{__('Shipment Date')}}</td>
                    <td><input type="text" name="order_info[shipment_date]" readonly id="shipment_date" class="form-control datepicker clear" placeholder="Shipment Date"></td>
                </tr>
                <tr>
                    <td>{{__('Quantity')}}</td>
                    <td><input type="text" name="order_info[qty]" readonly id="quantity" value="0" class="form-control clear" placeholder="Quantity"></td>
                </tr>
                <tr>
                    <td>{{__('Unit Price')}}</td>
                    <td><input type="text" name="order_info[unit_price]" readonly value="0" id="unit_price" class="form-control clear" placeholder="Unit Price"></td>
                </tr>
                <tr>
                    <td>{{__('LC Value')}}</td>
                    <td ><input type="text" name="order_info[lc]" readonly value="0" id="lc" class="form-control clear"  ></td>
                </tr>
            </table>
        </div>
        <div class="col-lg-4 mt-30">
            <div class="order-management-image">
                <label><b>{{__('Order Image')}}</b></label>
                <label id="upload" class="upload-img">
                    <i><img src="{{ asset('assets/images/icons/upload.png') }}" id="order_image" class="img-preview"></i>
                </label>
            </div>
        </div>

    </div>
    <div class="row table-form-section budget-form based-on-order">
        <div class="col-lg-12 mt-30">
            <div class="table-form-section">
                <div class="responsive-table m-0">
                    <table class="table table-bordered small-table">
                        <thead>
                        <tr>
                            <th>{{__('Description - Fabric')}}</th>
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
                        <tr class="position-relative">
                            <td><div class="add-btn-three"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                            <td><div class="tr-remove-btn remove-three"><i class="fas fa-trash"></i></div></td>
                        </tr>

                        <tr class="duplicate-three">
                            <td><input type="text" name="yarn_desc[fab_desc][]" class="form-control clear-input" placeholder="Description"></td>
                            <td><input type="text" name="yarn_desc[supplier_name][]" class="form-control clear-input" placeholder="Supplier name"></td>
                            <td><input type="text" name="yarn_desc[yarn_count][]" class="form-control clear-input" placeholder="Yarn count"></td>
                            <td><input type="number" step="any" name="yarn_desc[unit_price][]" class="form-control count-total yarn-unit-price 0" data-length="0" placeholder="Unit price" value="0"></td>
                            <td><input type="text" name="yarn_desc[consumption][]" class="form-control clear-input" placeholder="Consumptions"></td>
                            <td><input type="text" name="yarn_desc[w][]" class="form-control clear-input" placeholder="W%"></td>
                            <td><input type="number" step="any" name="yarn_desc[total_qty][]" class="form-control count-total yarn-qty 0" data-length="0" placeholder="Total qty/kg" value="0"></td>
                            <td><input type="number" step="any" readonly name="yarn_desc[total_cost][]" class="form-control yarn-cost 0" data-length="0" value="0"></td>
                            <td><input type="number" step="any" readonly name="yarn_desc[pre_cost][]" class="form-control yarn-pre-cost 0" data-length="0" value="0"></td>
                        </tr>

                        <tr>
                            <td><b>{{__('Total yarn cost')}}</b></td>
                            <td colspan="5"></td>
                            <td><b class="total-yarn-qty clear">0</b></td>
                            <input type="hidden" value="0" name="yarn_qty" id="yarn_qty">
                            <td><b class="total-yarn-cost clear">0</b></td>
                            <input type="hidden" value="0" name="yarn_cost" id="yarn_cost">
                            <td><b class="total-yarn-pre-cost clear">0</b></td>
                            <input type="hidden" name="pre_cost_desc[yarn]" id="pre_cost_desc_yarn">

                        </tr>
                        <tr class="position-relative">
                            <td><div class="add-btn-four"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                            <td><div class="tr-remove-btn remove-four"><i class="fas fa-trash"></i></div></td>
                        </tr>
                        <tr class="duplicate-four">
                            <td><input type="text" name="knitting_desc[fab_desc][]" class="form-control clear-input" placeholder="Description-fabric"></td>
                            <td><input type="text" name="knitting_desc[supplier_name][]" class="form-control clear-input" placeholder="Supplier Name"></td>
                            <td><input type="text" name="knitting_desc[yarn_count][]" class="form-control clear-input" placeholder="Yarn Count"></td>
                            <td><input type="number" step="any" name="knitting_desc[unit_price][]" class="form-control count-total knit-unit-price 0" data-length="0" value="0"  placeholder="unit Price"></td>
                            <td><input type="text" name="knitting_desc[consumption][]" class="form-control clear-input" placeholder="Consumptions"></td>
                            <td><input type="text" name="knitting_desc[w][]" class="form-control clear-input" placeholder="W%"></td>
                            <td><input type="number" step="any"  name="knitting_desc[total_qty][]" class="form-control count-total knit-qty 0" data-length="0" value="0" placeholder="Total Qty/kg"></td>
                            <td><input type="number" step="any" readonly=""  name="knitting_desc[total_cost][]" class="form-control knit-cost 0" data-length="0" value="0" placeholder="Total Cost"></td>
                            <td><input type="number" step="any" readonly name="knitting_desc[pre_cost][]" class="form-control knit-pre-cost 0" data-length="0" value="0" placeholder="Pre-Cost"></td>
                        </tr>
                        <tr>
                            <td><b>{{__('Total Knitting cost')}}</b></td>
                            <td colspan="5"></td>
                            <td><b class="total-knit-qty clear">0</b></td>
                            <input type="hidden" name="knitting_qty" id="knitting_qty" value="0">
                            <td><b class="total-knit-cost clear">0</b></td>
                            <input type="hidden" name="knitting_cost" id="knitting_cost" value="0">
                            <td><b class="total-knit-pre-cost clear">0</b></td>
                            <input type="hidden" name="pre_cost_desc[knitting]" id="pre_cost_desc_knitting">

                        </tr>
                        <tr class="position-relative">
                            <td><div class="add-btn-five"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                            <td><div class="tr-remove-btn remove-five"><i class="fas fa-trash"></i></div></td>
                        </tr>
                        <tr class="duplicate-five">
                            <td><input type="text" name="dfa_desc[fab_desc][]" class="form-control clear-input" placeholder="Description-fabric"></td>
                            <td><input type="text" name="dfa_desc[supplier_name][]" class="form-control clear-input" placeholder="Supplier Name"></td>
                            <td><input type="text" name="dfa_desc[yarn_count][]" class="form-control clear-input" placeholder="Yarn Count"></td>
                            <td><input type="number" step="any" name="dfa_desc[unit_price][]" class="form-control count-total dfa-unit-price 0" data-length="0" value="0" placeholder="unit Price"></td>
                            <td><input type="text" name="dfa_desc[consumption][]" class="form-control clear-input" data-length="0" value="0" placeholder="Consumptions"></td>
                            <td><input type="text" name="dfa_desc[w][]" class="form-control clear-input" placeholder="W%"></td>
                            <td><input type="number" step="any" name="dfa_desc[total_qty][]" class="form-control count-total dfa-qty 0" data-length="0" value="0" placeholder="Total Qty/kg"></td>
                            <td><input type="number" step="any" readonly name="dfa_desc[total_cost][]" class="form-control dfa-cost 0" data-length="0" value="0" placeholder="Total Cost"></td>
                            <td><input type="number" step="any" readonly name="dfa_desc[pre_cost][]" class="form-control dfa-pre-cost 0" data-length="0" value="0" placeholder="Pre-Cost"></td>
                        </tr>
                        <tr>
                            <td><b>{{__('Total dyeing+finishing, AOP cost')}}</b></td>
                            <td colspan="5"></td>
                            <td><b class="total-dfa-qty clear">0</b></td>
                            <input type="hidden" value="0" name="dfa_qty" id="dfa_qty">
                            <td><b class="total-dfa-cost clear">0</b></td>
                            <input type="hidden"  value="0" name="dfa_cost" id="dfa_cost">
                            <td><b class="total-dfa-pre-cost clear">0</b></td>
                            <input type="hidden" name="pre_cost_desc[dfa]" id="pre_cost_desc_dfa">
                        </tr>
                        <tr>
                            <td colspan="6"><b>{{__('Total fabric cost')}}</b></td>
                            <td><b class="total-fabric-qty clear">0</b></td>
                            <input type="hidden" name="fabric_qty" id="fabric_qty" value="0">
                            <td><b class="total-fabric-cost clear">0</b></td>
                            <input type="hidden" value="0" name="fabric_cost" id="fabric_cost">
                            <td><b class="total-fabric-pre-cost clear">0</b></td>
                            <input type="hidden" name="pre_cost_desc[fabric]" id="pre_cost_desc_fabric">
                        </tr>
                        </tbody>
                    </table>
                </div>
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
                    <tr class="position-relative">
                        <td><div class="add-btn-six"><i class="fal fa-plus" aria-hidden="true"></i></div></td>
                        <td><div class="tr-remove-btn remove-six"><i class="fas fa-trash"></i></div></td>
                    </tr>
                    <tr class="duplicate-six">
                        <td><input type="text" name="accessories_desc[accessories_des][]" class="form-control clear-input" placeholder="Accessories"></td>
                        <td><input type="text" name="accessories_desc[supplier_name][]" class="form-control clear-input" placeholder="Supplier"></td>
                        <td><input type="number" step="any" name="accessories_desc[unit_price][]" class="form-control count-total accessories-unit-price 0" data-length="0" value="0" placeholder="Unit Price"></td>
                        <td><input type="text" name="accessories_desc[unit_number][]" class="form-control clear-input" placeholder="Units"></td>
                        <td><input type="text" name="accessories_desc[consumption][]" class="form-control clear-input" placeholder="Consumptions"></td>
                        <td><input type="text" name="accessories_desc[w][]" class="form-control clear-input" placeholder="W%"></td>
                        <td><input type="number" step="any" name="accessories_desc[total_qty][]" class="form-control count-total accessories-qty 0" data-length="0" value="0" placeholder="Total Qty"></td>
                        <td><input type="number" step="any" readonly name="accessories_desc[total_cost][]" class="form-control accessories-cost 0" data-length="0" value="0" placeholder="Total Cost"></td>
                        <td><input type="number" step="any" readonly name="accessories_desc[pre_cost][]" class="form-control accessories-pre-cost 0" data-length="0" value="0" placeholder="Pre-Cost"></td>
                    </tr>
                    <tr>
                        <td><b>{{__('Total Accessories cost')}}</b></td>
                        <td colspan="5"></td>
                        <td><b class="total-accessories-qty clear">0</b></td>
                        <input type="hidden" name="accessories_qty" id="accessories_qty" value="0">
                        <td><b class="total-accessories-cost clear">0</b></td>
                        <input type="hidden" value="0" name="accessories_cost" id="accessories_cost">
                        <td><b class="total-accessories-pre-cost clear">0</b></td>
                        <input type="hidden" name="pre_cost_desc[accessories]" id="pre_cost_desc_accessories">
                    </tr>
                    <tr>
                        <td colspan="7"><b>{{__('Total Fabric & Accessories Cost')}}</b></td>
                        <td><b class="total-making-cost clear">0</b></td>
                        <input type="hidden" name="total_making_cost" id="total_making_cost" value="0">
                        <td><b class="total-making-pre-cost clear">0</b></td>
                        <input type="hidden" name="total_making_pre_cost" id="total_making_pre_cost">
                    </tr>
                    <tr>
                        <td>{{__('Finance/Commercial & Logistic Cost')}}</td>
                        <td colspan="4"></td>
                        <td><input type="number" step="any" name="finance_value" value="0" class="finance-value" placeholder="1.70%"></td>
                        <td></td>
                        <td><input type="number" step="any" readonly name="finance_cost"  value="0" class="finance-cost" placeholder="Cost"></td>
                        <td><input type="number" step="any" readonly name="finance_pre_cost" value="0" class="finance-pre-cost" placeholder="Pre cost"></td>
                    </tr>
                    <tr>
                        <td>{{__('Deferred payment with interest')}}</td>
                        <td colspan="4"></td>
                        <td><input type="number" step="any" name="deferred_value"  value="0" class="deferred-value" placeholder="1.70%"></td>
                        <td></td>
                        <td><input type="number" step="any" readonly name="deferred_cost"  value="0" class="deferred-cost" placeholder="Cost"></td>
                        <td><input type="number" step="any" readonly name="deferred_pre_cost" id="pre_cost" value="0" class="deferred-pre-cost" placeholder="Pre cost"></td>
                    </tr>
                    <tr>
                        <td>{{__('Other Cost')}}</td>
                        <td colspan="6"></td>
                        <td colspan="2"><input type="number" step="any" name="other_cost" id="other_cost" placeholder="0"></td>
                    </tr>
                    <tr>
                        <td colspan="7"><b>{{__('Grand total')}}</b></td>
                        <td><b class="grand-total-cost clear">0</b></td>
                        <input type="hidden" name="grand_cost" id="grand_cost" value="0">
                        <td><b class="grand-total-pre-cost clear">0</b></td>
                        <input type="hidden" name="pre_cost_desc[grand]" id="pre_cost_desc_grand">
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
                        <td id="factory_cm_cost"><b>0</b></td>
                        <input type="hidden" name="factory_cm_cost" id="factoryCmCost" value="0">
                        <td id="factory_cm_pre_cost"><b>0</b></td>
                        <input type="hidden" name="factory_cm_pre_cost" id="factoryCmPreCost" value="0">
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
                        <td colspan="2"><b>{{__('TOTAL EXPENSE')}}</b></td>
                        <td id="total_expense_cost"><b>0</b></td>
                        <input type="hidden" name="total_expense_cost" id="totalExpenseCost" value="0">
                        <td id="total_expense_pre_cost"><b>0</b></td>
                        <input type="hidden" name="total_expense_pre_cost" id="totalExpensePreCost" value="0">
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
                        <td colspan="2"><b>{{__('NET EARNING')}}</b></td>
                        <td id="net_earning_cost"><b>0</b></td>
                        <input type="hidden" name="net_earning_cost" id="netEarningCost" value="0">
                        <td id="net_earning_pre_cost"><b>0</b></td>
                        <input type="hidden" name="net_earning_pre_cost" id="netEarningPreCost" value="0">
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-12 mt-5">
            <div class="button-group text-center">
                <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
            </div>
        </div>
    </div>
</form>

<input type="hidden" value="{{ route('budget.order') }}" id="get-order">
