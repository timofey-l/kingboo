var imagesServices = angular.module('imagesServices', ['ngResource']);

imagesServices.factory('Image', ['$resource', function($resource){
    return $resource('/hotelimages/:id', null, {
        'update': {method: 'PUT'}
    })
}]);
