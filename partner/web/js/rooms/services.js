var roomsServices = angular.module('roomsServices', ['ngResource']);

roomsServices.factory('Room', ['$resource', function($resource){
    return $resource('/rooms/:id', null, {
        'update': {method: 'PUT'}
    })
}]);

var roompricesServices = angular.module('roompricesServices', ['ngResource']);

roompricesServices.factory('Roomprices', ['$resource', function($resource){
    return $resource('/roomprices/:id', null, {
        'query':  {method:'GET', isArray:true},
        'update': {method: 'PUT'}
    })
}]);