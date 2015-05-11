var LANGS = {
    'ru': {
        datesFormat: 'dd.mm.yyyy'
    },
    'en': {
        datesFormat: 'mm/dd/yyyy'
    }
};

function t(v, l) {
    if (typeof l == 'undefined' && typeof window.LANG !== 'undefined') {
        l = window.LANG;
    }

    if (typeof LANGS[l][v] != 'undefined') {
        return LANGS[l][v]
    } else {
        return v;
    }
}