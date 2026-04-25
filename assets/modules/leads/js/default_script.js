$(document).ready(function() {
    $('#tb-' + jsURI[1]).ssDtTable({
        url: BASE_URL + 'leads/tb_main',
        table_main: true,
        identity: '',
        style: {
            colNowrap: [1, 2, 3, 4, 5, 6, 7, 8]
        }
    })
})