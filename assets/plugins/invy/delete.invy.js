(function ($) {
    $.deleteItem = function (options) {
        let itemData = null;
        $.ajax({
            url: BASE_URL + options.module + '/detailed/' + options.id,
            type: 'GET',
            dataType: 'json',
            async: false,
            beforeSend: function () {
                $.loader('show');
            },
            success: function (response) {
                $.loader('hide');
                itemData = response;
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

                $.loader('show');
            }
        })

        if (itemData.status) {
            let message = '';
            if (itemData.data.row_status === true) {
                message = `Are you sure you want to delete this data '${options.itemDeleteName}'?`;
            } else {
                message = `Are you sure you want to delete permanently this data '${options.itemDeleteName}'?`;
            }

            $.invyAlert({
                title: 'DELETE',
                text: message,
                icon: 'warning',
                cobtn: true,
                cotext: 'Delete',
                cabtn: true,
                catext: 'Cancel',
                alertResponse: function (res) {
                    if (res.alertResponse.isConfirmed) {
                        $.ajax({
                            url: BASE_URL + options.module + '/delete/' + options.id,
                            type: 'DELETE',
                            dataType: 'json',
                            async: true,
                            beforeSend: function () {
                                $.loader('show');
                            },
                            success: function (response) {
                                if (response.status) {
                                    $.invyAlert({
                                        title: 'DELETE',
                                        text: 'Delete data successfully!',
                                        icon: 'success',
                                        redirectUrl: (($.empty(options.redirectUrl)) ? window.location.href : options.redirectUrl)
                                    })
                                } else {
                                    let errInfo = $.getErrorInfo(response.code)

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
            let errInfo = null;
            if (!$.empty(itemData.code)) {
                errInfo = $.getErrorInfo(itemData)
            } else {
                errInfo = $.getErrorInfo('SYS-BUG-E001')
            }

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
    }
})(jQuery);

$('table>tbody').on('click', '.btn-delete', function () {
    let itemID = $(this).data('id');
    let itemDelete = $(this).data('item');

    $.deleteItem({
        module: jsURI[1],
        id: itemID,
        itemDeleteName: itemDelete,
        redirectUrl: ''
    })
})