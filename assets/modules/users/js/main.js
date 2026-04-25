$(document).ready(function() {
    $('#tb-' + jsURI[1]).ssDtTable({
        url: BASE_URL + 'users/tb_main',
        table_main: true,
        identity: '',
        style: {
            colNowrap: [1]
        }
    })
})