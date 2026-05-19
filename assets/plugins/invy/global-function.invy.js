/* ------------- GLobal utility function ------------- */
// Encoder string bin2hex
function bin2hex(str) {
    let result = '';
    for (let i = 0; i < str.length; i++) {
        const hex = str.charCodeAt(i).toString(16);
        result += ('0' + hex).slice(-2);
    }
    return result;
}

// Encoder string hex2bin
function hex2bin(hex) {
    if (typeof hex !== 'string') return null;
    if (hex.length % 2 !== 0) return null;

    let result = '';
    for (let i = 0; i < hex.length; i += 2) {
        const byte = parseInt(hex.substr(i, 2), 16);
        if (isNaN(byte)) return null;
        result += String.fromCharCode(byte);
    }
    return result;
}

// Detects both JSON strings and objects
function isJson(value) {
    // Null or undefined → not JSON
    if (value === undefined || value === null) {
        return false;
    }

    // Plain object or array → already valid JSON structure
    if ($.isPlainObject(value) || Array.isArray(value)) {
        return true;
    }

    // If it's a string, try to parse it
    if (typeof value === 'string') {
        try {
            const parsed = JSON.parse(value);
            // Ensure the parsed result is an object or array
            return $.isPlainObject(parsed) || Array.isArray(parsed);
        } catch (e) {
            return false;
        }
    }

    // Other types → not JSON
    return false;
}

// Get value object with key
function keyByValue(object, value) {
    return Object.keys(object).find(key => object[key] === value);
}

/* =================== Check empty value =================== */
(function($) {
    $.empty = function(value) {
        if (value === null || value === undefined) return true;
        if (typeof value === 'string' && value === 'null') return true;
        if (typeof value === 'string' && value.trim().length === 0) return true;
        if (typeof value === 'number' && value === 0) return true;
        if (typeof value === 'string' && value === '0') return true;
        if (typeof value === 'boolean' && value === false) return true;
        if (Array.isArray(value) && value.length === 0) return true;
        if (typeof value === 'object' && Object.keys(value).length === 0) return true;
        return false;
    };
})(jQuery);
/* ========================================================= */

/* ====================== Init select2 ====================== */
(function($) {
    $.select2 = function(option) {
        $(option).select2({
            placeholder: "Select an Option",
            allowClear: true,
            width: '100%',
            dropdownParent: $(option).parent()
        });
    }
})(jQuery);

$(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
});
/* ==================================================== */

/* ====================== Loader ====================== */
(function($) {
    $.loader = function(option) {
        function preventScroll(e) {
            e.preventDefault();
        }

        if (typeof option === 'string' && option === 'show') {
            // Show loader view
            $('#loader').show()

            // Optionally disable keyboard scrolling (spacebar, page up/down, arrow keys)
            $(document).on('keydown.scrollLock', function(e) {
                const keys = [32, 33, 34, 35, 36, 37, 38, 39, 40]; // space, arrows, page up/down
                if (keys.includes(e.which)) {
                    e.preventDefault();
                }
            });

            // Optional: Hide the scrollbar visually to prevent manual dragging
            $('body').css('overflow', 'hidden');
        } else if (typeof option === 'string' && option === 'hide') {
            // Hidden loader view
            $('#loader').hide()

            // Re-enable keyboard scroll
            $(document).off('keydown.scrollLock');

            // Restore scrollbar visibility
            $('body').css('overflow', '');
        }
    }
})(jQuery);
/* ==================================================== */

/* ====================== WhereClause View ====================== */
(function($) {
    $.wcQueryView = function(option, value = '') {
        let output = false;
        const $wcView = $('#where-clause-view');

        if (typeof option === 'string' && option === 'get') {
            output = $wcView.val()
        } else if (typeof option === 'string' && option === 'set') {
            $wcView.html(value)
            $wcView.val(value)
            output = true;
        }

        return output;
    }
})(jQuery);
/* ============================================================== */

/* ====================== Errors ====================== */
(function($) {
    $.getErrorInfo = function(code = '', error = null) {
        let output = 'Error code invalid';

        $.ajax({
            url: BASE_URL + `errors/get_error_info/${code}`,
            type: 'GET',
            dataType: 'json',
            async: false,
            data: {
                error: encodeURIComponent(error)
            },
            success: function(response) {
                output = response;
            }
        })

        return output;
    }
})(jQuery);
/* ==================================================== */

/* ====================== Check menu active ====================== */

(function($) {
    $.refreshMenu = function() {
        $('#sidebar-menu .nav-link.active').each(function() {
            $(this)
                .parents('li.nav-item')
                .each(function() {
                    $(this)
                        .addClass('menu-is-opening menu-open')
                        .children('a.nav-link')
                        .addClass('active');
                });
        });

        $('#sidebar-menu li:first').css('padding-top', '5px')
    }
})(jQuery);

$(document).ready(function() {
    $.refreshMenu();
});

/* ==================================================== */

/* ====================== Downloading Function ====================== */
(function($) {
    $.downloads = function(url, fileDirectory, filename) {
        $.ajax({
            url: BASE_URL + url,
            type: "GET",
            data: {
                file_directory: fileDirectory,
                filename: filename
            },
            async: true,
            beforeSend: function() {
                $.loader('show')
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(data, status, xhr) {
                let contentType = xhr.getResponseHeader("Content-Type") || "application/octet-stream";

                let disposition = xhr.getResponseHeader("Content-Disposition");
                if (disposition && disposition.includes("filename=")) {
                    filename = disposition.split("filename=")[1].replace(/"/g, "");
                }

                let blob = new Blob([data], { type: contentType });

                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                $.loader('hide')
            },
            error: function() {
                $.loader('hide')
            }
        });
    }
})(jQuery);
/* ================================================================== */