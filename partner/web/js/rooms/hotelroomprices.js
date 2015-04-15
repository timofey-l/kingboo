function hotelRoomPrices() {};
    
hotelRoomPrices.createPriceMatrix = function(Roomprices, pricelist, room, start, end) {
    var n = 0; //количество дней
    var prices = [];
    prices[0] = [];
    var titles = [];
    var dates = [];
    var date = moment(start); //дата, которая будет переменной

    //делаем заголовки дат
    var date_n = []; //массив соотношений дат и их индексов
    while (date.format('X') <= end.format('X')) {
        dates[n] = moment(date);
        date.add(1,'days');
        date_n[dates[n].format('YYYY-MM-DD')] = n;
        n++;
    }

    //делаем массив соотношений типов заселения и их индексов
    var types = 0;
    var types_n = [];
    var p = [];
    p[0] = [];
    for (var i = 1; i <= room.adults; i++) {
        types_n[i] = [];
        for (var j0 = 0; j0 <= room.children; j0++) {
            types_n[i][j0] = [];
            for (var j1 = 0; j1 <= room.children; j1++) {
                types_n[i][j0][j1] = [];
                if (i + j1 + j0 > room.total) continue;
                if (j1 + j0 > room.children) continue;
                types_n[i][j0][j1] = [types];
                p[types] = [];
                types++;
            }
        }
    }
    
    //обрабытываем pricelist
    for (var i in pricelist) {
        if (room.price_type == PriceTypeFixed) {
            if (pricelist[i].date != undefined) {
                p[0][date_n[pricelist[i].date]] = pricelist[i];
            }
        } else if (room.price_type == PriceTypeGuests) {
            if (pricelist[i].date != undefined) {
                p[types_n[pricelist[i].adults][pricelist[i].kids][pricelist[i].children]][date_n[pricelist[i].date]] = pricelist[i];
            }
        }
    }

    if (room.price_type == PriceTypeFixed) {
        titles[0] = {
            _error: false,
            title: 'Room Price',
            price: ''
        };
        //Только один вариант
        for (var k = 0; k < n; k++) {
            if (p[0][k] != undefined) {
                prices[0][k] = p[0][k];
                prices[0][k]._focused = false;
                prices[0][k]._oldPrice = p[0][k].price;
                prices[0][k]._error = false;
            } else {
                prices[0][k] = new Roomprices({
                    _focused: false,
                    _oldPrice: 0,
                    _error: false,
                    date: dates[k].format('YYYY-MM-DD'),
                    room_id: room.id,
                    adults: 0,
                    children: 0,
                    kids: 0,
                    price: 0,
                    price_currency: 1 //TODO: Исправить валюту
                });
            }
            dates[k] = dates[k].format(getLocale('dateFormat'));
        }
    } else if (room.price_type == PriceTypeGuests) {
        //Перебираем все варианты заседения              
        types = 0;
        for (var i = 1; i <= room.adults; i++) {
            for (var j0 = 0; j0 <= room.children; j0++) {
                for (var j1 = 0; j1 <= room.children; j1++) {
                    if (i + j1 + j0 > room.total) continue;
                    if (j1 + j0 > room.children) continue;
                    title = i + ' + ' + j1 + ' + ' + j0;
                    titles[types] = {
                        _error: false,
                        title: title,
                        price: ''
                    };
                    prices[types] = new Array();
                    for (var k = 0; k < n; k++) {
                        if (p[types] != undefined && p[types][k] != undefined) {
                            prices[types][k] = p[types][k];
                            prices[types][k]._focused = false;
                            prices[types][k]._oldPrice = p[types][k].price;
                            prices[types][k]._error = false;
                        } else {
                            prices[types][k] = new Roomprices({
                                _focused: false,
                                _oldPrice: 0,
                                _error: false,
                                date: dates[k].format('YYYY-MM-DD'),
                                room_id: room.id,
                                adults: i,
                                children: j1,
                                kids: j0,
                                price: 0,
                                price_currency: 1 //TODO: Исправить валюту
                            });
                        }
                    }
                    types++;
                }
            }
        }
        for (k = 0; k < n; k++) {
            dates[k] = dates[k].format(getLocale('dateFormat'));
        }
    }
    
    var o = {
        prices: prices,
        titles: titles,
        dates: dates,
        days: n
    }

    return o;
        
};
    
