/**
* n - сумма
* currency - объект валюты из базы
*/
function priceFormat(n,currency) {
    var v = currency.format.replace('{symbol}',currency.symbol);
    v = v.replace('{value}', currencyFormat(n));
    return v;
}

/**
* n - сумма
* c - до какого знака округлять
*/
function currencyFormat(n,c) {
    var locale = window.getLocale('currency');
    var d = locale.d;
    var t = locale.t;
    c = Math.abs(c);
    if (isNaN(c)) {
        if (n == Math.ceil(n)) {
            c = 0;
        } else {
            c = 2;
        }
    }
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
    s = s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    return s;
}