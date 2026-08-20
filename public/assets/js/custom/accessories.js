"use strict";

// front calculation
$('.unit-price').on('change', function () {
    let selectedOption = $(".unit-price option:selected")
    let unit_price = selectedOption.data("unit_price");
    $('#unitPrice').val(unit_price);
    cal();
});

$('#qtyUnit').on('input', function () {
    cal();
});

function cal() {
    let qtyUnit = parseFloat($('#qtyUnit').val());
    let unitPrice = parseFloat($('#unitPrice').val());
    let total = qtyUnit * unitPrice;
    if (!isNaN(total)) {
        $('#ttlAmount').val(total);
    }
}

// view modal
$(document).ready(function () {
    $('.view-btn').each(function () {
        let container = $(this);
        let service = container.data('id');
        $('#details-view_' + service).on('click', function () {
            $('#accessories-name').text($('#details-view_' + service).data('accessories-name'));
            $('#party-name').text($('#details-view_' + service).data('party-name'));
            $('#unit-mame').text($('#details-view_' + service).data('unit-name'));
            $('#quantity').text($('#details-view_' + service).data('quantity'));
            $('#unit-price').text($('#details-view_' + service).data('unit-price'));
            $('#total-amount').text($('#details-view_' + service).data('total-amount'));
        });
    });
});
