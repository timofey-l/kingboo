'use strict';
(function(){
    var roomsManageApp = angular.module('RoomsManageApp',['ngRoute', 'roomsManageControllers' , 'roomsServices']);

    roomsManageApp.config(['$routeProvider', function($routeProvider) {
            $routeProvider
                .when('/', {
                    templateUrl: BASE_URL + '/partial/rooms/index.html',
                    controller: 'RoomListCtrl'
                })
                .when('/add', {
                    templateUrl: BASE_URL + '/partial/rooms/add.html',
                    controller: 'RoomAddCtrl'
                })
                .when('/edit/:id', {
                    templateUrl: BASE_URL + '/partial/rooms/add.html',
                    controller: 'RoomEditCtrl'
                })
            ;
    }]);
})();