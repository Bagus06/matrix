$('#tb-' + jsURI[1]).ssDtTable({
    url: BASE_URL + 'payment_receipts/tb_main',
    table_main: true,
    identity: '',
    style: {
        colNowrap: [1, 2]
    }
})