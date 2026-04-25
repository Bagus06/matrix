$(document).ready(function() {
    let courseID = $('select[name="course_id"]').val()
    let universityID = $('select[name="university_id"]').val()
    courseOption(universityID, courseID)

    $.select2('select[name="university_id"]')
    $.select2('select[name="course_id"]')

    function courseOption(universityID, courseID = '') {
        $.ajax({
            url: BASE_URL + 'university_courses/option_courses',
            type: 'GET',
            async: true,
            data: {
                university_id: universityID,
                course_id: courseID
            },
            success: function(response) {
                $('select[name="course_id"]').html(response)
                $.select2('select[name="course_id"]')
            }
        })
    }

    $('select[name="university_id"]').on('change', function() {
        let universityID = $(this).val();
        courseOption(universityID)
    })
})