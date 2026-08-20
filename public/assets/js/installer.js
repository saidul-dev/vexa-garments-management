"use strict";

$('.checking-env-onchange').on('change', function() {
    let val = $(this).val();
    var element = document.getElementById('environment_text_input');
    if (val == 'other') {
        element.classList.remove('d-none');
    } else {
        element.classList.add('d-none');
    }
});

$('.show-database-settings').on('click', function() {
    document.getElementById('tab2').checked = true;
    return false;
});

$('.show-application-settings').on('click', function() {
    document.getElementById('tab3').checked = true;
    return false;
});

var x = document.getElementById('error_alert');
var y = document.getElementById('close_alert');
if (x || y) {
    y.onclick = function() {
        x.style.display = "none";
    };
}
