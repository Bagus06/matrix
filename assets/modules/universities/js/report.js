$(document).ready(function() {
    const $modal = $('#studentReportModal')
    let $reportFor = $(this).data('reportfor')

    $('.btn-modalreport').on('click', function() {
        $reportFor = $(this).data('reportfor')

        if ($reportFor == 'internal') {
            $modal.find('.modal-title').text('Report Internal')
            $modal.find('input[name="report_for"]').val('internal')
        } else if ($reportFor == 'university') {
            $modal.find('.modal-title').text('Report University')
            $modal.find('input[name="report_for"]').val('university')
        }

        $modal.modal('show')
    })

    $('#studentReportModal').on('hide.bs.modal', function() {
        $(document.activeElement).blur();
        $('.btn-modalreport').trigger('focus');
    });

    $('#submit-report').on('click', function() {
        const $dateStart = $modal.find('input[name="date_start"]').val()
        const $dateEnd = $modal.find('input[name="date_end"]').val()

        $.ajax({
            url: BASE_URL + 'students/university_report',
            type: 'GET',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                $.loader('show')
            },
            data: {
                date_start: $dateStart,
                date_end: $dateEnd,
                reportfor: $reportFor,
                university_id: jsURI[3]
            },
            success: function(response) {
                if (response.status) {
                    $.downloads('students/downloads', response.data.path, response.data.filename)
                    $.invyAlert({
                        title: 'REPORT',
                        text: 'Report download successful. Please check your downloads folder.',
                        icon: 'success'
                    })
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

                console.log(response)
                $.loader('hide')
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
    })
})