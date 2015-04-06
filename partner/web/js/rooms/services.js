var roomsServices = angular.module('roomsServices', ['ngResource']);

roomsServices.factory('Room', ['$resource', function($resource){
    return $resource('/rooms/:id', null, {
        'update': {method: 'PUT'}
    })
}]);