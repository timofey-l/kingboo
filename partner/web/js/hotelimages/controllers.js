var imagesManageControllers = angular.module('imagesManageControllers', []);

imagesManageControllers.controller('ImageListCtrl', 
    ['$rootScope', '$scope', '$routeParams', 'Image', '$http', '$timeout',
    function ($rootScope, $scope, $routeParams, Image, $http, $timeout) {
        
    $scope.LANG = window.LANG;
    $scope.loading = true;
    $scope.images = [];
    $scope.t = window.t;
    
    const defaultImage = {
        hotel_id: $rootScope.hotelId,
        image: undefined
    };

    $scope.load = function() {
        $scope.images = Image.query(
            {
                hotel_id: $rootScope.hotelId
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
    $scope.load();  

   
    $scope.newImage = new Image(defaultImage);

    $scope.save = function () {
        //if (!$scope.add_image.$valid) return false;
        $scope.loading = true;
        var f = $('#add_image')[0];
        $.ajax({
            url: '/hotelimages',
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
