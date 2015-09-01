roomsManageControllers.controller('AvailabilityCtrl', 
    ['$rootScope', '$scope', '$routeParams', '$http', 'Room', 'Availability', 
    function ($rootScope, $scope, $routeParams, $http, Room, Availability) {

    $scope.LANG = window.LANG;
    $scope.loading = true;
    $scope.t = window.t;
    $scope.room = null;
    $scope.months = [];
    $scope.startMonth = moment().startOf('month');
    $scope.startDate = null;
    $scope.endDate = null;
    $scope.dateFormat = getLocale('dateFormat');
    $scope.count = null;
    $scope.countInvalid = false;
    $scope.priceTitles = {};
    $scope.newPrices = {};
    $scope.priceInvalid = {};
    $scope.selectedDay = null;
    $scope.currencies = [];
    $scope.currency = 0;

    moment.locale(LANG);

    //Загружаем комнаты, если был прямой заход на УРЛ
    if ($rootScope.rooms == null) {
        $scope.load = function() {
            $scope.rooms = Room.query(
                {
                    hotel_id: $rootScope.hotelId
                },
                function () {
                    //$scope.loading = false;
                },
                function () {
                    //$scope.loading = false;
                }
            );
        };
        $scope.load();
    } else {
        $scope.rooms = $rootScope.rooms;
    }
    
    $scope.changeRoom = function () {
        window.location.hash = "#/availability/" + $scope.room.id;
    }
    
    var promise = Room.get({
        id: $routeParams.id
    }).$promise;

    promise
        .then(function (room) {
            $scope.room = room;
            $scope.calendars();
            $scope.getData();
        })
        .finally(function () {
            $scope.reqStatus = promise.$$state.value;
            $scope.loading = false;
        });

    $scope.getData = function () {
        $scope.loading = true;
        var endMonth = $scope.startMonth.clone();
        endMonth.add(6,'months');
        endMonth.subtract(1, 'days');
        f = {
            startMonth: $scope.startMonth.format('YYYY-MM-DD'),
            endMonth: endMonth.format('YYYY-MM-DD'),
            room_id: $scope.room.id,
        };

        // Stop-sale + count
        Availability.query(
            f,
            function (res) {
                $scope.scanDays(function(d) {
                    for (var i in res) {
                        if (res[i].date != undefined && res[i].date == d.fulldate) {
                            d.count = res[i].count;
                            d.stopSale = res[i].stop_sale;
                        }
                    }
                });
            },
            function () {
                //TODO: Add error message
            }
        );

        // Currencies
        $http.get('/roomavailability/currencies/')
            .success(function(data) {
                for (var i in data) {
                    $scope.currencies[data[i].id] = data[i];
                }
            })

        // Prices
        $http.post('/roomavailability/prices/',f)
            .success(function(data) {//console.log(data);
                $scope.currency = data.currency;
                // titles
                $scope.priceTitles = data.titles;
                for (var i in $scope.priceTitles) {
                    $scope.newPrices[i] = {
                        price: null,
                        adults: $scope.priceTitles[i].adults,
                        children: $scope.priceTitles[i].children,
                        kids: $scope.priceTitles[i].kids,
                    };
                    $scope.priceInvalid[i] = false;
                }
                // set days
                $scope.scanDays(function(d) {
                    $scope.setDays(data, d);
                });
                // popover
                setTimeout(function() {
                    $('[data-toggle="popover"]').popover({
                        placement: 'left auto',
                        html: true,
                        delay: { "show": 500, "hide": 0 },
                    });
                },100);
                $scope.loading = false;
            })
            .error(function (data, status, headers, config) {
                $scope.loading = false;
                //TODO: Add error message
            });
    }

    // set prices for each day
    $scope.setDays = function (data, d) {
        if (data[d.fulldate] != undefined) {
            d.prices = data[d.fulldate].prices;
            // look for min price
            var min = 1000000;
            var n = 0;
            var currency = 0;
            for (var i in d.prices) {
                var p = parseFloat(d.prices[i].price);
                if (p < min) {
                    if (p == 0) continue;
                    min = p;
                    currency = d.prices[i].price_currency;
                }
                n++;
            }
            if (min != 1000000) {
                d.minPrice = $scope.cFormat(min,currency);
                if (n > 1) {
                    d.minPrice = t('availability_from') + d.minPrice;
                }
            } else {
                d.minPrice = '';
            }
            if (data[d.fulldate].set) {
                d.allPricesSet = true;
            }
        } else {
            //console.log('No date', d);
        }
    }

    $scope.save = function () {
        if (!$scope.countValidate()) return;
        if (!$scope.priceValidate()) return;
        f = {
            startDate: $scope.startDate.format('YYYY-MM-DD'),
            endDate: $scope.endDate != null ? $scope.endDate.format('YYYY-MM-DD') : $scope.startDate.format('YYYY-MM-DD'),
            room_id: $scope.room.id,
            count: $scope.count,
            prices: $scope.newPrices,
        };
        //console.log(f); return;
        $http.post('/roomavailability/updategroup/',f)
            .success(function(data) {
                $scope.scanDays(function(d) {
                    if (_.indexOf(data.changed, d.fulldate) != -1) {
                        d.count = $scope.count;
                    }
                });
                // set days
                $scope.scanDays(function(d) {
                    $scope.setDays(data, d);
                });
                // popover
                setTimeout(function() {
                    $('[data-toggle="popover"]').popover({
                        placement: 'left auto',
                        html: true,
                        delay: { "show": 500, "hide": 0 },
                    });
                },100);
                $scope.cancelModal();
            })
            .error(function (data, status, headers, config) {
                //TODO: Add error message
            });
    }

    $scope.stopSale = function (stop) {
        f = {
            startDate: $scope.startDate.format('YYYY-MM-DD'),
            endDate: $scope.endDate != null ? $scope.endDate.format('YYYY-MM-DD') : $scope.startDate.format('YYYY-MM-DD'),
            room_id: $scope.room.id,
            stopSale: stop ? 1 : 0,
        };
        $http.post('/roomavailability/updategroup/',f)
            .success(function(data) {
                //console.log(data);
                $scope.scanDays(function(d) {
                    if (_.indexOf(data.changed, d.fulldate) != -1) {
                        d.stopSale = stop;
                    }
                });
                $scope.cancelModal();
            })
            .error(function (data, status, headers, config) {
                //TODO: Add error message
            });
    }

    $scope.countValidate = function () {
        if ($scope.count == null || $scope.count == '') {
            $scope.countInvalid = false;
            return true;
        }
        var n = parseInt($scope.count);
        if (!isNaN(n) && n >= 0 && n == $scope.count && n < 1000) {
            $scope.countInvalid = false;
            return true;
        } else {
            $scope.countInvalid = true;
            return false;
        }
    }

    $scope.priceValidate = function () {
        var res = true;
        for (var i in $scope.newPrices) {
            if ($scope.newPrices[i].price == null || $scope.newPrices[i].price == '') {
                $scope.priceInvalid[i] = false;
                continue;
            }
            var x = parseFloat($scope.newPrices[i].price);
            if (!isNaN(x) && x > 0 && x == $scope.newPrices[i].price) {
                $scope.priceInvalid[i] = false;
                continue;
            } else {
                $scope.priceInvalid[i] = true;
                res = false;
            }
        }
        return res;
    }

    $scope.calendars = function() {
        var daysOfWeek = [];
        for (var i = 0; i<7; i++) {
            daysOfWeek[i] = {name: moment().day(i + 1).format('dd')};
        }
        var emptyNumber = -1;
        var month = $scope.startMonth.clone();
        var currentDay = month.clone();
        currentDay = currentDay.startOf('week');
        for (var m=0; m<6; m++) {
            //weeks
            var weeks = [];
            for (var w=0; w<6; w++) {
                //days
                var days = [];
                for (var d=0; d<7; d++) {
                    if (w == 0 && emptyNumber != -1) {
                        emptyNumber--;
                    }
                    var active = emptyNumber == -1 && currentDay.format('M') == month.format('M');
                    days[d] = {
                        active: active,
                        date: active ? currentDay.format('D') : '',
                        fulldate: active ? currentDay.format('YYYY-MM-DD') : false,
                        count: '',
                        stopSale: false,
                        selected: false,
                        allPricesSet: false,
                        prices: {},
                        minPrice: '',
                    };
                    var nextDay = currentDay.clone();
                    if (emptyNumber == -1 && nextDay.add(1, 'days').format('M') != month.format('M') && w > 2 && d != 6) {
                        emptyNumber = d;
                        endOfMonth = true;
                    }
                    if (emptyNumber == -1) {
                        currentDay.add(1, 'days');
                    }
                }
                weeks[w] = {
                    days: days,
                };
            };
            $scope.months[m] = {
                name: month.format('MMMM'),
                year: month.format('YYYY'),
                weeks: weeks,
                daysOfWeek: daysOfWeek, 
            };
            month.add(1, 'months');
        }
    }

    $scope.prevMonth = function () {
        $scope.startMonth.subtract(1,'months');
        $scope.calendars();
        $scope.getData();
    }

    $scope.nextMonth = function () {
        $scope.startMonth.add(1,'months');
        $scope.calendars();
        $scope.getData();
    }

    $scope.chooseDate = function (day, e) {
        if (e.target.tagName == 'I') return;
        if (day.fulldate == false) return;
        if ($scope.startDate == null) {
            $scope.startDate = moment(day.fulldate);
            day.selected = true;
        } else {
            var d = moment(day.fulldate);
            if (d.format('X') < $scope.startDate.format('X')) {
                return;
            } else if (d.format('X') > $scope.startDate.format('X')) {
                $scope.endDate = moment(day.fulldate);
                $scope.selectPeriod();
            }
            $('#modal-dialog').css('display','block');
        }
    }

    $scope.showDayInfo = function (day) {//console.log(day);
        $scope.selectedDay = day;
        $scope.selectedDay.formatedDate = moment($scope.selectedDay.fulldate).format($scope.dateFormat);
        $('#day-info').css('display', 'block');
    }

    $scope.scanDays = function (f) {
        for (var m in $scope.months) {
            for (var w in $scope.months[m].weeks) {
                for (var d in $scope.months[m].weeks[w].days) {
                    var d = $scope.months[m].weeks[w].days[d];
                    if (!d.active) continue;
                    f(d);
                }
            }
        }
    }

    $scope.selectPeriod = function () {
        $scope.scanDays(function (d) {
            var date = moment(d.fulldate);
            if (date.format('X') >= $scope.startDate.format('X') && date.format('X') <= $scope.endDate.format('X')) {
                d.selected = true;
            }
        });
    }

    $scope.cancelModal = function () {
        $('#modal-dialog').css('display','none');
        $scope.startDate = null;
        $scope.endDate = null;
        $scope.count = '';
        $scope.countInvalid = false;
        for (var i in $scope.priceTitles) {
            $scope.newPrices[i].price = null;
            $scope.priceInvalid[i] = false;
        }
        $scope.scanDays(function (d) {
            var date = moment(d.fulldate);
            d.selected = false;
        });
    }

    $scope.closeDayInfo = function () {
        $('#day-info').css('display','none');
        $scope.selectedDay = null;
    }

    $scope.period = function () {
        $('#modal-dialog').css('display','none');
    }

    $scope.currencyValue = function (id, param) {
        if (param == 'name') {
            param = 'name_' + LANG;
        }
        return $scope.currencies[id] != undefined ? $scope.currencies[id][param] : '';
    }

    $scope.cFormat = function (n,currency_id) {
        if ($scope.currencies[currency_id] == undefined) {
            var v = '' + currencyFormat(n);
            return v;
        }
        var f = $scope.currencies[currency_id].format;
        var v = f.replace('{symbol}',$scope.currencies[currency_id].symbol);
        v = v.replace('{value}', currencyFormat(n));
        return v;
    }

    window.s = $scope;
}]);

/**
* n - сумма
* currency - валюта
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
    //var c = isNaN(c = Math.abs(c)) ? 2 : c, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
    s = s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    return s;
}