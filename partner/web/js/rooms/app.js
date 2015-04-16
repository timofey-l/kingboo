'use strict';
(function(){
    var roomsManageApp = angular.module('RoomsManageApp',['ngRoute', 'roomsManageControllers' , 'roomsServices', 'roompricesServices']);

    roomsManageApp.config(['$routeProvider', function($routeProvider) {
            $routeProvider
                .when('/', {
                    templateUrl: '/partial/rooms/index.html',
                    controller: 'RoomListCtrl'
                })
                .when('/add', {
                    templateUrl: '/partial/rooms/add.html',
                    controller: 'RoomAddCtrl'
                })
                .when('/edit/:id', {
                    templateUrl: '/partial/rooms/add.html',
                    controller: 'RoomEditCtrl'
                })
                .when('/prices/:id', {
                    templateUrl: '/partial/rooms/prices.html',
                    controller: 'PricesCtrl'
                })
            ;
    }]);
})();