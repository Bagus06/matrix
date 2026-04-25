$(document).ready(function () {
    $('#data-table').on('click', '.tm-detailed', function () {
        console.log('Ok')
    })

    $('#data-table').tableMobile({
        ajax: {
            url: BASE_URL + 'invy_mobiletable/table_test'
        }
    });
})