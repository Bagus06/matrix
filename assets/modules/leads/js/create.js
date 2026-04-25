$(document).ready(function() {
    setInterval(() => {
        let enquiryNumber = $('input[name="enquiry_number"]').val();
        if (!$.empty(enquiryNumber)) {
            $.ajax({
                url: BASE_URL + 'leads/update_booked_code',
                type: 'GET',
                async: true,
                dataType: 'json',
                data: {
                    number: enquiryNumber
                },
                success: function(response) {
                    if (response.status) {
                        $('input[name="enquiry_number"]').val(response.data.number);
                    } else {
                        $('input[name="enquiry_number"]').val('');
                    }
                },
                error: function() {
                    $('input[name="enquiry_number"]').val('');
                }
            })
        }
    }, 30000);
})