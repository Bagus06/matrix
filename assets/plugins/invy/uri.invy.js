var HISTORY_URL = "HISTORY_URL";
let historyURL = localStorage.getItem(HISTORY_URL);

var urlPath = window.location.pathname.split('/');
var jsURI = [];
var urlpathloop = 0;
let indexKey = 0
$.each(urlPath, function(key, value) {
    if ((BASE_URL !== BASE_DEV) && (BASE_URL !== BASE_DOMAIN)) {
        if (value != '') {
            jsURI[indexKey] = value
            indexKey++
        }
    } else {
        jsURI[indexKey] = value
        indexKey++
    }
});

if (historyURL !== null) {
    historyURL = JSON.parse(historyURL);
    if (JSON.stringify(historyURL[historyURL.length - 1]) != JSON.stringify(jsURI)) {
        historyURL[historyURL.length] = jsURI
        localStorage.setItem(HISTORY_URL, JSON.stringify(historyURL));
    }
} else {
    historyURL = []
    historyURL[0] = jsURI
    localStorage.setItem(HISTORY_URL, JSON.stringify(historyURL));
}