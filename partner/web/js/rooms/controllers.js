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
roomsManageControllers.controller('RoomListCtrl', ['$rootScope', '$scope', '$routeParams', 'Room', function ($rootScope, $scope, $routeParams, Room) {
    $scope.LANG = window.LANG;
    $scope.loading = true;
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
}]);

roomsManageControllers.controller('RoomEditCtrl', ['$rootScope', '$scope', '$routeParams', 'Room', function ($rootScope, $scope, $routeParams, Room) {
    $scope.LANG = window.LANG;
    $scope.t = window.t;
    tabsRegister();
    $scope.loading = true;
    $scope.room = null;

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
        if ($scope.add_room.$valid) {
            var p = Room.update({id: $scope.room.id}, $scope.room).$promise;
            p.finally(function (data) {
                if (p.$$state.value.id) {
                    window.location.hash = "#/";
                }
            });
        }
    };

}]);

roomsManageControllers.controller('RoomAddCtrl', ['$rootScope', '$scope', '$routeParams', 'Room', function ($rootScope, $scope, $routeParams, Room) {
    tabsRegister();

    $scope.LANG = window.LANG;
    $scope.t = window.t;

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