/** .......... dynamic button start .......... */
$(document).ready(function () {

    "use strict";
    
    $('.view-budget').on('click', function () {
        var url = $(this).data('url');
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                $('.budget-view-modal').html(response.data);
            },
        });
    
        $('#budget-view').modal('show');
    });
    
    // yarn
    function disableRemoveFeature3() {
        var tr = $(".duplicate-three");
        var trash = $(".remove-three");
        if (tr.length === 1) {
            trash.css("display", "none");
        } else {
            trash.css("display", "block");
        }
    }

    $(".add-btn-three").click(function () {
        let length = parseInt($(".duplicate-three").length);
        $(".duplicate-three:last").clone().insertAfter("tr.duplicate-three:last");
        $('.duplicate-three:last .yarn-unit-price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-three:last .yarn-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-three:last .yarn-cost').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-three:last .yarn-pre-cost').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $(".yarn-unit-price:last").val(0);
        $(".yarn-qty:last").val(0);
        $(".yarn-cost:last").val(0);
        $(".yarn-pre-cost:last").val(0);
        $('.duplicate-three:last .clear-input').val('');
        disableRemoveFeature3();
    });

    $(".remove-three").click(function () {
        $("tr.duplicate-three:last").remove();
        disableRemoveFeature3();
        // quantity
        var total_yarn_qty = 0;
        $('.yarn-qty').each(function () {
            total_yarn_qty += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total-yarn-qty').text(total_yarn_qty)
        // cost
        var total_yarn_cost = 0;
        $('.yarn-cost').each(function () {
            total_yarn_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total-yarn-cost').text(total_yarn_cost)
        // pre-cost
        var total_yarn_pre_cost = 0;
        $('.yarn-pre-cost').each(function () {
            total_yarn_pre_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total-yarn-pre-cost').text(total_yarn_pre_cost);
        totalFab()
    });

    // knit
    function disableRemoveFeature4() {
        var tr = $(".duplicate-four");
        var trash = $(".remove-four");
        if (tr.length === 1) {
            trash.css("display", "none");
        } else {
            trash.css("display", "block");
        }
    }
    $(".add-btn-four").click(function () {
        let length = parseInt($(".duplicate-four").length);
        $(".duplicate-four:last").clone().insertAfter("tr.duplicate-four:last");
        $('.duplicate-four:last .knit-unit-price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-four:last .knit-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-four:last .knit-cost').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-four:last .knit-pre-cost').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $(".knit-unit-price:last").val(0);
        $(".knit-qty:last").val(0);
        $(".knit-cost:last").val(0);
        $(".knit-pre-cost:last").val(0);
        $('.duplicate-four:last .clear-input').val('');
        disableRemoveFeature4()
    });
    $(".remove-four").click(function () {
        $("tr.duplicate-four:last").remove();
        disableRemoveFeature4();
        //quantity
        var total_knit_qty = 0;
        $('.knit-qty').each(function () {
            total_knit_qty += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0)
        });
        $('.total-knit-qty').text(total_knit_qty);
        //cost
        var total_knit_cost = 0;
        $('.knit-cost').each(function () {
            total_knit_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total-knit-cost').text(total_knit_cost);
        //pre-cost
        var total_knit_pre_cost = 0;
        $('.knit-pre-cost').each(function () {
            total_knit_pre_cost += parseFloat($(this).val() && !isNaN($(this).val()) ? $(this).val() : 0)
        });
        $('.total-knit-pre-cost').text(total_knit_pre_cost);
        totalFab()
    });


    // dfa (Dyeing+Finishing, AOP)
    function disableRemoveFeature5() {
        var tr = $(".duplicate-five");
        var trash = $(".remove-five");
        if (tr.length === 1) {
            trash.css("display", "none");
        } else {
            trash.css("display", "block");
        }
    }
    $(".add-btn-five").click(function () {
        let length = parseInt($(".duplicate-five").length);
        $(".duplicate-five:last").clone().insertAfter("tr.duplicate-five:last");
        $('.duplicate-five:last .dfa-unit-price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-five:last .dfa-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-five:last .dfa-cost').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-five:last .dfa-pre-cost').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $(".dfa-unit-price:last").val(0);
        $(".dfa-qty:last").val(0);
        $(".dfa-cost:last").val(0);
        $(".dfa-pre-cost:last").val(0);
        $('.duplicate-five:last .clear-input').val('');
        disableRemoveFeature5();
    });
    $(".remove-five").click(function () {
        $("tr.duplicate-five:last").remove();
        disableRemoveFeature5();
        //quantity
        var total_dfa_qty = 0;
        $('.dfa-qty').each(function () {
            total_dfa_qty += parseFloat($(this).val() && !isNaN($(this).val()) ? $(this).val() : 0)
        });
        $('.total-dfa-qty').text(total_dfa_qty);
        //cost
        var total_dfa_cost = 0;
        $('.dfa-cost').each(function () {
            total_dfa_cost += parseFloat($(this).val() && !isNaN($(this).val()) ? $(this).val() : 0);
        });
        $('.total-dfa-cost').text(total_dfa_cost)
        //pre-cost
        var total_dfa_pre_cost = 0;
        $('.dfa-pre-cost').each(function () {
            total_dfa_pre_cost += parseFloat($(this).val() && !isNaN($(this).val()) ? $(this).val() : 0)
        });
        $('.total-dfa-pre-cost').text(total_dfa_pre_cost);
        totalFab()
    });


    // accessories
    function disableRemoveFeature6() {
        var tr = $(".duplicate-six");
        var trash = $(".remove-six");
        if (tr.length === 1) {
            trash.css("display", "none");
        } else {
            trash.css("display", "block");
        }
    }
    $(".add-btn-six").click(function () {
        $(".duplicate-six:last").clone().insertAfter("tr.duplicate-six:last");
        let length = parseInt($(".duplicate-six").length);
        $('.duplicate-six:last .accessories-unit-price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-six:last .accessories-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-six:last .accessories-cost').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-six:last .accessories-pre-cost').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $(".accessories-unit-price:last").val(0);
        $(".accessories-qty:last").val(0);
        $(".accessories-cost:last").val(0);
        $(".accessories-pre-cost:last").val(0);
        $('.duplicate-six:last .clear-input').val('');
        disableRemoveFeature6();
    });
    $(".remove-six").click(function () {
        $("tr.duplicate-six:last").remove();
        disableRemoveFeature6();
        //quantity
        var total_accessories_qty = 0;
        $('.accessories-qty').each(function () {
            total_accessories_qty += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total-accessories-qty').text(total_accessories_qty);
        //cost
        var total_accessories_cost = 0;
        $('.accessories-cost').each(function () {
            total_accessories_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total-accessories-cost').text(total_accessories_cost);
        //pre-cost
        var total_accessories_pre_cost = 0;
        $('.accessories-pre-cost').each(function () {
            total_accessories_pre_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total-accessories-pre-cost').text(total_accessories_pre_cost);
        totalMakingCost();
    });

    // print
    function disableRemoveFeature7() {
        var tr = $(".duplicate-seven");
        var trash = $(".remove-seven");
        if (tr.length === 1) {
            trash.css("display", "none");
        } else {
            trash.css("display", "block");
        }
    }
    $(".add-btn-seven").click(function () {
        let length = parseInt($(".duplicate-seven").length);
        $(".duplicate-seven:last").clone().insertAfter("tr.duplicate-seven:last");
        $('.duplicate-seven:last .order-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-seven:last .process-loss').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-seven:last .cutting-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-seven:last .unit').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-seven:last .total-value').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-seven:last .print-cost').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-seven:last .print-pre-cost').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $(".order-qty:last").val(0);
        $(".process-loss:last").val(0);
        $(".cutting-qty:last").val(0);
        $(".unit:last").val(0);
        $(".total-value:last").val(0);
        $(".print-cost:last").val(0);
        $(".print-pre-cost:last").val(0);
        $('.duplicate-seven:last .clear-input').val('');
        disableRemoveFeature7()
    });
    $(".remove-seven").click(function () {
        $("tr.duplicate-seven:last").remove();
        disableRemoveFeature7();
        //quantity
        var total_print_qty = 0;
        $('.print-qty').each(function () {
            total_print_qty += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total-print-qty').text(total_print_qty);
        //cost
        var total_print_cost = 0;
        $('.print-cost').each(function () {
            total_print_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total-print-cost').text(total_print_cost);
        //pre-cost
        var total_print_pre_cost = 0;
        $('.print-pre-cost').each(function () {
            total_print_pre_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total-print-pre-cost').text(total_print_pre_cost);
        totalMakingCost();
    });
});
/** ...........dynamic button end................. */


/** ............ create form start ................ */

// dropdown use to input start
$('#order').on('change', function () {
    var orderId = $(this).val();
    var url = $('#get-order').val();
    $.ajax({
        type: "GET",
        url: url + `?order_id=` + orderId,
        dataType: "json",
        success: function (response) {
            $('.ajaxform_instant_reload').trigger('reset');
            $('.clear').text('0');
            $('.order-class').val(orderId);
            $('#payment').val(response.order.payment_mode);
            $('#party_name').val(response.order.party.name);
            $('#party_type').val(response.order.party.type);

            let imageSrc = (response.order.image);
            $('#order_image').attr('src', imageSrc);

            var details = response.details;
            var dropdown = $('.style-dropdown-container');
            dropdown.empty();
            dropdown.append('<option value="">Select a Style</option>');
            dropdown.append('<option value="all">All</option>');

            details.forEach(function (detail) {
                dropdown.append('<option data-color="' + detail.color + '" data-shipment_date="' + detail.shipment_date + '" data-unit_price="' + detail.unit_price + '" data-qty="' + detail.qty + '" data-total_price="' + detail.total_price + '" value="' + detail.style + '">' + detail.style + '</option>');
            });
        }
    });

});

$('.costing-style').on('change', function () {

    let selectedOption = $(".costing-style option:selected");
    let selectedValue = selectedOption.val();

    if (selectedValue === "all") {
        $('.all-hide').addClass('d-none');
        $('.all-hide').val('');
        let totalTotalPrice = 0;
        let totalUnitPrice = 0;
        var numberOfOptions = 0;
        let totalQty = 0;
        $('.style-dropdown-container option[data-total_price]').each(function () {
            totalTotalPrice += parseFloat($(this).data('total_price'));
        });
        $('.style-dropdown-container option[data-unit_price]').each(function () {
            totalUnitPrice += parseFloat($(this).data('unit_price'));
            numberOfOptions++;
        });
        $('.style-dropdown-container option[data-unit_price]').each(function () {
            totalQty += parseFloat($(this).data('qty'));
        });

        $('#quantity').val(totalQty.toFixed(3));
        $('#lc').val(totalTotalPrice.toFixed(3));

        var averageUnitPrice = totalUnitPrice / numberOfOptions;
        $('#unit_price').val(averageUnitPrice.toFixed(3));
        $('#unit_price').closest('td').prev().text('Avg. Unit Price:');

    } else {
        $('.all-hide').removeClass('d-none');
        $('#color').val(selectedOption.data("color"));
        $('#shipment_date').val(selectedOption.data("shipment_date"));
        $('#quantity').val(selectedOption.data("qty"));
        $('#unit_price').val(selectedOption.data("unit_price"));
        $('#unit_price').closest('td').prev().text('Unit Price:');
        $('#lc').val(selectedOption.data("total_price"));
    }
    $('.count-total').trigger('input');
    $('.yarn-qty').trigger('input');
    $('.knit-qty').trigger('input');
    $('.dfa-qty').trigger('input');
    $('.accessories-qty').trigger('input');
    $('.finance-value').trigger('input');
    $('.deferred-value').trigger('input');
    $('.machine').trigger('input');
});

// ###### Fabric calculation & accessories START. ######
// count length
$(document).on('input', '.count-total', function() {
    var length = $(this).data('length');  // unique id
    countTotal(length);
})

function countTotal(length) {
    let lc = $('#lc').val(); // Lc value

    //yarn calculation
    let yarn_unit_price = parseFloat($('.yarn-unit-price.'+length).val());
    let yarn_qty        = parseFloat($('.yarn-qty.'+length).val());
    let yarn_cost       = yarn_unit_price * yarn_qty;
    $('.yarn-cost.'+length).val(yarn_cost.toFixed(3));
    countTotalYarnCost();
    // pre cost
    let yarn_Pre_cal = (yarn_cost * 100)/lc;
    $('.yarn-pre-cost.'+length).val(yarn_Pre_cal.toFixed(3));
    countTotalYarnPreCost();

    //knitting calculation
    let knit_unit_price = parseFloat($('.knit-unit-price.'+length).val());
    let knit_qty        = parseFloat($('.knit-qty.'+length).val());
    let knit_cost       = knit_unit_price * knit_qty;
    $('.knit-cost.'+length).val(knit_cost.toFixed(3));
    countTotalKnitCost();
    // pre cost
    let knit_pre_cal = (knit_cost * 100)/lc;
    $('.knit-pre-cost.'+length).val(knit_pre_cal.toFixed(3));
    countTotalKnitPreCost();

    //dfa calculation
    let dfa_unit_price = parseFloat($('.dfa-unit-price.'+length).val());
    let dfa_qty        = parseFloat($('.dfa-qty.'+length).val());
    let dfa_cost       = dfa_unit_price * dfa_qty;
    $('.dfa-cost.'+length).val(dfa_cost.toFixed(3));
    countTotalDfaCost();
    // pre cost
    let dfa_pre_cal = (dfa_cost * 100)/lc;
    $('.dfa-pre-cost.'+length).val(dfa_pre_cal.toFixed(3));
    countTotalDfaPreCost();

    // accessories calculation
    let accessories_unit_price = parseFloat($('.accessories-unit-price.'+length).val());
    let accessories_qty        = parseFloat($('.accessories-qty.'+length).val());
    let accessories_cost       = accessories_unit_price * accessories_qty;
    $('.accessories-cost.'+length).val(accessories_cost.toFixed(3));
    countTotalAccessoriesCost();
    // pre cost
    let accessories_pre_cal = (accessories_cost * 100)/lc;
    $('.accessories-pre-cost.'+length).val(accessories_pre_cal.toFixed(3));
    countTotalAccessoriesPreCost();

    // print calculation
    let process_loss = parseFloat($('.order-qty.'+length).val());
    let order_qty    = parseFloat($('.process-loss.'+length).val());
    let cutting_qty  = process_loss * order_qty;
    let unit_dzn    = parseFloat($('.unit.'+length).val());
    let total_value = (unit_dzn * cutting_qty)/12
    $('.cutting-qty.'+length).val(cutting_qty.toFixed(3));
    $('.total-value.'+length).val(total_value.toFixed(3));
    $('.print-cost.'+length).val(total_value.toFixed(3));
    countTotalPrintCost();
    // pre cost
    let print_Pre_cal = (total_value * 100)/lc;
    $('.print-pre-cost.'+length).val(print_Pre_cal.toFixed(3));
    countTotalPrintPreCost();
}
function countTotalYarnCost() {
    var total_yarn_cost = 0;
    $('.yarn-cost').each(function () {
        total_yarn_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('.total-yarn-cost').text(total_yarn_cost);
    $('#yarn_cost').val(total_yarn_cost); // hidden value
    totalFab();
}
function countTotalYarnPreCost() {
    var total_yarn_pre_cost = 0;
    $('.yarn-pre-cost').each(function () {
        total_yarn_pre_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('.total-yarn-pre-cost').text(total_yarn_pre_cost);
    $('#pre_cost_desc_yarn').val(total_yarn_pre_cost); // hidden value
    totalFab();
}

function countTotalKnitCost(){
    var total_knit_cost = 0;
    $('.knit-cost').each(function () {
        total_knit_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('.total-knit-cost').text(total_knit_cost);
    $('#knitting_cost').val(total_knit_cost); // hidden value
    totalFab();
}
function countTotalKnitPreCost(){
    let total_knit_pre_cost = 0;
    $('.knit-pre-cost').each(function () {
        total_knit_pre_cost += parseFloat($(this).val() && !isNaN($(this).val()) ? $(this).val() : 0)
    });
    $('.total-knit-pre-cost').text(total_knit_pre_cost);
    $('#pre_cost_desc_knitting').val(total_knit_pre_cost); // hidden value
    totalFab();
}

function countTotalDfaCost(){
    var total_dfa_cost = 0;
    $('.dfa-cost').each(function () {
        total_dfa_cost += parseFloat($(this).val() && !isNaN($(this).val()) ? $(this).val() : 0);
    });
    $('.total-dfa-cost').text(total_dfa_cost)
    $('#dfa_cost').val(total_dfa_cost); // hidden value
    totalFab();
}
function countTotalDfaPreCost(){
    var total_dfa_pre_cost = 0;
    $('.dfa-pre-cost').each(function () {
        total_dfa_pre_cost += parseFloat($(this).val() && !isNaN($(this).val()) ? $(this).val() : 0)
    });
    $('.total-dfa-pre-cost').text(total_dfa_pre_cost);
    $('#pre_cost_desc_dfa').val(total_dfa_pre_cost); // hidden value
    totalFab();
}

function countTotalAccessoriesCost(){
    let total_accessories_cost = 0;
    $('.accessories-cost').each(function () {
        total_accessories_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('.total-accessories-cost').text(total_accessories_cost);
    $('#accessories_cost').val(total_accessories_cost); // hidden value
    totalMakingCost();
}

function countTotalAccessoriesPreCost(){
    let total_accessories_pre_cost = 0;
    $('.accessories-pre-cost').each(function () {
        total_accessories_pre_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('.total-accessories-pre-cost').text(total_accessories_pre_cost);
    $('#pre_cost_desc_accessories').val(total_accessories_pre_cost); // hidden value
    totalMakingCost();
}

function countTotalPrintCost(){
    var total_print_cost = 0;
    $('.print-cost').each(function () {
        total_print_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('.total-print-cost').text(total_print_cost);
    $('#print_cost').val(total_print_cost); // hidden value
    totalMakingCost();
    grandTotal();

}
function countTotalPrintPreCost(){
    var total_print_pre_cost = 0;
    $('.print-pre-cost').each(function () {
        total_print_pre_cost += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('.total-print-pre-cost').text(total_print_pre_cost);
    $('#pre_cost_desc_print').val(total_print_pre_cost); // hidden value
    totalMakingCost();
}


// ###### Fabric calculation & accessories END. ######

// yarn calculation
$(document).on('input', '.yarn-qty', function () {
    let total_yarn_qty = 0;
    $('.yarn-qty').each(function () {
        total_yarn_qty += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('.total-yarn-qty').text(total_yarn_qty);
    $('#yarn_qty').val(total_yarn_qty); // hidden value
    totalFab();
})

// knitting calculation
$(document).on('input', '.knit-qty', function () {
    var total_knit_qty = 0;
    $('.knit-qty').each(function () {
        total_knit_qty += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0)
    });
    $('.total-knit-qty').text(total_knit_qty);
    $('#knitting_qty').val(total_knit_qty); // hidden value
    totalFab();
})

// dfa (Dyeing+Finishing, AOP) calculation
$(document).on('input', '.dfa-qty', function () {
    var total_dfa_qty = 0;
    $('.dfa-qty').each(function () {
        total_dfa_qty += parseFloat($(this).val() && !isNaN($(this).val()) ? $(this).val() : 0)
    });
    $('.total-dfa-qty').text(total_dfa_qty);
    $('#dfa_qty').val(total_dfa_qty); // hidden value
    totalFab();
})

// total fabric calculation
function totalFab() {
    //total quantity
    let total_yarn_qty = parseFloat($('.total-yarn-qty').text()) + parseFloat($('.total-knit-qty').text()) + parseFloat($('.total-dfa-qty').text());
    $('.total-fabric-qty').text(total_yarn_qty);
    $('#fabric_qty').val(total_yarn_qty); // hidden value
    //total cost
    let total_yarn_cost = parseFloat($('.total-yarn-cost').text()) + parseFloat($('.total-knit-cost').text()) + parseFloat($('.total-dfa-cost').text());
    $('.total-fabric-cost').text(total_yarn_cost);
    $('#fabric_cost').val(total_yarn_cost); // hidden value
    //total pre-cost
    let total_yarn_pre_cost = parseFloat($('.total-yarn-pre-cost').text()) + parseFloat($('.total-knit-pre-cost').text()) + parseFloat($('.total-dfa-pre-cost').text());
    $('.total-fabric-pre-cost').text(total_yarn_pre_cost.toFixed(3));
    $('#pre_cost_desc_fabric').val(total_yarn_pre_cost); // hidden value
    totalMakingCost();
}

//accessories
$(document).on('input', '.accessories-qty', function () {
    var total_accessories_qty = 0;
    $('.accessories-qty').each(function () {
        total_accessories_qty += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('.total-accessories-qty').text(total_accessories_qty);
    $('#accessories_qty').val(total_accessories_qty); // hidden value
    totalMakingCost();
})

$(document).on('input', '.finance-value', function () {
    let finance_value = parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    let lc = $('#lc').val(); // Lc value
    let cal = (finance_value/100) * lc;
    $(".finance-cost").val(cal.toFixed(3));
    // pre cost
    $('.finance-pre-cost').val(finance_value.toFixed(3));
    grandTotal();
})

$(document).on('input', '.deferred-value', function () {
    let deferred_value = parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    let lc = $('#lc').val(); // Lc value
    let cal = (deferred_value/100) * lc;
    $(".deferred-cost").val(cal.toFixed(3));
    // pre cost
    $('.deferred-pre-cost').val(deferred_value.toFixed(3));
    grandTotal();
})

// Total fabric & accessories cost
function totalMakingCost(){
    //total-cost
    let total_cost = parseFloat($('.total-fabric-cost').text()) + parseFloat($('.total-accessories-cost').text());
    $('.total-making-cost').text(total_cost.toFixed(3));
    $('#total_making_cost').val(total_cost); // hidden value

    //pre-cost
    let pre_cost = parseFloat($('.total-fabric-pre-cost').text()) + parseFloat($('.total-accessories-pre-cost').text());
    $('.total-making-pre-cost').text(pre_cost.toFixed(3));
    $('#total_making_pre_cost').val(pre_cost); // hidden value
    grandTotal();
    factory();
}

$('#other_cost').on('input', grandTotal);

// Grand total calculation
function grandTotal() {
    //grand-cost
    let grand_cost = 0;
     grand_cost = parseFloat($('.total-fabric-cost').text()) + parseFloat($('.total-accessories-cost').text()) +
    parseFloat($('.finance-cost').val()) + parseFloat($('.deferred-cost').val()) + parseFloat($('#other_cost').val() && !isNaN($('#other_cost').val()) ? $('#other_cost').val() : 0);

    $('.grand-total-cost').text(grand_cost.toFixed(3));

    $('#grand_cost').val(grand_cost); // hidden value

    //grand-pre-cost
    let grand_pre_cost = parseFloat($('.total-fabric-pre-cost').text()) + parseFloat($('.total-accessories-pre-cost').text()) +
        parseFloat($('.finance-pre-cost').val()) + parseFloat($('.deferred-pre-cost').val());

    $('.grand-total-pre-cost').text(grand_pre_cost.toFixed(3));
    $('#pre_cost_desc_grand').val(grand_pre_cost); // hidden value

    factory();

}


function factory(){
    let lc =parseFloat( $('#lc').val()) // Lc value

    //FACTORY CM TOTAL
    let factory_total =   parseFloat($('#total_making_cost').val()) ;
    $('#factory_cm_cost').text(factory_total.toFixed(3));
    $('#factoryCmCost').val(factory_total); // hidden field

    let factory_total_pre = parseFloat($('#total_making_pre_cost').val());
    $('#factory_cm_pre_cost').text(factory_total_pre);
    $('#factoryCmPreCost').val(factory_total_pre); // hidden field

    // TOTAL EXPENSE
    let total_expense =  parseFloat($('.grand-total-cost').text());
    $('#total_expense_cost').text(total_expense.toFixed(3));
    $('#totalExpenseCost').val(total_expense); // hidden field

    let total_expense_pre_cost = parseFloat($('.grand-total-pre-cost').text());
    $('#total_expense_pre_cost').text(total_expense_pre_cost.toFixed(3));
    $('#totalExpensePreCost').val(total_expense_pre_cost); // hidden field

    // NET EARNING
    let net_earning_cost = lc - total_expense;
    net_earning_cost = net_earning_cost.toFixed(3)
    net_earning_cost = isFinite(net_earning_cost) ? net_earning_cost : 0;
    $('#net_earning_cost').text(net_earning_cost);
    $('#netEarningCost').val(net_earning_cost); // hidden field

    let net_earning_pre_cost = (net_earning_cost * 100)/lc;
    net_earning_pre_cost = net_earning_pre_cost.toFixed(3)
    net_earning_pre_cost = isFinite(net_earning_pre_cost) ? net_earning_pre_cost : 0;
    $('#net_earning_pre_cost').text(net_earning_pre_cost);
    $('#netEarningPreCost').val(net_earning_pre_cost); // hidden field
}
/** ........create form end........ */