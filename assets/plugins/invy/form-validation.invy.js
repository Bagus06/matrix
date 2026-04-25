(function($) {
    $.formValidation = function(options) {
        let output = {
            status: true,
            error_code: ''
        };

        let password = $('input[name="password"]')
        let confirmPassword = $('input[name="confirm_password"]')
        if ((password.val() !== undefined) && (confirmPassword.val() !== undefined)) {
            let passwordValue = password.val()
            let confirmPasswordValue = confirmPassword.val()

            if (passwordValue !== confirmPasswordValue) {
                output = {
                    status: false,
                    error_code: 'SYS-FRM-E001'
                };
            }
        }

        return output;
    }
})(jQuery);

/* ======================== Validation for on type or paste ======================== */
$(document).ready(function() {
    $('input').on('input paste', function() {
        const name = $(this).attr('name')
        const type = $(this).attr('type')
        const minlength = $(this).attr('minlength')
        const maxlength = $(this).attr('maxlength')
        const value = $(this).val().trim();
        const label = $('#lbl-' + name).html();
        const errorView = $('#err-' + name);

        /* =================== Validation for username =================== */
        if ((type === 'text') && (name === 'username')) {
            let usernameVlidationMsg = "";
            if (value.length < 3 || value.length > 20) {
                usernameVlidationMsg = `Username must be ${minlength}–${maxlength} characters long.`;
            } else if (!/^[a-zA-Z]/.test(value)) {
                usernameVlidationMsg = "Username must start with a letter (A–Z).";
            } else if (/[^a-zA-Z0-9._]/.test(value)) {
                usernameVlidationMsg = "Only letters, numbers, dot (.) and underscore (_) are allowed.";
            } else if (/[._]{2}/.test(value)) {
                usernameVlidationMsg = "Cannot use two dots or two underscores in a row.";
            } else if (/[._]$/.test(value)) {
                usernameVlidationMsg = "Username cannot end with dot or underscore.";
            } else {
                usernameVlidationMsg = "";
            }

            // Show or hide usernameVlidationMsg
            if (usernameVlidationMsg) {
                errorView.css('display', 'block');
                errorView.html(usernameVlidationMsg);
            } else {
                errorView.css('display', 'none');
            }
        }
        /* =============================================================== */

        /* =================== Validation for email =================== */
        if ((type === 'email') && (name === 'email')) {
            let emailVlidationMsg = "";

            // Rule 1: Required
            if (value.length === 0) {
                emailVlidationMsg = "Email is required.";
            }
            // Rule 2: Minimum length
            else if (value.length < 6) {
                emailVlidationMsg = `Email must be at least ${minlength} characters long.`;
            }
            // Rule 3: Must contain exactly one '@'
            else if ((value.match(/@/g) || []).length !== 1) {
                emailVlidationMsg = "Email must contain exactly one '@' symbol.";
            }
            // Rule 4: Must have local part before '@'
            else if (!/^[a-zA-Z0-9._%+-]+@/.test(value)) {
                emailVlidationMsg = "Email can only contain letters, numbers, dot (.), underscore (_), percent (%), plus (+), and hyphen (-) before '@'.";
            }
            // Rule 5: Must contain valid domain part
            else if (!/@[a-zA-Z0-9.-]+\./.test(value)) {
                emailVlidationMsg = "Email must contain a valid domain after '@'.";
            }
            // Rule 6: Must contain a valid TLD
            else if (!/\.[A-Za-z]{2,}$/.test(value)) {
                emailVlidationMsg = "Email must end with a valid domain extension like .com, .in, .org, etc.";
            }
            // Rule 7: No double dots
            else if (/\.\./.test(value)) {
                emailVlidationMsg = "Email cannot contain two consecutive dots.";
            }

            // Show or hide emailVlidationMsg
            if (emailVlidationMsg) {
                errorView.css('display', 'block');
                errorView.html(emailVlidationMsg);
            } else {
                errorView.css('display', 'none');
            }
        }
    });
    /* ================================================================================= */
})