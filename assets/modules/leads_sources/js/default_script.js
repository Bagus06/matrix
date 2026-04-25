$(document).ready(function() {
    $('#tb-' + jsURI[1]).ssDtTable({
        url: BASE_URL + 'leads_sources/tb_main',
        table_main: true,
        identity: '',
        style: {
            colNowrap: [1, 2]
        }
    })

    $('#tb-' + jsURI[1]).on('click', '.btn-showpassword', function() {
        let password = $(this).data('pass')
        let status = $(this).attr('showpass')

        if (status == 'true') {
            $(this).html('<i class="fa-solid fa-eye-slash"></i>')
            $(this).attr('showpass', 'false')
        } else if (status == 'false') {
            $(this).html(password)
            $(this).attr('showpass', 'true')
        }
    })
})