"use strict";

// edit modal
$('.edit-btn').each(function () {
    let container = $(this);
    let service = container.data('id');
    let id = service;
    $('#edit_' + service).on('click', function () {
        // alert($('#edit_'+service).data('holder-name'));
        $('#holder-name').val($('#edit_' + service).data('holder-name'));
        $('#bank-name').val($('#edit_' + service).data('bank-name'));
        $('#account-number').val($('#edit_' + service).data('account-number'));
        $('#branch-name').val($('#edit_' + service).data('branch-name'));
        $('#routing-number').val($('#edit_' + service).data('routing-number'));
        $('#balance').val($('#edit_' + service).data('balance'));
        let editactionroute = $(this).data('url');
        $('#editForm').attr('action', editactionroute + '/' + id);
    });
});
//view modal
$('.view-btn').each(function () {
    let container = $(this);
    let service = container.data('id');
    $('#view_' + service).on('click', function () {
        $('#view-holder-name').text($('#view_' + service).data('holder-name'));
        $('#view-bank-name').text($('#view_' + service).data('bank-name'));
        $('#view-account-number').text($('#view_' + service).data('account-number'));
        $('#view-branch-name').text($('#view_' + service).data('branch-name'));
        $('#view-routing-number').text($('#view_' + service).data('routing-number'));
        $('#view-balance').text($('#view_' + service).data('balance'));
    });
});

// Transfers
$('.transfer_type').on('change', function () {
    resetTransferForm();
    var transfer_type = $(this).val();
    if (transfer_type == 'bank_to_cash') {
        $('#bank_to').addClass('d-none');
        $('#bank_from').removeClass('d-none');
        $('.cash_type').removeClass('d-none');
        $('.type').addClass('d-none');
    } else if (transfer_type == 'cash_to_bank') {
        $('#bank_from').addClass('d-none');
        $('#bank_to').removeClass('d-none');
        $('.cash_type').addClass('d-none');
        $('.type').addClass('d-none');
    } else if (transfer_type == 'bank_to_bank') {
        $('#bank_from').removeClass('d-none');
        $('#bank_to').removeClass('d-none');
        $('.cash_type').addClass('d-none');
        $('.type').addClass('d-none');
    } else if (transfer_type == 'adjust_bank') {
        $('.type').removeClass('d-none');
        $('.cash_type').addClass('d-none');
        $('#bank_from').addClass('d-none');
        $('#bank_to').addClass('d-none');
    }
});

$('.adjust_type').on('change', function () {
    BankAdjustType($(this).val())
})

function BankAdjustType(adjust_type) {
    if (adjust_type == 'deposit') {
        $('#bank_from').addClass('d-none');
        $('#bank_to').removeClass('d-none');
    } else if (adjust_type == 'withdraw') {
        $('#bank_to').addClass('d-none');
        $('#bank_from').removeClass('d-none');
    }
    resetTransferForm(disabled_field = false)
}

function resetTransferForm(disabled_field = true) {
    $('.amount').val('');
    $('.bank_to').val('');
    $('.bank_from').val('');
    $('.cash_type').val('');
    if (disabled_field) {
        $('.adjust_type').val('');
    }
}

// PARTY JS START

$('.opening_balance_type').on('change', function (e) {
    showPaymentType();
})

function showPaymentType() {
    var isAdvancePayment = $('.opening_balance_type').val() == 'advance_payment';
    $('.receivable_type').toggleClass('d-none', !isAdvancePayment);
    // $('.bank_id, .receivable_type_val').val('').toggleClass('d-none', !isAdvancePayment);
}


$('.receivable_type_val').on('change', function () {
    if ($(this).val() == 'bank') {
        $('.bank_id').removeClass('d-none');
    } else {
        $('.bank_id').addClass('d-none');
    }
});

// PARTY JS END

// CHEQUE JS

$('.view-cheque').on('click', function () {
    $('.amount').text($(this).data('amount'))
    $('.status').text($(this).data('status'))
    $('.bank_name').text($(this).data('bank_name'))
    $('.cheque_no').text($(this).data('cheque_no'))
    $('.issue_date').text($(this).data('issue_date'))
    $('.party_name').text($(this).data('party_name'))
    $('#cheque-view').modal('show');
});

// CREDIT VOUCHER START

$('.credit_party_id').on('change', function () {
    $('.due-by-invoice').text('');
    var payment_from = $(this).val();
    var url = $(this).find('option:selected').data('url');
    var type = $(".credit_party_id option:selected").data("type");
    if (url) {
        getInvoicesByParty(url);
    } else {
        $('.invoice_label').text("Select");
        $('.income_id').html('<option value="">Select</option>');
    }

    if (payment_from != 'others') {
        inputFieldsForParty(type);
    } else {
        $('.amount').val('');
        $('.party-balance').text('');
        $('.voucher_no').addClass('d-block');
        $('.credit_bill_type').addClass('d-none');
        $('.invoice_label').text("Select Receivable Source");
        $('.credit_payment_method option[value="party_balance"]').remove();
    }
})

function getInvoicesByParty(url) {
    $.ajax({
        type: "GET",
        url: url,
        success: function (res) {
            var invoices = '<option value="">Select</option>';
            $.each(res, function(index, income) {
                invoices += '<option data-total_due="' + income.total_due + '" value="' + income.id + '">' + income.category_name + '</option>';
            });
            $('.income_id').html(invoices);
        },
        error: function (e) {
            Notify("error", null, "Please reload this page & try again.");
        },
    });
}

function inputFieldsForParty(type) {
    $('.amount').val('');
    $('.invoice_label').text('Select Invoice');
    var party_balance = $(".credit_party_id option:selected").data("party_blnc");

    if ($('.credit_payment_method').val() != 'party_balance') {
        $('.credit_bill_type').removeClass('d-none')
    }

    if (type == 'supplier') {
        $('.credit_bill_type').addClass('d-none');
        $('.bill_type_option').val('balance_withdraw');
        $('.party-balance').text('Balance: '+party_balance);
        $('.credit_payment_method option[value="party_balance"]').remove();

        $('.bill_type_option option[value="due_bill"]').remove();
        $('.bill_type_option option[value="advance_payment"]').remove();

        if ($('.bill_type_option option[value="balance_withdraw"]').length === 0) {
            $('.bill_type_option').append('<option value="balance_withdraw">Balance withdraw</option>');
        }

    } else if (type == 'buyer' || type == 'customer') {
        if ($('.income_id').val()) {
            $('.credit_bill_type').addClass('d-none');
        } else {
            $('.credit_bill_type').removeClass('d-none');
        }
        $('.bill_type_option option[value="balance_withdraw"]').remove();

        $('.party-balance').text('Due: '+party_balance);
        if ($('.credit_payment_method option[value="party_balance"]').length === 0) {
            $('.credit_payment_method').append('<option value="party_balance">Wallet</option>');
        }
        if ($('.bill_type_option option[value="due_bill"]').length === 0) {
            $('.bill_type_option').append('<option value="due_bill">Due Bill</option>');
        }
        if ($('.bill_type_option option[value="advance_payment"]').length === 0) {
            $('.bill_type_option').append('<option value="advance_payment">Advance Payment</option>');
        }
    } else {
        $('.party-balance').text('');
    }
}

$('.credit_payment_method').on('change', function (e) {
    var payment_method = $(this).val();
    var payment_from = $('.credit_party_id').val();
    var type = $(".credit_party_id option:selected").data("type");

    if (payment_method == 'cheque') {
        $('.cheque_input').removeClass('d-none');
        $('.bank_cheque_input').removeClass('d-none');
    } else if (payment_method == 'bank') {
        $('.cheque_input').addClass('d-none');
        $('.bank_cheque_input').removeClass('d-none');
    } else if (payment_method == 'party_balance') {
        if (payment_from != 'others' && type != 'supplier') {
            $('.credit_bill_type').addClass('d-none');
        }
        $('.cheque_input').addClass('d-none');
        $('.bank_cheque_input').addClass('d-none');
        $('.bill_type_option').val('due_bill');
    } else {
        $('.cheque_input').addClass('d-none');
        $('.bank_cheque_input').addClass('d-none');
    }
});

$('.income_id').on('change', function () {

    var type = $(".credit_party_id option:selected").data("type");
    let total_due = $(this).find('option:selected').data('total_due');

    if (total_due) {
        $('.amount').val(total_due);
        $('.bill_type_option').val('due_bill');
        $('.credit_bill_type').addClass('d-none');
        $('.due-by-invoice').removeClass('d-none').text(total_due);
    } else {
        $('.amount').val('');
        $('.bill_type_option').val('');
        $('.due-by-invoice').addClass('d-none').text('0');
    }
    if ($(this).val()) {
        $('.voucher-input').val('');
        $('.voucher_no').addClass('d-none');
        $('.credit_bill_type').addClass('d-none');
    } else {
        $('.voucher-input').val('');
        $('.voucher_no').removeClass('d-none');
        if (type == 'buyer' || type == 'customer') {
            $('.credit_bill_type').removeClass('d-none');
        }
    }
})

// CREDIT VOUCHER END

// DEBIT VOUCHER START

$('.debit_payment_method').on('change', function (e) {
    var payment_method = $(this).val();
    var type = $(".party_id option:selected").data("type");
    if (payment_method == 'cheque') {
        $('.cheque_input').removeClass('d-none');
        $('.bank_cheque_input').removeClass('d-none');
    } else if (payment_method == 'bank') {
        $('.cheque_input').addClass('d-none');
        $('.bank_cheque_input').removeClass('d-none');
    } else if (payment_method == 'party_balance') {
        $('.bill_type').addClass('d-none');
        $('.cheque_input').addClass('d-none');
        $('.bank_cheque_input').addClass('d-none');
        $('.bill_type_option').val('due_bill');
    } else {
        $('.cheque_input').addClass('d-none');
        $('.bank_cheque_input').addClass('d-none');
    }
});

$('.party_id').on('change', function () {
    var payment_from = $(this).val();
    var type = $(".party_id option:selected").data("type");
    var url = $(this).find('option:selected').data('url');
    if (url) {
        getExpensesByParty(url);
    } else {
        $('.invoice_label').text("Select");
        $('.income_id').html('<option value="">Select</option>');
    }

    if (payment_from != 'others') {
        expenseFieldsForParty(type);
    } else {
        $('.amount').val('');
        $('.voucher_no').addClass('d-block');
        $('.bill_type').addClass('d-none');
        $('.invoice_label').text("Select payment method");
        $('.debit_payment_method option[value="party_balance"]').remove();
    }
})

function getExpensesByParty(url) {
    $.ajax({
        type: "GET",
        url: url,
        success: function (res) {
            var expenses = '<option value="">Select</option>';
            $.each(res, function(index, expense) {
                expenses += '<option data-total_due="' + expense.total_due + '" value="' + expense.id + '">' + expense.category_name + '</option>';
            });
            $('.expense_id').html(expenses);
        },
        error: function (e) {
            Notify("error", null, "Please reload this page & try again.");
        },
    });
}

function expenseFieldsForParty(type) {
    $('.amount').val('');
    $('.invoice_label').text('Select Expense');
    var party_balance = $(".party_id option:selected").data("party_blnc");

    if ($('.debit_payment_method').val() != 'party_balance') {
        $('.bill_type').removeClass('d-none')
    }
    if (type == 'buyer' || type == 'customer') {

        $('.bill_type').addClass('d-none');
        $('.bill_type_option').val('balance_withdraw');
        $('.party-balance').text('Balance: $'+party_balance);
        $('.debit_payment_method option[value="party_balance"]').remove();

    } else if (type == 'supplier') {
        if ($('.expense_id').val()) {
            $('.bill_type').addClass('d-none');
        } else {
            $('.bill_type').removeClass('d-none');
        }

        $('.party-balance').text(party_balance);

        if ($('.debit_payment_method option[value="party_balance"]').length === 0) {
            $('.debit_payment_method').append('<option value="party_balance">Wallet</option>');
        }
    }
}


$('.expense_id').on('change', function () {

    var type = $(".party_id option:selected").data("type");
    let total_due = $(this).find('option:selected').data('total_due');

    if (total_due) {
        $('.amount').val(total_due);
        $('.bill_type').addClass('d-none');
        $('.bill_type_option').val('due_bill');
        $('.due-by-invoice').removeClass('d-none').text(total_due);
    } else {
        $('.amount').val('');
        $('.bill_type_option').val('');
        $('.due-by-invoice').addClass('d-none').text('0');
    }
    if ($(this).val()) {
        $('.voucher-input').val('');
        $('.voucher_no').addClass('d-none');
        $('.bill_type').addClass('d-none');
    } else {
        $('.voucher-input').val('');
        $('.voucher_no').removeClass('d-none');
        if (type == 'supplier') {
            $('.bill_type').removeClass('d-none');
        }
    }
})

// DEBIT VOUCHER END

/** Cash in Hand Start */
//view modal
$('.cash-view-btn').each(function () {
    let container = $(this);
    let service = container.data('id');
    $('#view_' + service).on('click', function () {
        $('#cash_account_name').text($('#view_' + service).data('account-name'));
        $('#cash_bank_name').text($('#view_' + service).data('bank-name'));
        $('#cash_type').text($('#view_' + service).data('type'));
        $('#cash_amount').text($('#view_' + service).data('amount'));
        $('#cash_date').text($('#view_' + service).data('date'));
    });
});
/** Cash in Hand End */


/** SHIPMENT, SAMPLE, PRODUCTION AUTO INPUT  */

$('.order-id').on('change', function() {
    var url = $('#url').val();
    var model = $('#url').data('model');

    $.ajax({
        type: 'GET',
        url: url+'?id='+$(this).val(),
        success: function (res) {
            // check retrieve data for shipment
            var details;
            // shipment
            if (model === 'Shipment'){
                $('#shipment_party_name').val(res.order.party.name);
                details =  res.order.order_details;

                let lastAddedDiv = $('.party-name');
                $('.duplicate-feature2').remove();
                createShipmentDiv(details, lastAddedDiv);

            }
            // Sample
            else if (model === 'Sample'){
                $('#consignee').val(res.order.invoice_details['consignee']);
                 details =  res.order.order_details;
                var qty = 0;
                var unit_price = 0;
                var total_price = 0;
                var tbody = $('#erp-table tbody'); // Get the table body
                tbody.find('.duplicate-one').remove(); // Remove existing duplicate rows

                var lastAddedRow = $('.position-relative'); // Keep track of the last added row
                createRows(details, lastAddedRow, qty, unit_price, total_price)
            }
            // Production
            else if (model === 'Production'){
                $('#party_name').val(res.order.party.name);
                 details =  res.order.order_details;
                var lastAddedDiv = $('.party-name');
                $('.duplicate-feature1').remove();
                createNewDiv(details, lastAddedDiv);
            }
        },
    });
});

/** Shipment Start */

function createShipmentDiv(details, lastAddedDiv) {
    details.forEach(function(detail) {
        var newRow = $(
            '<div class="col-lg-12 feature-row duplicate-feature2 sample-form-wrp">' +
                '<button type="button" class="btn btn-secondary service-btn-possition add-more-feature-2">+</button>' +
                '<button type="button" class="btn text-danger trash remove-btn-features"><i class="fas fa-trash"></i></button>' +
                '<div class="grid-3">' +
                    '<div class="grid-items mt-2">' +
                        '<label>Style</label>' +
                        '<input type="text" name="styles[]" required value="'+detail.style+'" class="form-control clear-input" placeholder="Enter Style">' +
                    '</div>' +
                    '<div class="grid-items mt-2">' +
                        '<label>Color</label>' +
                        '<input type="text" name="colors[]" value="'+detail.color+'" class="form-control clear-input" placeholder="Enter Color">' +
                    '</div>' +
                    '<div class="grid-items mt-2">' +
                        '<label>Item</label>' +
                        '<input type="text" name="items[]" required value="'+detail.item+'" class="form-control clear-input" placeholder="Enter Item">' +
                    '</div>' +
                    '<div class="grid-items mt-2">' +
                        '<label>Shipment</label>' +
                        '<input type="date" name="dates[]" required value="'+detail.shipment_date+'" class="form-control clear-input">' +
                    '</div>' +
                    '<div class="grid-items mt-2">' +
                        '<label>Size</label>' +
                        '<input type="text" name="sizes[]" value="" class="form-control clear-input" placeholder="Enter Size">' +
                    '</div>' +
                    '<div class="grid-items mt-2">' +
                        '<label>Garments Qty</label>' +
                        '<input type="number" name="qts[]" required value="'+detail.qty+'" class="form-control clear-input" placeholder="Enter Garments Qty">' +
                    '</div>' +
                '</div>' +
            '</div>'
        );


        newRow.insertAfter(lastAddedDiv); // Insert the new row after the last added row
        lastAddedDiv = newRow;
    });
}

function disableRemoveFeature2(){
    var trash = $(".trash");
    if(trash.length === 1){
        trash.attr('disabled','disabled');
    }else{
        trash.removeAttr('disabled');
    }
}

$(document).on('click', '.add-more-feature-2', function() {
    $(".duplicate-feature2:last").clone().insertAfter("div.duplicate-feature2:last").addClass('new-row-js');
    $(".feature-title:last").val('');
    $(".feature-value:last").val(0);
    $('.duplicate-feature2:last .clear-input').val('');

    disableRemoveFeature2();
})

$(document).on('click', '.remove-btn-features', function() {
    $(this).closest('.duplicate-feature2').remove();
    disableRemoveFeature2();
});

$(document).on('click', '.duplicate-feature-remove', function() {
    $(this).closest('.duplicate-feature1').remove();
    disableRemoveFeature2();
});

/** SHIPMENT END */

/** Sample start */
//dynamic input field
function disableRemoveFeature() {
    var tr = $(".duplicate-one");
    var trash = $(".remove-one");
    if (tr.length === 1) {
        trash.css("display", "none");
    } else {
        trash.css("display", "block");
    }
}

$(document).on('click', ".add-btn-one", function () {
    if ($('.duplicate-one').length === 0) {
        toastr.warning("Please select an order first.");
        return;
    }
    let length = parseInt($(".duplicate-one").length); // dynamic row count
    $(".duplicate-one:last").clone().insertAfter("tr.duplicate-one:last");
    $('.duplicate-one:last .qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
    $(".qty:last").val('');
    $('.duplicate-one:last .clear-input').val('');

    disableRemoveFeature();
});

$(".remove-one").click(function () {
    $("tr.duplicate-one:last").remove();
    disableRemoveFeature();
});

function createRows(details, lastAddedRow){
    details.forEach(function(detail, loopKey) {
        var newRow = $('<tr class="duplicate-one">' +
            '<td><input type="text" name="styles[]" value="' + detail.style + '" class="form-control clear-input" required placeholder="Style"></td>' +
            '<td><input type="text" name="colors[]" value="' + detail.color + '" class="form-control clear-input" placeholder="Color"></td>' +
            '<td><input type="text" name="items[]" value="' + detail.item + '" class="form-control clear-input" placeholder="Item description"></td>' +
            '<td><input type="number" name="quantities[]" value="' + detail.qty + '" class="form-control count-length qty '+loopKey+'" data-length="'+loopKey+'" required placeholder="Qty"></td>' +
            '<td><input type="text" name="types[]" class="form-control clear-input "></td>' +
            '<td><input type="number" name="sizes[xs][]" class="form-control clear-input "></td>' +
            '<td><input type="number" name="sizes[s][]" class="form-control clear-input "></td>' +
            '<td><input type="number" name="sizes[m][]" class="form-control clear-input "></td>' +
            '<td><input type="number" name="sizes[l][]" class="form-control clear-input "></td>' +
            '<td><input type="number" name="sizes[xl][]" class="form-control clear-input "></td>' +
            '<td><input type="number" name="sizes[xxl][]" class="form-control clear-input "></td>' +
            '<td><input type="number" name="sizes[3xl][]" class="form-control clear-input "></td>' +
            '<td><input type="number" name="sizes[4xl][]" class="form-control clear-input "></td>' +
            '</tr>');

        newRow.insertAfter(lastAddedRow); // Insert the new row after the last added row
        lastAddedRow = newRow; // Update the last added row to the newly added row

    });

}

// reset input field if click
$(".reset-input").on("focus", function() {
    $(this).val("");
});

/** Sample end */

/** Production start */
function createNewDiv(details, lastAddedDiv) {
    details.forEach(function(detail) {
        var newRow = $(
            '<div class="col-lg-12 feature-row duplicate-feature1 sample-form-wrp production-wrap">' +
            '    <button type="button" class="btn text-danger trash remove-btn-features duplicate-feature-remove"><i class="fas fa-trash"></i></button>' +
            '    <div class="grid-4">' +
            '        <div class="grid-items mt-2 order-data">' +
            '            <label>Style</label>' +
            '            <input type="text" name="order_info[style][]" value=" '+detail.style+' " readonly class="form-control" placeholder="Style">' +
            '        </div>' +
            '        <div class="grid-items mt-2 order-data">' +
            '            <label>Item</label>' +
            '            <input type="text" name="order_info[item][]" value=" '+detail.item+' " readonly class="form-control" placeholder="Item">' +
            '        </div>' +
            '        <div class="grid-items mt-2 order-data">' +
            '            <label>Colors</label>' +
            '            <input type="text" name="order_info[color][]" value=" '+detail.color+' " readonly class="form-control" placeholder="Color">' +
            '        </div>' +
            '        <div class="grid-items mt-2 order-data">' +
            '            <label>Order Quantity</label>' +
            '            <input type="number" name="order_info[qty][]" value="' + detail.qty + '" readonly class="form-control" placeholder="Quantity">' +
            '        </div>' +
            '    </div>' +
            '</div>'
    );


        newRow.insertAfter(lastAddedDiv); // Insert the new row after the last added row
        lastAddedDiv = newRow;
    });
}

// Button action
function disableRemoveFeature(){
    var trash = $(".trash");
    if(trash.length === 1) {
        trash.attr('disabled','disabled');
    } else {
        trash.removeAttr('disabled');
    }
}

/** Production end */

/** LOSS PROFIT START */

$('.loss-profit-year').on('change', function () {
    let year = $(this).val();
    var url = $('#url').val();
    $.ajax({
        type: 'GET',
        url: url + '?year=' + year,
        success: function (res) {
            $('.loss-profit-data').html(res.data)
        },
    });

    getLossProfitData(year);
});

/** LOSS PROFIT END */

// DUE COLLECTION START

$('.payment-received').on('click', function() {
    var url = $(this).data('url');
    getInvoicesByParty(url);
    $('.party_id').val($(this).data('id'));
    $('.party-name').text($(this).data('name'));
});

$('.invoice').on('change', function() {
    let invoice_due = $(this).find('option:selected').data('total_due');
    $('.due-amount').val(invoice_due);
    calculateChangeAmount();
})

$('.amount').on('input', function() {
    calculateChangeAmount();
})

function calculateChangeAmount() {
    let paying_amount = parseFloat($('.paying-amount').val(), 2);
    let invoice_due = parseFloat($('.due-amount').val(), 2);

    if (paying_amount > invoice_due) {
        $('.change-lable').text("Change Return");
        $('.change-amount').val(paying_amount - invoice_due);
    } else {
        $('.change-lable').text("Remaining Balance");
        $('.change-amount').val(invoice_due - paying_amount);
    }
}

// DUE COLLECTION END

// LOSS PROFIT : INCOME START
function incomeCsv(){

    var url = $('#get-income-csv').val();
    var tableContent = $('.print-table').html();

    $.ajax({
        type: 'GET',
        url: url,
        data: {
            tableContent: tableContent
        },
        success: function (res) {
            // Handle the response from the controller
            console.log(res);
        },
    });
}
// LOSS PROFIT : INCOME END

// SALARY PAY START

$('.employee_id').on('change', function() {
    let salary = $(this).find('option:selected').data('salary');
    $('.amount').val(salary);
});

// SALARY PAY END

/** Designation Start */
//edit modal
$('.edit-btn').each(function () {
    let container = $(this);
    let id = container.data('id');

    $('#edit_' + id).on('click', function () {
        $('#designation_edit_name').val($('#edit_' + id).data('name'));
        $('#designation_edit_description').val($('#edit_' + id).data('description'));

        let editactionroute = $(this).data('url');
        $('#editForm').attr('action', editactionroute + '/' + id);
    });
});
/** Designation End */

/** Monthly transaction filter start */
$('.transaction-filter-form').on('input', function (e) {
    e.preventDefault();
    
    var table = $(this).attr('table');
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: new FormData(this),
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,
        success: function (res) {
            $(table).html(res.tableData);
            $('#transaction_total_amount').text(res.data.total_amount)
            $('#transaction_credit_amount').text(res.data.credit_amount)
            $('#transaction_debit_amount').text(res.data.debit_amount)
            $('#transaction_bank_balance').text(res.data.bank_balance)

        }
    });
});

$(document).ready(function () {
    function downloadXlsx(data, filename, sheet = 'Sheet 1') {
        let ws = XLSX.utils.aoa_to_sheet(data);
        let wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, sheet);
        XLSX.writeFile(wb, filename);
    }
    
    $('#download-xlsx').on('click', function () {
        let data = [$(this).data('table-headings').split(',')];
        let filename = $(this).data('filename') + '.xlsx';
    
        $('#erp-table tbody tr').each(function (index, row) {
            let rowData = [];
            $(row).find('td').each(function (index, cell) {
                rowData.push($(cell).text());
            });
            data.push(rowData);
        });
        downloadXlsx(data, filename);
    });
    // XLSX END

    function downloadCsv(data, filename) {
        const csvData = data.join('\n');
        const encodedUri = encodeURI('data:text/csv;charset=utf-8,' + csvData);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    $('#download-csv').on('click', function () {
        let data = [$(this).data('table-headings').split(',')];
        let filename = $(this).data('filename') + '.csv';
    
        $('#erp-table tbody tr').each(function (index, row) {
            let rowData = [];
            $(row).find('td').each(function (index, cell) {
                rowData.push(`"${$(cell).text()}"`);
            });
            data.push(rowData.join(','));
        });
    
        downloadCsv(data, filename);
    });
});