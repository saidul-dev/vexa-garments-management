"use strict";

$('.hide-pass').click(function () {
    var model = $('#auth').data('model');
    $(this).toggleClass("show-pass");

    // LOGIN
    if (model === 'Login') {
        let passwordInput = $(".password");
        if (passwordInput.attr('type') === "password") {
            passwordInput.attr('type', 'text');
        } else {
            passwordInput.attr('type', 'password');
        }
    }
    // REGISTRATION & RESET PASSWORD
    else {
        let passwordInput = $(this).siblings('input');
        let passwordType = passwordInput.attr('type');
        if (passwordType === 'password') {
            passwordInput.attr('type', 'text');
        } else {
            passwordInput.attr('type', 'password');
        }
    }
});

// Fill email and password fields
$('.login-btn-group').on('click', function() {
    $(".email").val($(this).data('email'));
    $(".password").val($(this).data('password'));
})