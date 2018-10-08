
var jqxhr = function (ajaxSettings, doneCall, failCall, alwaysCall) {

    window.showAppLoader();
    $.ajax(
        ajaxSettings
    )
        .done(function () {
            doneCall();
        })
        .fail(function () {
            failCall();
        })
        .always(function () {
            window.hideAppLoader();
            alwaysCall();
        });
}