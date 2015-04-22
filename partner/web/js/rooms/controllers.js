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
    ['$rootScope', '$scope', '$routeParams', 'Room', 'Roomprices', '$http', 
    function ($rootScope, $scope, $routeParams, Room, Roomprices, $http) {
        
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

roomsManageControllers.controller('RoomEditCtrl', ['$rootScope', '$scope', '$routeParams', 'Room', function ($rootScope, $scope, $routeParams, Room) {
        
    $scope.LANG = window.LANG;
    $scope.t = window.t;
    tabsRegister();
    $scope.loading = true;
    $scope.room = null;
    $scope.PriceTypes = window.PriceTypes;

    $scope.reqStatus = null;

    var promise = Room.get({
        id: $routeParams.id
    }).$promise;

    promise
        .then(function (room) {
            $scope.room = room;
        })
        .finally(function () {
            $scope.reqStatus = promise.$$state.value;
            $scope.loading = false;
        });

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

}]);

roomsManageControllers.controller('RoomAddCtrl', ['$rootScope', '$scope', '$routeParams', 'Room', function ($rootScope, $scope, $routeParams, Room) {
    tabsRegister();

    $scope.LANG = window.LANG;
    $scope.t = window.t;
    $scope.PriceTypes = window.PriceTypes;

    const defaultRoom = {
        'title_ru': '',
        'title_en': '',
        'description_ru': '',
        'description_en': '',
        adults: 0,
        children: 0,
        total: 0,
        hotel_id: $rootScope.hotelId
    };

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

    window.s = $scope;
}]);

roomsManageControllers.controller('PricesCtrl', 
    ['$rootScope', '$scope', '$routeParams', 'Room', 'Roomprices', '$http', 
    function ($rootScope, $scope, $routeParams, Room, Roomprices, $http) {
        
    $scope.LANG = window.LANG;
    $scope.loading = true;
    $scope.priceLoading = false;
    $scope.t = window.t;
    
    //Цены
    $scope.selectedRoom = null;
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
    
    var promise = Room.get({
        id: $routeParams.id
    }).$promise;

    promise
        .then(function (room) {
            $scope.selectedRoom = room;
            $scope.filter.room_id = room.id;
        })
        .finally(function () {
            $scope.reqStatus = promise.$$state.value;
            $scope.loading = false;
        });
        
    $scope.changeRoom = function () {
        window.location.hash = "#/prices/" + $scope.selectedRoom.id;
    }
        
    //Настройка daterangepicker
    $('#daterange').daterangepicker(
        {
            format: getLocale('dateFormat'),
            locale: getLocale('date'),
            startDate: moment(),
            endDate: moment().add(15,'days'),
            dateLimit: { days: 30 },
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
                var data = hotelRoomPrices.createPriceMatrix(Roomprices, pricelist, $scope.selectedRoom, $scope.filter.startDate, $scope.filter.endDate);
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
        if (!$scope.priceValidate(title)) return;  //console.log($scope.prices[k][0]);
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
            .success(function(data) { console.log(data);
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
        $.ajax({
            url: '/roomimages',
            type: "POST",
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(f)
        }).success(function(data){
            //console.log(data);
            $scope.newImage.image = undefined;
            var i = $("#addNewImage");    //console.log(i);
            i.replaceWith( i = i.clone( true ) ); 
            $scope.load();
        }).error(function(){
            $scope.loading = false;
            //TODO: add error
        });
    };
    
    $scope.delete = function (image) {
        if (confirm(t('delete_confirm'))) {
            Image.delete({id: image.id})
                .$promise.then(function (image) {
                    $scope.loading = true;
                    $scope.load();
                });
        }
    };    
    window.s = $scope;

}]);