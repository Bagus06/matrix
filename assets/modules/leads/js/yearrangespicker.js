$(document).ready(function() {

    let currentYear = new Date().getFullYear();
    for (let year = currentYear + 5; year >= 2000; year--) {

        $('#startYear, #endYear').append(
            `<option value="${year}">${year}</option>`
        );
    }

    let inputValue = $('.yearrangepicker').val().trim();

    if (inputValue !== '') {

        let years = inputValue.split('-');

        let startYear = parseInt(years[0].trim());
        let endYear = parseInt(years[1].trim());

        $('#startYear').val(startYear);
        $('#endYear').val(endYear);

    } else {

        // Default value
        $('#startYear').val(currentYear);
        $('#endYear').val(currentYear + 1);

        // Default input value
        $('.yearrangepicker').val(currentYear + ' - ' + (currentYear + 1));
    }

    // Saat start year berubah
    $('#startYear').on('change', function() {

        updateEndYearOptions();
    });

    // Open dropdown
    $('.yearrangepicker').on('click', function(e) {

        $('.year-dropdown').slideDown(150);

        e.stopPropagation();
    });

    $('#applyYear').on('click', function() {

        let start = $('#startYear').val();
        let end = $('#endYear').val();

        $('.yearrangepicker').val(start + ' - ' + end);

        $('.year-dropdown').slideUp(150);
    });

    $('#cancelYear').on('click', function() {

        $('.year-dropdown').slideUp(150);
    });

    $(document).on('click', function(e) {

        if (!$(e.target).closest('.form-group').length) {

            $('.year-dropdown').slideUp(150);
        }
    });

});