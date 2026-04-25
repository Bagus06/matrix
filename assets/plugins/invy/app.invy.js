$(document).ready(function () {
    /* ---------------------- Reset session every time you change module pages ---------------------- */

    let sessionKey = Object.keys(localStorage);

    $.each(sessionKey, function (i) {
        // Reset session key "WHERECLAUSE"
        if (sessionKey[i].search("WHERECLAUSE_") > -1) {
            if ((sessionKey[i] != "WHERECLAUSE_" + jsURI[1].toUpperCase()) && (sessionKey[i] != "WHERECLAUSE_" + jsURI[2].toUpperCase())) {
                localStorage.removeItem(sessionKey[i]);
            }
        }

        // Reset session key "TABLE_SRCS"
        if (sessionKey[i].search("TABLE_SRCS_") > -1) {
            if ((sessionKey[i] != "TABLE_SRCS_" + jsURI[1].toUpperCase()) && (sessionKey[i] != "TABLE_SRCS_" + jsURI[2].toUpperCase())) {
                localStorage.removeItem(sessionKey[i]);
            }
        }
    })

    /* ---------------------------------------------------------------------------------------------- */

    /* ------------------ function for user session active activity ------------------ */

    let checkSessionTimeEvery = 300000;
    let cursorCurrentPosition = 0;
    let cursorLastPosition = 0;
    let currentURL = encodeURIComponent(window.location.href);
    const redirectURL = 'user/lockscreen/?redirect_to=';

    $(document).mousemove(function (event) {
        cursorCurrentPosition = event.pageX + event.pageY;
    });

    timeout();

    // Repeat session check based on predetermined time
    function timeout() {
        setTimeout(function () {
            update();
            timeout();
        }, checkSessionTimeEvery);
    }

    function update() {
        if ((currentURL.indexOf('login') == -1) && (currentURL.indexOf('lockscreen') == -1)) {
            if (cursorCurrentPosition == cursorLastPosition) {
                // window.location.replace(BASE_URL + redirectURL + currentURL);
            } else {
                cursorLastPosition = cursorCurrentPosition;
            }
        }
    }

    /* ------------------------------------------------------------------------------- */
});