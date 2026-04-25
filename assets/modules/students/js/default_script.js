$('#tb-' + jsURI[1]).ssDtTable({
    url: BASE_URL + 'students/tb_main',
    table_main: true,
    identity: '',
    style: {
        colNowrap: [1, 2, 6, 7, 11],
        colW60: []
    }
})