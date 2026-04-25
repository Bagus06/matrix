(function($) {
    let toastrQueue = [];
    let toastrActive = false;
    toastr.options = {
        "positionClass": "toast-bottom-right",
        "preventDuplicates": true,
        "showDuration": "100",
        "hideDuration": "500",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $.invyToastr = function(options) {

        const defaults = {
            type: "info",
            message: "",
            toastrOptions: {}
        };

        let settings = $.extend({}, defaults, options);

        toastrQueue.push(settings);
        runToastrQueue();
    };

    function runToastrQueue() {
        if (toastrActive || toastrQueue.length === 0) return;

        toastrActive = true;
        let toast = toastrQueue.shift();

        if (toast.options) {
            toastr.options = {...toastr.options, ...toast.options };
        }

        let $toast = toastr[toast.type](toast.message);

        let totalTime =
            (parseInt(toastr.options.timeOut) || 0) +
            (parseInt(toastr.options.hideDuration) || 0) +
            (parseInt(toastr.options.extendedTimeOut) || 0);

        setTimeout(() => {
            toastrActive = false;
            runToastrQueue();
        }, totalTime + 50); // buffer 100ms
    }

    $.invyAlert = function(options) {
        var settings = $.extend({
            title: 'Empty title!',
            text: 'Empty text.',
            icon: 'question',
            cabtn: false,
            catext: 'Confirm',
            cobtn: false,
            cotext: 'Cancel',
            timer: 1500,
            redirectUrl: '',
            alertResponse: null
        }, options);

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success m-2",
                cancelButton: "btn btn-danger m-2"
            },
            buttonsStyling: false
        });

        (settings.cobtn || settings.cabtn) ? settings.timer = false: false;

        swalWithBootstrapButtons.fire({
            title: settings.title,
            text: settings.text,
            icon: settings.icon,
            showCancelButton: settings.cabtn,
            cancelButtonText: settings.catext,
            showConfirmButton: settings.cobtn,
            confirmButtonText: settings.cotext,
            reverseButtons: true,
            timer: settings.timer,
        }).then((result) => {
            if (result.alertResponse) {
                if (settings.redirectUrl) {
                    window.location.href = settings.redirectUrl;
                }

            } else if (result.dismiss) {
                if (settings.redirectUrl) {
                    window.location.href = settings.redirectUrl;
                }
            }


            if (typeof settings.alertResponse === 'function') {
                settings.alertResponse({ alertResponse: result });
            }
        });
    }
})(jQuery);

$(document).ready(function() {
    if (window.AppData.alert) {
        if (isJson(window.AppData.alert)) {
            let alert = window.AppData.alert;

            if (!$.empty(alert.code)) {
                $.invyAlert({
                    title: alert.code,
                    text: alert.message,
                    icon: alert.level,
                    cobtn: alert.cobtn,
                    cotext: alert.cotext,
                    cabtn: alert.cabtn,
                    catext: alert.catext,
                    redirectUrl: alert.redirectUrl
                })
            }
        }
    }

    if (window.AppData.toastr) {
        if (isJson(window.AppData.toastr)) {
            let toastr = window.AppData.toastr;

            toastr.forEach(item => $.invyToastr({ type: item.level, message: `<b>${item.code}</b><br>${item.message}` }));
        }
    }
})