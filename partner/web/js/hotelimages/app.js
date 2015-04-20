'use strict';
(function(){
    var roomsManageApp = angular.module('ImagesManageApp',['ngRoute', 'imagesManageControllers' , 'imagesServices']);

    roomsManageApp.config(['$routeProvider', function($routeProvider) {
            $routeProvider
                .when('/', {
                    templateUrl: '/partial/hotelimages/index.html',
                    controller: 'ImageListCtrl'
                });
    }]);
})();