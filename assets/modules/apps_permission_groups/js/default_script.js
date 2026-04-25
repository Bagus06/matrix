$('#tb-' + jsURI[1]).ssDtTable({
    url: BASE_URL + 'apps_permission_groups/tb_main',
    table_main: true,
    identity: '',
    style: {
        colNowrap: [1, 2],
        colW20: [3]
    }
})