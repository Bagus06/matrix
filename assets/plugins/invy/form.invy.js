$(document).ready(function() {
    function createAndEdit(e) {
        $.loader('show')

        const formName = $(e).data('formname');
        const formType = $(e).data('formtype');
        const formAction = $(e).attr('href');

        if (formType === 'ajax') {
            const modalID = $(e).data('modalid');
            const $modal = $(modalID);

            $.ajax({
                url: formAction,
                type: 'GET',
                async: true,
                success: function(response) {
                    $.loader('hide')

                    $modal.find('.modal-body').html(response)

                    $modal.find('button[type="submit"]').attr('form', formName)

                    $modal.find('form').data('modalid', modalID)
                    $modal.find('form').attr('formtype', formType)
                    $modal.find('form').attr('action', formAction)

                    $modal.modal('show')
                },
                error: function() {
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
        }
    }

    $('.btn-create').on('click', function(e) {
        const formType = $(this).data('formtype');

        if (formType === 'ajax') {
            createAndEdit(this)
            e.preventDefault();
        }
    })

    $('table').on('click', '.btn-edit', function(e) {
        const formType = $(this).data('formtype');

        if (formType === 'ajax') {
            createAndEdit(this)
            e.preventDefault();
        }
    })

    $('.btn-save').on('click', function(e) {
        e.preventDefault();
        $.loader('show')

        let form = '#' + $(this).data('formname');
        let $btnSubmit = $(form)
        let formAction = $(this).attr('href')

        $btnSubmit.parent().attr('method', 'POST')
        $btnSubmit.parent().attr('action', formAction)

        let formValidation = $.formValidation()
        if (formValidation.status) {
            $.loader('hide')
            $btnSubmit.trigger('click');
        } else {
            let errInfo = $.getErrorInfo(formValidation.error_code)

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

    $('button[type="submit"]').on('click', function(e) {
        let form = $(this).attr('form');
        let $form = $(form)
        let hasFile = false;

        $('input[type="file"]').each(function() {
            if ($(this)[0].files.length > 0) {
                hasFile = true;
            }
        });

        if (hasFile) {
            $('form').attr('enctype', 'multipart/form-data');
        } else {
            $('form').removeAttr('enctype');
        }

        $form.trigger('submit');
    })

    $('.modal').on('submit', 'form', function(e) {
        e.preventDefault();
        const formType = $(this).attr('formtype');
        const formAction = $(this).attr('action');
        const modalID = $(this).data('modalid');

        const $modal = $(modalID);

        if (formType === 'ajax') {
            let formData = new FormData(this);

            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $.loader('show');
                },
                success: function(response) {
                    if (response.status) {
                        if (!$.empty(response.code)) {
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
                        } else {
                            $modal.modal('hide')
                            if (!$.empty(response.data)) {
                                if (response.data.update) {
                                    $.invyAlert({
                                        title: 'UPDATE',
                                        text: 'Update data successfully.',
                                        icon: 'success',
                                        redirectUrl: (($.empty(response.redirectUrl)) ? window.location.href : response.redirectUrl)
                                    })
                                } else {
                                    $.invyAlert({
                                        title: 'CREATE',
                                        text: 'Create data successfully.',
                                        icon: 'success',
                                        redirectUrl: (($.empty(response.redirectUrl)) ? window.location.href : response.redirectUrl)
                                    })
                                }
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

                    $.loader('hide');
                }
            });
        }
    })

    $(document).on('mousedown keydown click focus', 'select[readonly]', function(e) {
        e.preventDefault();
        $(this).blur();
        return false;
    });
})