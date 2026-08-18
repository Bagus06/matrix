$(function () {
    const $recordCall = $('#record-call')
    const $callInputs = $('.call-log-input')
    const $phone = $('input[name="phone"]')
    const $callLink = $('#lead-phone-call')

    function toggleCallInputs() {
        const enabled = $recordCall.is(':checked')
        $callInputs.prop('disabled', !enabled)
        $('select[name="contact_result"]').prop('required', enabled)
    }

    function updateCallLink() {
        const phone = String($phone.val() || '').replace(/[^0-9+]/g, '')
        $callLink.attr('href', phone ? 'tel:' + phone : '#')
        $callLink.toggleClass('disabled', !phone)
    }

    $recordCall.on('change', toggleCallInputs)
    $phone.on('input', updateCallLink)
    toggleCallInputs()
    updateCallLink()
})
