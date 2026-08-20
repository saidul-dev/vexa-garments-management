"use strict";

var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
$.ajaxSetup({ headers: { "X-CSRF-TOKEN": CSRF_TOKEN } });

var $savingLoader = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>',

$ajaxform = $(".ajaxform");
$ajaxform.initFormValidation(),
$(document).on("submit", ".ajaxform", function (e) {
    e.preventDefault();
    let t = $(this).find(".submit-btn"),
        a = t.html();
    $ajaxform.valid() &&
    $.ajax({
        type: "POST",
        url: this.action,
        data: new FormData(this),
        dataType: "json",
        contentType: !1,
        cache: !1,
        processData: !1,
        beforeSend: function () {
            t.html($savingLoader).attr("disabled", !0);
        },
        success: function (e) {
            t.html(a).attr("disabled", false);
            if (e.redirect) {
                t.html(a).removeClass("disabled").attr("disabled", !1);
                window.sessionStorage.hasPreviousMessage = true;
                window.sessionStorage.previousMessage = e.message ?? null;
                if (e.another_page) {
                    window.open(e.another_page, '_blank');
                }
                location.href = e.redirect;
            }
        },
        error: function (e) {
            t.html(a).attr("disabled", !1), Notify("error", e);
        },
    });
});

var $ajaxform_instant_reload = $(".ajaxform_instant_reload");
$ajaxform_instant_reload.initFormValidation(),
$(document).on("submit", ".ajaxform_instant_reload", function (e) {
    e.preventDefault();
    let t = $(this).find(".submit-btn"),
        a = t.html();
    $ajaxform_instant_reload.valid() &&
    $.ajax({
        type: "POST",
        url: this.action,
        data: new FormData(this),
        dataType: "json",
        contentType: !1,
        cache: !1,
        processData: !1,
        beforeSend: function () {
            t.html($savingLoader).addClass("disabled").attr("disabled", !0);
        },
        success: function (e) {
            t.html(a).removeClass("disabled").attr("disabled", !1), (window.sessionStorage.hasPreviousMessage = !0), (window.sessionStorage.previousMessage = e.message ?? null), e.redirect && (location.href = e.redirect);
        },
        error: function (e) {
            t.html(a).removeClass("disabled").attr("disabled", !1), showInputErrors(e.responseJSON), Notify("error", e);
        },
    });
});

function notification(e, t) {
    let a;
    (a = "success" == e ? "fa fa-check-circle" : "error" == e ? "fa fa-times-circle" : "fa fa-info-circle"),
        Lobibox.notify(e, {
            pauseDelayOnHover: !0,
            continueDelayOnInactiveTab: !1,
            icon: a,
            sound: !1,
            position: "top right",
            showClass: "zoomIn",
            hideClass: "zoomOut",
            size: "mini",
            rounded: !0,
            width: 250,
            height: "auto",
            delay: 2e3,
            msg: t,
        });
}

function ajaxSuccess(e, t) {
    e.redirect ? (e.message && ((window.sessionStorage.hasPreviousMessage = !0), (window.sessionStorage.previousMessage = e.message ?? null)), (location.href = e.redirect)) : e.message && Notify("success", e);
}

function clean(e) {
    return (e = (e = e.replace(/ /g, "-")).replace(/[^A-Za-z0-9\-]/, "")).toLowerCase();
}

$(".init_form_validation").initFormValidation();
