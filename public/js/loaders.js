
$( document ).ready(function () {
    window.showAppLoader = function () {
        $( appLoaderImageElement ).show();
    }

    window.hideAppLoader = function () {
        setTimeout(function(){$( appLoaderImageElement ).hide()}, 1000);// Delay added deliberately for testing
    }
});