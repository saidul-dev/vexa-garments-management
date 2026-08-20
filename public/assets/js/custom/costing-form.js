/** ............ Dynamic button start ................ */
$(document).ready(function () {

    "use strict";

    $('.view-costing').on('click', function () {
        var url = $(this).data('url');
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                $('.cost-view-modal').html(response.data);
            },
        });

        $('#costing-view').modal('show');
    });

    // Yarn button
    function disableRemoveFeature() {
        var tr = $(".duplicate-one");
        var trash = $(".remove-one");
        if (tr.length === 1) {
            trash.css("display", "none");
        } else {
            trash.css("display", "block");
        }
    }
    $(".add-btn-one").click(function () {
        let length = parseInt($(".duplicate-one").length);
        $(".duplicate-one:last").clone().insertAfter("tr.duplicate-one:last");
        $('.duplicate-one:last .yarn-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-one:last .yarn-price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-one:last .yarn-total').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $(".yarn-qty:last").val("");
        $(".yarn-price:last").val("");
        $(".yarn-total:last").val(0);
        $('.duplicate-one:last .clear-input').val('');
        disableRemoveFeature();
    });

    $(".remove-one").click(function () {
        $("tr.duplicate-one:last").remove();
        yarnGrandTotal();
        disableRemoveFeature();
    });

    // Knitting Type
    function disableRemoveFeature2() {
        var tr = $(".duplicate-two");
        var trash = $(".remove-two");
        if (tr.length === 1) {
            trash.css("display", "none");
        } else {
            trash.css("display", "block");
        }
    }

    $(".add-btn-two").click(function () {
        let length = parseInt($(".duplicate-two").length);
        $(".duplicate-two:last").clone().insertAfter("tr.duplicate-two:last");
        $('.duplicate-two:last .knitting-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-two:last .knitting-price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-two:last .knitting-total').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $(".knitting-qty:last").val("");
        $(".knitting-price:last").val("");
        $(".knitting-total:last").val(0);
        $('.duplicate-two:last .clear-input').val('');
        disableRemoveFeature2();
    });

    $(".remove-two").click(function () {
        $("tr.duplicate-two:last").remove();
        knittingGrandTotal();
        disableRemoveFeature2();
    });

    // Dyeing & finishing
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
        $('.duplicate-three:last .dyeing-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-three:last .dyeing-price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-three:last .dyeing-total').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $(".dyeing-qty:last").val("");
        $(".dyeing-price:last").val("");
        $(".dyeing-total:last").val(0);
        $('.duplicate-three:last .clear-input').val('');
        disableRemoveFeature3();
    });

    $(".remove-three").click(function () {
        $("tr.duplicate-three:last").remove();
        dyeingGrandTotal();
        disableRemoveFeature3();
    });

    // print
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
        $('.duplicate-four:last .print-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-four:last .print-price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-four:last .print-total').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $(".print-qty:last").val("");
        $(".print-price:last").val("");
        $(".print-total:last").val(0);
        $('.duplicate-four:last .clear-input').val('');
        disableRemoveFeature4();
    });

    $(".remove-four").click(function () {
        $("tr.duplicate-four:last").remove();
        printGrandTotal();
        disableRemoveFeature4();
    });

    // Trim / Accessories
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
        $('.duplicate-five:last .trim-qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-five:last .trim-price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-five:last .trim-total').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $(".trim-qty:last").val("");
        $(".trim-price:last").val("");
        $(".trim-total:last").val(0);
        $('.duplicate-five:last .clear-input').val('');
        disableRemoveFeature5();
    });

    $(".remove-five").click(function () {
        $("tr.duplicate-five:last").remove();
        trimGrandTotal();
        disableRemoveFeature5();
    });
});
/** ............ Dynamic button end ................ */

/** ...........  form Start .............. */
// dropdown use to input start
$('.order-id').on('change', function () {

    var orderId = $(this).val();
    var url = $('#url').val();
    $.ajax({
        type: "GET",
        url: url + `?order_id=` + orderId,
        dataType: "json",
        success: function (response) {

            $('#party_name').val(response.order.party.name);
            $('#party_type').val(response.order.party.type);
            $('#order_no').val(response.order.order_no);
            $('#fabrication').val(response.order.fabrication);

            var details = response.details;
            var dropdown = $('.style-dropdown-container');
            dropdown.empty();
            dropdown.append('<option value="">Select a Style</option>');
            dropdown.append('<option value="all">All</option>');

            details.forEach(function(detail) {
                dropdown.append('<option data-shipment_date="'+detail.shipment_date+'" data-unit_price="'+detail.unit_price+'" data-qty="'+detail.qty+'" data-total_price="'+detail.total_price+'" value="' + detail.style + '">' + detail.style + '</option>');
            });
        }
    });
});
// dropdown use to input end

$('.costing-style').on('change', function() {

    let selectedOption = $(".costing-style option:selected");
    let selectedValue = selectedOption.val();

    if (selectedValue === "all") {
        $('.all-hide').addClass('d-none');
        $('.all-hide').val('');
        let totalTotalPrice = 0;
        let totalUnitPrice = 0;
        let totalQty = 0;
        $('.style-dropdown-container option[data-total_price]').each(function() {
            totalTotalPrice += parseFloat($(this).data('total_price'));
        });
        $('.style-dropdown-container option[data-unit_price]').each(function() {
            totalUnitPrice += parseFloat($(this).data('unit_price'));
            $('#unit_price').closest('td').prev().text('Avg. Unit Price:');

        });
        $('.style-dropdown-container option[data-unit_price]').each(function() {
            totalQty += parseFloat($(this).data('qty'));
        });
        $('#qty').val(totalQty.toFixed(3));
        $('#lc').val(totalTotalPrice.toFixed(3));
        $('#unit_price').val(totalUnitPrice.toFixed(2));

    }
    else{
        $('.all-hide').removeClass('d-none');
        $('#shipment_date').val(selectedOption.data("shipment_date"));
        $('#qty').val(selectedOption.data("qty"));
        $('#unit_price').val(selectedOption.data("unit_price"));
        $('#lc').val(selectedOption.data("total_price"));
    }

});

// count length
$(document).on('input', '.count-total', function () {
    var length = $(this).data('length'); // unique id
    countTotal(length);
})

function countTotal(length) {
    //yarn calculation
    let yarn_qty = isNaN(parseFloat($('.yarn-qty.' + length).val())) ? 0 : parseFloat($('.yarn-qty.' + length).val());
    let yarn_price = isNaN(parseFloat($('.yarn-price.' + length).val())) ? 0 : parseFloat($('.yarn-price.' + length).val());

    let yarn_total = yarn_qty * yarn_price;
    $('.yarn-total.' + length).val(yarn_total.toFixed(2));
    yarnGrandTotal();
    //Knitting calculation
    let knitting_qty = isNaN(parseFloat($('.knitting-qty.' + length).val())) ? 0 : parseFloat($('.knitting-qty.' + length).val());
    let knitting_price = isNaN(parseFloat($('.knitting-price.' + length).val())) ? 0 : parseFloat($('.knitting-price.' + length).val());

    let knitting_total = knitting_qty * knitting_price;
    $('.knitting-total.' + length).val(knitting_total.toFixed(2));
    knittingGrandTotal()
    //dyeing calculation
    let dyeing_qty = isNaN(parseFloat($('.dyeing-qty.' + length).val())) ? 0 : parseFloat($('.dyeing-qty.' + length).val());
    let dyeing_price = isNaN(parseFloat($('.dyeing-price.' + length).val())) ? 0 : parseFloat($('.dyeing-price.' + length).val());

    let dyeing_total = dyeing_qty * dyeing_price;
    $('.dyeing-total.' + length).val(dyeing_total.toFixed(2));
    dyeingGrandTotal()
    //print calculation
    let print_qty = isNaN(parseFloat($('.print-qty.' + length).val())) ? 0 : parseFloat($('.print-qty.' + length).val());
    let print_price = isNaN(parseFloat($('.print-price.' + length).val())) ? 0 : parseFloat($('.print-price.' + length).val());

    let print_total = print_qty * print_price;
    $('.print-total.' + length).val(print_total.toFixed(2));
    printGrandTotal();
    //trim calculation
    let trim_qty = isNaN(parseFloat($('.trim-qty.' + length).val())) ? 0 : parseFloat($('.trim-qty.' + length).val());
    let trim_price = isNaN(parseFloat($('.trim-price.' + length).val())) ? 0 : parseFloat($('.trim-price.' + length).val());

    let trim_total = trim_qty * trim_price;
    $('.trim-total.' + length).val(trim_total.toFixed(2));
    trimGrandTotal();
}

function yarnGrandTotal() {
    var yarn_grand_total = 0;
    $('.yarn-total').each(function () {
        yarn_grand_total += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('#yarn_total').val(yarn_grand_total.toFixed(2));
    grandTotal();
}

function knittingGrandTotal() {
    var knitting_grand_total = 0;
    $('.knitting-total').each(function () {
        knitting_grand_total += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('#knitting_total').val(knitting_grand_total.toFixed(2));
    grandTotal();
}

function dyeingGrandTotal() {
    var dyeing_grand_total = 0;
    $('.dyeing-total').each(function () {
        dyeing_grand_total += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('#dyeing_total').val(dyeing_grand_total.toFixed(2));
    grandTotal();
}

function printGrandTotal() {
    var print_grand_total = 0;
    $('.print-total').each(function () {
        print_grand_total += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('#print_total').val(print_grand_total.toFixed(2));
    grandTotal();
}

function trimGrandTotal() {
    var trim_grand_total = 0;
    $('.trim-total').each(function () {
        trim_grand_total += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
    });
    $('#trim_total').val(trim_grand_total.toFixed(2));
    grandTotal();
}
$(document).on('input', '.commercial-total', function () {
    let commercial_qty = isNaN(parseFloat($('.commercial-qty').val())) ? 0 : parseFloat($('.commercial-qty').val());
    let commercial_price = isNaN(parseFloat($('.commercial-price').val())) ? 0 : parseFloat($('.commercial-price').val());

    let commercial_total = commercial_qty * commercial_price;
    $('#commercial_total').val(commercial_total.toFixed(2));
    $('.commercial-grand-total').val(commercial_total.toFixed(2));
    grandTotal();
})

$(document).on('input', '.cm-total', function () {
    let cm_qty = isNaN(parseFloat($('.cm-qty').val())) ? 0 : parseFloat($('.cm-qty').val());
    let cm_price = isNaN(parseFloat($('.cm-price').val())) ? 0 : parseFloat($('.cm-price').val());

    let cm_total = cm_qty * cm_price;
    $('#cm_cost_total').val(cm_total.toFixed(2));
    $('.cm-grand-total').val(cm_total.toFixed(2));
    grandTotal();
})

function grandTotal() {
    let total = 0;
    total = parseFloat($('#yarn_total').val()) + parseFloat($('#knitting_total').val()) + parseFloat($('#dyeing_total').val()) + parseFloat($('#print_total').val()) + parseFloat($('#trim_total').val()) + parseFloat($('#commercial_total').val()) + parseFloat($('#cm_cost_total').val());
    $('#grand_total').val(total.toFixed(2));
    let per_dzn = total / 12;
    $('#grand_total_in_dzn').val(per_dzn.toFixed(2))
}

/** ............ Create form End ................ */

/** Note Modal View start */
//Use the id of the form instead of #change
$('.change').change(function () {
    var url = $(this).data('url');
    $('.cost-status').val($(this).val());
    $('.reject-form').attr('action', url);
    $('#approved-modal').modal('show');
});
/** Note Modal view End */
