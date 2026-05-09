$('#tb-' + jsURI[1]).ssDtTable({
    url: BASE_URL + 'students/tb_main',
    table_main: true,
    identity: '',
    style: {
        orderableCol: [7, 8],
        colNowrap: [1, 2, 5, 6, 7, 8, 9, 10],
        colAlRight: [],
        colW60: []
    }
})