$(document).ready(function() {
    const menu = $('a[href="' + BASE_URL + 'leads/followup"]')

    $.ajax({
        url: BASE_URL + 'leads/followup_count',
        type: 'GET',
        async: true,
        dataType: 'json',
        success: function(response) {
            console.log(response)
            if (response.status) {
                menu.append(`<span class="badge badge-danger navbar-badge">` + response.data.filtered_record + `</span>`);
            }
        },
        error: function() {}
    })
})