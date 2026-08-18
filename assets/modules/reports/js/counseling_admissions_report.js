$(function () {
    const $modal = $('#counselingReportModal')
    const $form = $('#counseling-report-form')
    const $actions = $modal.find('[data-pdf-action]')
    const $progress = $('#counseling-report-progress')
    let generating = false

    $('.btn-counseling-report').on('click', function () {
        $modal.modal('show')
    })

    $actions.on('click', function () {
        if (generating) {
            return
        }

        const form = $form.get(0)
        if (!form.checkValidity()) {
            form.reportValidity()
            return
        }

        const action = $(this).data('pdf-action')
        const isPreview = action === 'preview'
        const reportDate = $form.find('[name="report_date"]').val()
        const params = new URLSearchParams({
            report_date: reportDate,
            tracking_month: $form.find('[name="tracking_month"]').val(),
            disposition: isPreview ? 'inline' : 'attachment'
        })
        const reportUrl = BASE_URL + 'reports/counseling_admissions_pdf?' + params.toString()
        const previewWindow = isPreview ? window.open('', '_blank') : null

        if (isPreview && !previewWindow) {
            $.invyAlert({title: 'PDF REPORT', text: 'Allow pop-ups to preview this report.', icon: 'warning'})
            return
        }

        if (previewWindow) {
            previewWindow.document.write('<!doctype html><title>Preparing PDF</title><style>body{margin:0;display:flex;min-height:100vh;align-items:center;justify-content:center;background:#f4f6f9;color:#2853a0;font:16px Arial,sans-serif}.box{text-align:center}.spinner{width:34px;height:34px;margin:0 auto 16px;border:4px solid #d7e1f0;border-top-color:#2853a0;border-radius:50%;animation:s .8s linear infinite}@keyframes s{to{transform:rotate(360deg)}}</style><div class="box"><div class="spinner"></div><b>Preparing PDF report...</b><div style="margin-top:7px;color:#6c757d;font-size:13px">Please wait a moment.</div></div>')
            previewWindow.document.close()
            previewWindow.opener = null
        }

        generating = true
        $actions.prop('disabled', true)
        $progress.removeClass('d-none').find('span').text(isPreview ? 'Preparing PDF preview...' : 'Preparing PDF download...')

        fetch(reportUrl, {credentials: 'same-origin'})
            .then(async function (response) {
                const contentType = response.headers.get('content-type') || ''
                if (!response.ok || contentType.indexOf('application/pdf') === -1) {
                    const message = await response.text()
                    throw new Error(message.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim() || 'Unable to generate PDF report.')
                }
                return response.blob()
            })
            .then(function (blob) {
                const blobUrl = URL.createObjectURL(blob)
                if (isPreview) {
                    previewWindow.location.replace(blobUrl)
                    setTimeout(function () { URL.revokeObjectURL(blobUrl) }, 300000)
                } else {
                    const link = document.createElement('a')
                    link.href = blobUrl
                    link.download = 'counseling-admissions-report-' + reportDate + '.pdf'
                    document.body.appendChild(link)
                    link.click()
                    link.remove()
                    setTimeout(function () { URL.revokeObjectURL(blobUrl) }, 1000)
                }
                $modal.modal('hide')
            })
            .catch(function (error) {
                if (previewWindow && !previewWindow.closed) {
                    previewWindow.close()
                }
                $.invyAlert({title: 'PDF REPORT', text: error.message || 'Unable to generate PDF report.', icon: 'error'})
            })
            .finally(function () {
                generating = false
                $actions.prop('disabled', false)
                $progress.addClass('d-none')
            })
    })
})
