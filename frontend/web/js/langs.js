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

var locale = {
    en: { },
    ru: { }
};

/**
* d - десятичный знак
* t - разделитель порядков
*/
locale.en.currency = {
    d: '.',
    t: ',',
}

locale.ru.currency = {
    d: '.',
    t: ' ',
}

function getLocale(v, l) {
    if (typeof l == 'undefined' && typeof window.LANG !== 'undefined') {
        l = window.LANG;
    }

    if (typeof locale[l][v] != 'undefined') {
        return locale[l][v]
    } else {
        return null;
    }
}