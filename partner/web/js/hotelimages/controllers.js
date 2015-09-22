(function addXhrProgressEvent($) {
    var originalXhr = $.ajaxSettings.xhr;
    $.ajaxSetup({
        progress: function () {
            console.log("standard progress callback");
        },
        xhr: function () {
            var req = originalXhr(), that = this;
            if (req) {
                if (typeof req.addEventListener == "function") {
                    req.addEventListener("progress", function (evt) {
                        that.progress(evt);
                    }, false);
                }
            }
            return req;
        }
    });
})(jQuery);


var imagesManageControllers = angular.module('imagesManageControllers', []);

imagesManageControllers.controller('ImageListCtrl', 
    ['$rootScope', '$scope', '$routeParams', 'Image', '$http', '$timeout',
    function ($rootScope, $scope, $routeParams, Image, $http, $timeout) {
        
    $scope.LANG = window.LANG;
    $scope.loading = true;
    $scope.images = [];
    $scope.t = window.t;
    $scope.imgToDelete = null;
    
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
        if (!$('#addNewImage').val()) {
            alert(t('need_image_select'));
            return false;
        }

        var f = $('#add_image')[0];
        var data = new FormData(f);
        data.hotel_id = $rootScope.hotelId;

        $scope.loading = true;
        $.ajax({
            url: '/hotelimages',
            type: "POST",
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(f),
            progress: function (evt) {
                if (evt.lengthComputable) {
                    console.log("Loaded " + parseInt((evt.loaded / evt.total * 100), 10) + "%");
                }
                else {
                    console.log("Length not computable.");
                }
            }
        }).success(function(data){
            //console.log(data);
            $scope.newImage.image = undefined;
            var i = $("#addNewImage");    //console.log(i);
            i.replaceWith( i = i.clone( true ) ); 
            $scope.load();
        }).error(function(){
            $scope.loading = false;
            alert(t('upload_error'));
            $scope.$digest();
            //TODO: add error
        });
    };
    
    $scope.delete = function (image) {
        $scope.imgToDelete = image;
        window.remodal.open();
    };

    // Delete confirmation
    setTimeout(function () {
        window.remodal = $('#modal').remodal();
    },500);
    $(document).on('confirmation', '.remodal', function () {
        if ($scope.imgToDelete == null) {
            return;
        }
        Image.delete({id: $scope.imgToDelete.id})
            .$promise.then(function () {
                $scope.loading = true;
                $scope.load();
            });

    });
    $(document).on('cancellation', '.remodal', function () {
        $scope.imgToDelete = null;
    });
    
}]);
