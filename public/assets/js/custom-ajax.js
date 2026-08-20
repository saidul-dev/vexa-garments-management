/** status publish start*/
$('.status-item').each(function () {
    var container = $(this);
    var service = container.data('id');
    $('#status_' + service).on('click', function () {
        var id = $('#status_' + service).data('id');
        var statustext = $('#status_' + service).data('status');

        if ($('#status_' + service).is(":checked")) {
            var status = 1
        } else {
            var status = 0
        }
        ajaxFunction(id, statustext, status);
    });
});

function ajaxFunction(id, statustext, status) {
    $.ajax({
        url: '/publish/status/ajax',
        method: "get",
        dataType: "json",
        data: {
            'status': status, 'id': id, 'statustext': statustext,
        },
        success: function (data) {

            if (data.status == 1) {
                toastr.success(statustext + ' Published');
            } else {
                toastr.success(statustext + ' Unpublished.');
            }
        },
        error: function (data) {
            console.log(data)
            alert('Error occur fetch subcategory action.....!!');
        }
    })
}

/** status publish end */

/** confirm modal start */
$(document).on('click', '.confirm-action', function (event) {
    event.preventDefault();

    let url = $(this).data('action') ?? $(this).attr('href');
    let method = $(this).data('method') ?? 'POST';
    let icon = $(this).data('icon') ?? 'fas fa-warning';

    $.confirm({
        title: "Are you sure?",
        icon: icon,
        theme: 'modern',
        closeIcon: true,
        animation: 'scale',
        type: 'red',
        scrollToPreviousElement: false,
        scrollToPreviousElementAnimate: false,
        buttons: {
            confirm: {
                btnClass: 'btn-red',
                action: function () {
                    event.preventDefault();
                    $.ajax({
                        type: method,
                        url: url,
                        success: function (response) {
                            if (response.redirect) {
                                window.sessionStorage.hasPreviousMessage = true;
                                window.sessionStorage.previousMessage = response.message ?? null;

                                location.href = response.redirect;
                            } else {
                                Notify('success', response)
                            }
                        },
                        error: function (xhr, status, error) {
                            Notify('error', xhr)
                        }
                    })
                }
            },
            close: {
                action: function () {
                    this.buttons.close.hide()
                }
            }
        },
    });
});
/**confirm modal end */

/** filter all from start */
$('.filter-form input, .filter-form select').on('input change', function (e) {
    e.preventDefault();

    var table = $(this).closest('.filter-form').attr('table');
    $.ajax({
        type: "POST",
        url: $(this).closest('.filter-form').attr('action'),
        data: new FormData($(this).closest('.filter-form')[0]), // Use the form
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,
        success: function (res) {
            $(table).html(res.data);
        }
    });
});
/** filter all from  end */

/** Loss-profit start */
function getLossProfitData(year) {
    var url = $('#get-loss-profit').val();
    $.ajax({
        type: "GET",
        url: url + '?year=' + year,
        dataType: "json",
        success: function (res) {
            $('#loss_profit_total_sale').text(res.total_sale);
            $('#loss_profit_total_paid').text(res.total_paid);
            $('#loss_profit_total_expense').text(res.total_expense);
            $('#loss_profit_total_profit').text(res.total_profit);
            $('#loss_profit_total_loss').text(res.total_loss);
        }
    });
}
/** Loss-profit end */

// STATUS CHANGE
$(document).ready(function() {
    $('.change-status').on('click', function () {
        var url = $(this).attr('action');
        $('.approve-reject-form').attr('action', url);
        $('#approved-reject-modal').modal('show');
    });

    $('.print-window').on('click', function() {
        window.print();
    });
});

$(document).on('change', '.file-input-change', function () {
    let prevId = $(this).data('id');
    newPreviewImage(this, prevId);
});

// image preview
function newPreviewImage(input, prevId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#' + prevId).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$('.logoutButton').on('click', function() {
    document.getElementById('logoutForm').submit();
});
