$('#tb-' + jsURI[1]).ssDtTable({
    url: BASE_URL + 'universities/tb_main',
    table_main: true,
    identity: '',
    style: {
        colNowrap: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
        colW40: [15]
    }
})