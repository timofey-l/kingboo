function tabsRegister() {
    $('.nav-tabs-custom .nav-tabs>li>a').click(function (e) {
        e.preventDefault();

        var tabsContainer = $(this).parent().parent().parent();

        var link = $(this);
        var tab = $('#' + link.data('tabid'));
        if (tab.length > 0) {
            tabsContainer.find('.nav-tabs>li.active').removeClass('active');
            link.parent().addClass('active');
            tabsContainer.find('.tab-content>.active').removeClass('active');
            tab.addClass('active');
            tabsContainer.find('.tab-content input, .tab-content textarea').focus();
        }
        return false;
    });
}

var roomsManageControllers = angular.module('roomsManageControllers', []);

roomsManageControllers.controller('RoomListCtrl', 
    ['$rootScope', '$scope', '$routeParams', 'Room', '$http', 
    function ($rootScope, $scope, $routeParams, Room, $http) {
        
    $scope.LANG = window.LANG;
    $scope.loading = true;
    $scope.priceLoading = false;
    $scope.t = window.t;
    
    $scope.load = function() {
        $scope.rooms = Room.query(
            {
                hotel_id: $rootScope.hotelId
            },
            function () {
                $scope.loading = false;
            },
            function () {
                $scope.loading = false;
            }
        );
        $rootScope.rooms = $scope.rooms;
    };
    $scope.load();

    $scope.delete = function (room) {
        var v = prompt(t('delete_confirm'));
        if (v == 'delete') {
            Room.delete({id: room.id})
                .$promise.then(function (room) {
                    $scope.loading = true;
                    $scope.load();
                });
        }
    };
    
    //Возвращает соответствующую id запись из типов цены
    $scope.getPriceType = function (room) {
        var el = null;      
        PriceTypes.forEach(function(elem){
            if (room.price_type == elem.id) {
                el = elem;
            }
        });
        return el;
    }
        
}]);

roomsManageControllers.controller('RoomEditCtrl', 
    ['$rootScope', '$scope', '$routeParams', '$http', 'Room', 
    function ($rootScope, $scope, $routeParams, $http, Room) {

    $scope.edit = true;
    $scope.LANG = window.LANG;
    $scope.t = window.t;
    tabsRegister();
    $scope.loading = true;
    $scope.room = null;
    $scope.PriceTypes = window.PriceTypes;
        window.s = $scope;

    $scope.reqStatus = null;

    var promise = Room.get({
        id: $routeParams.id
    }).$promise;

    promise
        .then(function (room) {
            $scope.loading = false;
            $scope.room = room;
            $scope.facilityArray();
        })
        .finally(function () {
            $scope.reqStatus = promise.$$state.value;
            $scope.loading = false;
        });

    //Facilities
    $scope.facilityArray = function () {
        $http.get('/rooms/facilities?room_id=' + $scope.room.id)
            .success(function(data){
                $scope.room.facilities = data;
            }).error(function(){
                //TODO: add error
            });
    }
    // .\Facilities
    
    $scope.getClasses = function (name) {
        var el = $scope.add_room[name];
        return {
            'has-success': el.$touched && el.$valid,
            'has-error': el.$touched && el.$invalid
        }
    };

    $scope.labelClasses = function (name) {
        var el = $scope.add_room[name];
        return {
            'text-green': el.$touched && el.$valid,
            'text-red': el.$touched && el.$invalid
        }
    };

    $scope.save = function () {
        if ($scope.add_room.$invalid) {
            // "трогаем" все поля для валидации
            for(prop in $scope.add_room) {
                if(!/^\$/.test(prop))
                    $scope.add_room[prop].$setTouched();
            }
            return false;
        }
        if ($scope.add_room.$valid) {
            var p = Room.update({id: $scope.room.id}, $scope.room).$promise;
            p.finally(function (data) {
                if (p.$$state.value.id) {
                    window.location.hash = "#/";
                } else {
                    //TODO: Add error message
                }
            });
        }
    };
    
    $scope.cancel = function (url) {
        if (url != '#/') {
            url = url + $scope.room.id;
        }
        if ($scope.add_room.$dirty) {
            if (confirm(t('edit_cancel_confirm'))) {
                document.location = url;
            }
        } else {
            document.location = url;
        }
    };

}]);

roomsManageControllers.controller('RoomAddCtrl', 
    ['$rootScope', '$scope', '$routeParams', '$http', 'Room',
    function ($rootScope, $scope, $routeParams, $http, Room) {
        
    tabsRegister();

    $scope.edit = false;
    $scope.LANG = window.LANG;
    $scope.t = window.t;
    $scope.PriceTypes = window.PriceTypes;
    $scope.loading = true;

    const defaultRoom = {
        'title_ru': '',
        'title_en': '',
        'description_ru': '',
        'description_en': '',
        adults: 0,
        children: 0,
        total: 0,
        amount: 1,
        hotel_id: $rootScope.hotelId
    };

    //Facilities
    window.s = $scope;
    $scope.facilityArray = function () {
        $http.get('/rooms/facilities')
            .success(function(data){
                $scope.loading = false;
                $scope.room.facilities = data;
            }).error(function(){
                $scope.loading = false;
                //TODO: add error
            });
    }
    // .\Facilities
    
    $scope.getClasses = function (name) {
        var el = $scope.add_room[name];
        return {
            'has-success': el.$touched && el.$valid,
            'has-error': el.$touched && el.$invalid
        }
    };

    $scope.labelClasses = function (name) {
        var el = $scope.add_room[name];
        return {
            'text-green': el.$touched && el.$valid,
            'text-red': el.$touched && el.$invalid
        }
    };

    $scope.room = new Room(defaultRoom);
    $scope.facilityArray();

    $scope.save = function () {
        if ($scope.add_room.$invalid) {
            // "трогаем" все поля для валидации
            for(prop in $scope.add_room) {
                if(!/^\$/.test(prop))
                    $scope.add_room[prop].$setTouched();
            }
            return false;
        }
        if ($scope.add_room.$valid) {
            $scope.room.$save(function (response) {
                if (response.id > 0) {
                    window.location.hash = '#/';
                }
            });
        }
    };

    $scope.cancel = function (url) {
        if (url != '#/') {
            url = url + $scope.room.id;
        }
        if ($scope.add_room.$dirty) {
            if (confirm(t('edit_cancel_confirm'))) {
                document.location = url;
            }
        } else {
            document.location = url;
        }
    };
    
}]);

roomsManageControllers.controller('PricesCtrl', 
    ['$rootScope', '$scope', '$routeParams', 'Room', 'Roomprices', '$http', 
    function ($rootScope, $scope, $routeParams, Room, Roomprices, $http) {
        
    $scope.LANG = window.LANG;
    $scope.loading = true;
    $scope.priceLoading = false;
    $scope.t = window.t;
    
    //Цены
    $scope.room = null;
    $scope.prices = null;
    $scope.dates = null;
    $scope.titles = null;

    $scope.filter = {
        startDate: moment(),
        endDate: moment().add(15,'days'),
        room_id: 0
    };
    
    //Загружаем комнаты, если был прямой заход на УРЛ
    if ($rootScope.rooms == null) {
        $scope.load = function() {
            $scope.rooms = Room.query(
                {
                    hotel_id: $rootScope.hotelId
                },
                function () {
                    $scope.loading = false;
                },
                function () {
                    $scope.loading = false;
                }
            );
        };
        $scope.load();
    } else {
        $scope.rooms = $rootScope.rooms;
    }
    
    $scope.changeRoom = function () {
        window.location.hash = "#/prices/" + $scope.room.id;
    }
        
    var promise = Room.get({
        id: $routeParams.id
    }).$promise;

    promise
        .then(function (room) {
            $scope.room = room;
            $scope.filter.room_id = room.id;
        })
        .finally(function () {
            $scope.reqStatus = promise.$$state.value;
            $scope.loading = false;
        });
        
    //Настройка daterangepicker
    $('#daterange').daterangepicker(
        {
            format: getLocale('dateFormat'),
            locale: getLocale('date'),
            startDate: moment(),
            endDate: moment().add(15,'days'),
            dateLimit: { days: 365 },
            opens: 'right'
        },
        function(start, end, label) {
            $scope.filter.startDate = start;
            $scope.filter.endDate = end;
            $scope.$digest();
        }
    ); 
    
    $scope.getPrices = function () {
        $scope.priceLoading = true;
        $scope.titles = null;
        $('#daterange').trigger('change');
        $scope.filter.startDate = $('#daterange').data('daterangepicker').startDate;
        $scope.filter.endDate = $('#daterange').data('daterangepicker').endDate;
        f = {
            startDate: $scope.filter.startDate.format('YYYY-MM-DD'),
            endDate: $scope.filter.endDate.format('YYYY-MM-DD'),
            room_id: $scope.filter.room_id
        };
        var pricelist = null;
        Roomprices.query(
            f,
            function (res) {
                $scope.priceLoading = false;
                var pricelist = res;
                var data = hotelRoomPrices.createPriceMatrix(Roomprices, pricelist, $scope.room, $scope.filter.startDate, $scope.filter.endDate);
                $scope.dates = data.dates;
                $scope.titles = data.titles;
                $scope.prices = data.prices;
            },
            function () {
                $scope.priceLoading = false;
                //TODO: Add error message
            }
        );
    }
    
    $scope.priceValidate = function (price) {
        var x = Number(price.price);
        x = x.toFixed(2);
        if (x != NaN && x >= 0) {
            price._error = false;
            price.price = x;
            return true;
        } else {
            price._error = true;
            return false;
        }
    }
    
    $scope.priceEdit = function (price) {
        price._focused = true;
    }
    
    $scope.priceSave = function (price) {
        if (!$scope.priceValidate(price)) return;
        if (price.id > 0) {//update
            var p = Roomprices.update({id: price.id}, price).$promise;
            p.finally(function (data) {
                if (p.$$state.value.id) {
                    price._focused = false;
                    price._oldPrice = p.$$state.value.price;
                } else {
                    //TODO: Add error message
                }
            });
        } else {//insert
            price.$save(function (response) {
                if (response.id > 0) {
                    price._focused = false;
                    price._oldPrice = response.price;
                } else {
                    //TODO: Add error message
                }
            });
        }
    };
    
    $scope.groupPriceSave = function (k, title) {
        if (title.price == '') return;
        if (!$scope.priceValidate(title)) return;
        f = {
            startDate: $scope.filter.startDate.format('YYYY-MM-DD'),
            endDate: $scope.filter.endDate.format('YYYY-MM-DD'),
            room_id: $scope.filter.room_id,
            adults: $scope.prices[k][0].adults,
            children: $scope.prices[k][0].children,
            kids: $scope.prices[k][0].kids,
            price: title.price
        };
        $http.post('/roomprices/updategroup/',f)
            .success(function(data) {
                for (var i in $scope.prices[k]) {
                    if (_.indexOf(data, $scope.prices[k][i].date) != -1) {
                        $scope.prices[k][i].price = title.price;
                    }
                }
                title.price = '';
            })
            .error(function (data, status, headers, config) {
                //TODO: Add error message
            });
    }
    
    $scope.priceCancel = function (price) {
        price.price = price._oldPrice;
        price._focused = false;        
        price._error = false;        
    }
    
}]);

roomsManageControllers.controller('ImagesCtrl', 
    ['$rootScope', '$scope', '$routeParams', 'Room', 'Image', '$http', '$timeout',
    function ($rootScope, $scope, $routeParams, Room, Image, $http, $timeout) {
        
    $scope.LANG = window.LANG;
    $scope.loading = true;
    $scope.images = [];
    $scope.t = window.t;
    $scope.room = null;

    //Загружаем комнаты, если был прямой заход на УРЛ
    if ($rootScope.rooms == null) {
        $scope.load = function() {
            $scope.rooms = Room.query(
                {
                    hotel_id: $rootScope.hotelId
                },
                function () {
                    $scope.loading = false;
                },
                function () {
                    $scope.loading = false;
                }
            );
        };
        $scope.load();
    } else {
        $scope.rooms = $rootScope.rooms;
    }
    
    $scope.changeRoom = function () {
        window.location.hash = "#/images/" + $scope.room.id;
    }
    
    var promise = Room.get({
        id: $routeParams.id
    }).$promise;

    promise
        .then(function (room) {
            $scope.room = room;
            const defaultImage = {
                room_id: $scope.room.id,
                image: undefined
            };
            $scope.load();
            $scope.newImage = new Image(defaultImage);
        })
        .finally(function () {
            $scope.reqStatus = promise.$$state.value;
            $scope.loading = false;
        });
        
    $scope.load = function() {
        $scope.images = Image.query(
            {
                room_id: $scope.room.id
            },
            function () {
                $scope.loading = false;
                $timeout(function(){ $('.thumbnail').colorbox({rel:'thumbnail'});}, 0, false);
            },
            function () {
                $scope.loading = false;
            }
        );
    };
   
    $scope.save = function () {
        //if (!$scope.add_image.$valid) return false;
        $scope.loading = true;
        var f = $('#add_image')[0];

        if (!$('#addNewImage').val()) {
            alert(t('need_image_select'));
            return false;
        }

        $.ajax({
            url: '/roomimages',
            type: "POST",
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(f)
        }).success(function(data){
            $scope.newImage.image = undefined;
            var i = $("#addNewImage");
            i.replaceWith( i = i.clone( true ) ); 
            $scope.load();
        }).error(function(data){
            $scope.loading = false;
            $scope.$digest();
            console.log(data);

            //TODO: add error
        });
    };
    
    $scope.delete = function (image) {
        if (confirm(t('photo_delete_confirm'))) {
            Image.delete({id: image.id})
                .$promise.then(function (image) {
                    $scope.loading = true;
                    $scope.load();
                });
        }
    };    

}]);

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
    $scope.count = '';
    $scope.countInvalid = false;

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

        //Check prices
        $http.post('/roomavailability/checkprices/',f)
            .success(function(data) {
                $scope.scanDays(function(d) {
                    if (_.indexOf(data, d.fulldate) != -1) {
                        d.price = true;
                    }
                });
                $scope.loading = false;
            })
            .error(function (data, status, headers, config) {
                $scope.loading = false;
                //TODO: Add error message
            });
    }

    $scope.save = function () {
        if (!$scope.countValidate()) return;
        f = {
            startDate: $scope.startDate.format('YYYY-MM-DD'),
            endDate: $scope.endDate != null ? $scope.endDate.format('YYYY-MM-DD') : $scope.startDate.format('YYYY-MM-DD'),
            room_id: $scope.room.id,
            count: $scope.count,
        };
        $http.post('/roomavailability/updategroup/',f)
            .success(function(data) {
                $scope.scanDays(function(d) {
                    if (_.indexOf(data, d.fulldate) != -1) {
                        d.count = $scope.count;
                    }
                });
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
                    if (_.indexOf(data, d.fulldate) != -1) {
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
        var n = parseInt($scope.count);
        if (n != NaN && n >= 0 && n == $scope.count && n < 1000) {
            $scope.countInvalid = false;
            return true;
        } else {
            $scope.countInvalid = true;
            return false;
        }
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
                        price: false,
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

    $scope.chooseDate = function (day) {
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
        }
        $('#modal-dialog').css('display','block');
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
        $scope.scanDays(function (d) {
            var date = moment(d.fulldate);
            d.selected = false;
        });
    }

    $scope.period = function () {
        $('#modal-dialog').css('display','none');
    }

    window.s = $scope;
}]);