$(document).ready(function () {
    "use strict";

    // Auto input filled from order
    $('#booking').on('change', function () {
        var orderId = $(this).val();
        var url = $('#get-order_id').val();
        var model = $('#url').data('model');
        $.ajax({
            type: "GET",
            url: url + `?order_id=` + orderId,
            dataType: "json",
            success: function (res) {
                $('#booking_merchandiser').val(res.order.merchandiser.name);
                $('#booking_yearn_count').val(res.order['yarn_count']);
                $('#booking_fabrication').val(res.order['fabrication']);
                let imageSrc = (res.order['image']);
                $('#booking_image').attr('src', imageSrc);
                var details =  res.order.order_details;

                var qty = 0;
                var unit_price = 0;
                var total_price = 0;


                var tbody = $('#erp-table tbody'); // Get the table body
                tbody.find('.duplicate-one').remove(); // Remove existing duplicate rows

                var lastAddedRow = $('.position-relative'); // Keep track of the last added row

                details.forEach(function(detail, loopKey) {
                    var imageId = 'image_' + loopKey; // Create a unique ID for each image

                    var newRow = $(`<tr class="duplicate-one">
                    <td><input type="text" name="style[]" value="${detail.style}" class="form-control clear-input" required placeholder="Style"></td>
                    <td><input type="text" name="color[]" value="${detail.color}" class="form-control clear-input" placeholder="Color"></td>
                    <td><input type="text" name="item[]" value="${detail.item}" class="form-control clear-input" placeholder="Item description"></td>
                    <td><input type="date" name="shipment_date[]" value="${detail.shipment_date}" required class="form-control clear-input"></td>
                    <td><input type="number" name="qty[]" value="${detail.qty}" class="form-control count-length qty ${loopKey}" data-length="${loopKey}" required placeholder="Qty"></td>
                    <td><input type="number" name="unit_price[]" value="${detail.unit_price}" class="form-control count-length unit_price ${loopKey}" data-length="${loopKey}" required placeholder="Unit price"></td>
                    <td><input type="number" name="total_price[]" value="${detail.total_price}" class="form-control total_price ${loopKey}" placeholder="Total price" readonly data-length="${loopKey}" value="0"></td>
                    <td><input type="text" name="data[desc_garments][]" class="form-control clear-input" placeholder="Description Of Garments"></td>
                    <td><label for="${imageId}" class="remove-position"><input type="file" id="${imageId}" data-id="${imageId}" name="data[images][]" class="table-img form-control reset-img d-none file-input-change" accept="image/*"><img id="${imageId}" src="assets/images/icons/upload2.png" class="table-img justify-content-center"></label></td>
                    <td><input type="text" name="data[pantone][]" class="form-control clear-input" placeholder="Pantone"></td>
                    <td><input type="text" name="data[body_fab][]" class="form-control clear-input" placeholder="Body Fabrication"></td>
                    <td><input type="text" name="data[yarn_count_body][]" class="form-control clear-input" placeholder="Yarn Count For Body"></td>
                    <td><input type="text" name="data[garments_qty_dzn][]" class="form-control clear-input" placeholder="Garments QTY In DZN"></td>
                    <td><input type="text" name="data[body_fab_dzn][]" class="form-control clear-input" placeholder="Consumption Body Fabric In DZN"></td>
                    <td><input type="text" name="data[body_gray_fab][]" class="form-control clear-input" placeholder="Body Gray Fabric In KG"></td>
                    <td><input type="text" name="data[desc_garments_rib][]" class="form-control clear-input" placeholder="Description Of Garments (RIB)"></td>
                    <td><input type="text" name="data[yarn_count_rib][]" class="form-control clear-input" placeholder="Yarn Counts For RIB 1*1"></td>
                    <td><input type="text" name="data[consump_rib_dzn][]" class="form-control clear-input" placeholder="Consumption RIB In DZN"></td>
                    <td><input type="text" name="data[rib_kg][]" class="form-control clear-input" placeholder="RIB In KG"></td>
                    <td><input type="text" name="data[yarn_count_rib_lycra][]" class="form-control clear-input" placeholder="Yarn Counts For RIB 1*1 Lycra 1*1 RIB Yarn- 26/1 Finished DIA 48” Open"></td>
                    <td><input type="text" name="data[receive][]" class="form-control clear-input" placeholder="Receive"></td>
                    <td><input type="text" name="data[balance][]" class="form-control clear-input" placeholder="Balance"></td>
                    <td><input type="text" name="data[gray_body_fab][]" class="form-control clear-input" placeholder="Gray Body Febric"></td>
                    <td><input type="text" name="data[gray_body_rib][]" class="form-control clear-input" placeholder="Graybody RIB (2*1)"></td>
                    <td><input type="text" name="data[revised][]" class="form-control clear-input" placeholder="Revised"></td>
                    <td><input type="text" name="collar_size_qty[40_xs][]" class="form-control clear-input "></td>
                    <td><input type="text" name="collar_size_qty[41_s][]" class="form-control clear-input "></td>
                    <td><input type="text" name="collar_size_qty[42_m][]" class="form-control clear-input "></td>
                    <td><input type="text" name="collar_size_qty[43_l][]" class="form-control clear-input "></td>
                    <td><input type="text" name="collar_size_qty[44_xl][]" class="form-control clear-input "></td>
                    <td><input type="text" name="collar_size_qty[45_xxl][]" class="form-control clear-input "></td>
                    <td><input type="text" name="collar_size_qty[46_3xl][]" class="form-control clear-input "></td>
                    <td><input type="text" name="collar_size_qty[47_4xl][]" class="form-control clear-input "></td>
                    <td><input type="text" name="cuff_color[]" class="form-control clear-input " placeholder="Same"></td>
                    <td><input type="text" name="cuff_solid[37_l][]" class="form-control clear-input "></td>
                    <td><input type="text" name="cuff_solid[38_4xl][]" class="form-control clear-input "></td>
                    <td><input type="text" name="cuff_solid[39_5xl][]" class="form-control clear-input "></td>
                    <td><input type="text" name="cuff_solid[40_6xl][]" class="form-control clear-input "></td>
                    </tr>`);

                    newRow.insertAfter(lastAddedRow); // Insert the new row after the last added row
                    lastAddedRow = newRow; // Update the last added row to the newly added row

                    // total value
                    qty += parseInt(detail.qty);
                    unit_price += parseFloat(detail.unit_price);
                    total_price += parseFloat(detail.total_price);
                });

                $('.total_qty').text(qty);
                $('.total_unit_price').text(unit_price.toFixed(2));
                $('.final_total_price').text(total_price.toFixed(2));
            }
        });
    });

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
        var clonedRow = $(".duplicate-one:last").clone().insertAfter("tr.duplicate-one:last");
        $('.duplicate-one:last .qty').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-one:last .unit_price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        $('.duplicate-one:last .total_price').attr('data-length', length).addClass(`${length}`).removeClass(`${length - 1}`);
        // $(".sl-no:last").val(length + 1); //set sl no. value
        $(".qty:last").val('');
        $(".unit_price:last").val('');
        $(".total_price:last").val('');
        $('.duplicate-one:last .clear-input').val('');

        // Find the input and label elements within the cloned row
        var inputElement = clonedRow.find('input[type="file"]');
        var labelElement = clonedRow.find('label');
        var imgElement = clonedRow.find('.table-img');

        var imageId = 'image' + length;
        labelElement.attr('for', imageId);
        inputElement.attr('id', imageId);

        $('.duplicate-one:last .reset-img').val(null); // Clear the file input

        imgElement.attr('src', '');
        imgElement.attr('src', '/assets/images/icons/upload2.png');

        clonedRow.insertAfter("tr.duplicate-one:last");


        disableRemoveFeature();
    });

    $(".remove-one").click(function () {
        $("tr.duplicate-one:last").remove();
        var length = $(this).data('length');  // unique id
        let qty        = parseFloat($('.qty.'+length).val());
        let unit_price = parseFloat($('.unit_price.'+length).val());
        let total       = qty * unit_price;
        $('.total_price.'+length).val(total.toFixed(2));

        // total quantity
        let total_qty = 0;
        $('.qty').each(function () {
            total_qty += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total_qty').text(total_qty);

        // total unit price
        let total_unit_price = 0;
        $('.unit_price').each(function () {
            total_unit_price += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total_unit_price').text(total_unit_price);

        // final total price
        let final_total_price = 0;
        $('.total_price').each(function () {
            final_total_price += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.final_total_price').text(final_total_price);
        disableRemoveFeature();
    });

    // form input calculation
    $(document).on('input', '.count-length', function() {

        var length = $(this).data('length');  // unique id
        let qty        = parseFloat($('.qty.'+length).val());
        let unit_price = parseFloat($('.unit_price.'+length).val());
        let total       = qty * unit_price;
        $('.total_price.'+length).val(total.toFixed(2));

        // total quantity
        let total_qty = 0;
        $('.qty').each(function () {
            total_qty += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total_qty').text(total_qty);

        // total unit price
        let total_unit_price = 0;
        $('.unit_price').each(function () {
            total_unit_price += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.total_unit_price').text(total_unit_price);

        // final total price
        let final_total_price = 0;
        $('.total_price').each(function () {
            final_total_price += parseFloat($(this).val() && $(this).val() != NaN ? $(this).val() : 0);
        });
        $('.final_total_price').text(final_total_price.toFixed(2));
    })

    // reset input field if click
    $(".reset-input").on("focus", function() {
        $(this).val("");
    });
});