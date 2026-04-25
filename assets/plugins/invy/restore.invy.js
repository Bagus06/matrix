(function ($) {
    $.restoreItem = function (options) {
        $.invyAlert({
            title: 'RESTORE',
            text: "Are you sure you want to restore the '" + options.itemRestoreName + "' data?",
            icon: 'info',
            cobtn: true,
            cotext: 'Restore',
            cabtn: true,
            catext: 'Cancel',
            alertResponse: function (res) {
                if (res.alertResponse.isConfirmed) {
                    $.ajax({
                        url: BASE_URL + options.module + '/restore/' + options.id,
                        type: 'POST',
                        dataType: 'json',
                        async: false,
                        data: {
                            item: options.itemRestoreName
                        },
                        beforeSend: function () {
                            $.loader('show');
                        },
                        success: function (response) {
                            $.loader('hide')

                            if (response.status) {
                                if (!$.empty(response.data)) {
                                    if (response.data.duplicate) {
                                        let errInfo = $.getErrorInfo(response.code)

                                        $.invyAlert({
                                            title: errInfo.code,
                                            text: errInfo.message,
                                            icon: errInfo.level,
                                            cobtn: true,
                                            cotext: 'Replace',
                                            cabtn: true,
                                            catext: 'Cancel',
                                            alertResponse: function (res) {
                                                if (res.alertResponse.isConfirmed) {
                                                    $.ajax({
                                                        url: BASE_URL + options.module + '/restore/' + options.id,
                                                        type: 'POST',
                                                        dataType: 'json',
                                                        async: true,
                                                        data: {
                                                            replace: true,
                                                            item: options.itemRestoreName
                                                        },
                                                        beforeSend: function () {
                                                            $.loader('show');
                                                        },
                                                        success: function (response) {
                                                            if (response.status) {
                                                                $.invyAlert({
                                                                    title: 'RESTORE',
                                                                    text: 'Restore data successfully!',
                                                                    icon: 'success',
                                                                    redirectUrl: (($.empty(options.redirectUrl)) ? window.location.href : options.redirectUrl)
                                                                })
                                                            } else {
                                                                errInfo = $.getErrorInfo(response.code)
                                                                $.invyAlert({
                                                                    title: errInfo.code,
                                                                    text: errInfo.message,
                                                                    icon: errInfo.level,
                                                                    cabtn: errInfo.cabtn,
                                                                    catext: errInfo.catext
                                                                })
                                                            }

                                                            $.loader('hide')
                                                        },
                                                        error: function (params) {
                                                            let errInfo = $.getErrorInfo('SYS-BUG-E001')

                                                            if (!$.empty(errInfo)) {
                                                                $.invyAlert({
                                                                    title: errInfo.code,
                                                                    text: errInfo.message,
                                                                    icon: errInfo.level,
                                                                    cabtn: errInfo.cabtn,
                                                                    catext: errInfo.catext
                                                                })
                                                            }

                                                            $.loader('hide')
                                                        }
                                                    })
                                                } else {
                                                    $.loader('hide')
                                                }
                                            }
                                        })
                                    } else {
                                        $.invyAlert({
                                            title: 'RESTORE',
                                            text: 'Restore data successfully!',
                                            icon: 'success',
                                            redirectUrl: (($.empty(options.redirectUrl)) ? window.location.href : options.redirectUrl)
                                        })

                                        $.loader('hide')
                                    }
                                }
                            } else {
                                let errInfo = $.getErrorInfo(response.code)

                                if (!$.empty(errInfo)) {
                                    $.invyAlert({
                                        title: errInfo.code,
                                        text: errInfo.message,
                                        icon: errInfo.level,
                                        cabtn: errInfo.cabtn,
                                        catext: errInfo.catext
                                    })
                                }
                            }
                        },
                        error: function (params) {
                            let errInfo = $.getErrorInfo('SYS-BUG-E001')

                            if (!$.empty(errInfo)) {
                                $.invyAlert({
                                    title: errInfo.code,
                                    text: errInfo.message,
                                    icon: errInfo.level,
                                    cabtn: errInfo.cabtn,
                                    catext: errInfo.catext
                                })
                            }

                            $.loader('hide');
                        }
                    })
                } else {
                    $.loader('hide')
                }
            }
        })
    }
})(jQuery);

$('table>tbody').on('click', '.btn-restore', function () {
    let itemID = $(this).data('id');
    let itemRestore = $(this).data('item');

    $.restoreItem({
        module: jsURI[1],
        id: itemID,
        itemRestoreName: itemRestore,
        redirectUrl: ''
    })
})