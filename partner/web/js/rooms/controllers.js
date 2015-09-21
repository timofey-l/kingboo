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
    $scope.roomToDelete = null;
    $scope.submitPrompt = '';
    
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
        $scope.submitPrompt = '';
        $scope.roomToDelete = room;
        window.remodal.open();
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

    setTimeout(function () {
        window.remodal = $('#modal').remodal();
    },500);


    $(document).on('confirmation', '.remodal', function () {
        if ($scope.roomToDelete == null) {
            return;
        }
        if ($scope.submitPrompt == 'delete') {
            Room.delete({id: $scope.roomToDelete.id})
                .$promise.then(function () {
                    $scope.loading = true;
                    $scope.load();
                    $scope.roomToDelete = null;
                });
        } else {
            window.remodal.open();
        }
    });
        
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
    $scope.hotelLangs = window.hotelLangs;

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

    $scope.langBlockClasses = function (name) {
        var el1 = $scope.add_room['title_' + name];
        var el2 = $scope.add_room['description_' + name];
        return {
            'text-green': (el1.$touched || el2.$touched) && (el1.$valid && el2.$valid),
            'text-red': (el1.$touched || el2.$touched) && (el1.$invalid || el2.$invalid),
        }
    }

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
    $scope.hotelLangs = window.hotelLangs;
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

    $scope.langBlockClasses = function (name) {
        var el1 = $scope.add_room['title_' + name];
        var el2 = $scope.add_room['description_' + name];
        return {
            'text-green': (el1.$touched || el2.$touched) && (el1.$valid && el2.$valid),
            'text-red': (el1.$touched || el2.$touched) && (el1.$invalid || el2.$invalid),
        }
    }

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
