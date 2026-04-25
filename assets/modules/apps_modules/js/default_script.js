$('#tb-' + jsURI[1]).ssDtTable({
    url: BASE_URL + 'apps_modules/tb_main',
    table_main: true,
    identity: '',
    style: {
        orderableCol: [3],
        colNowrap: [1, 2, 4, 5, 6]
    }
})