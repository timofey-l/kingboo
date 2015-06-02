(function () {
    var searchApp = angular.module('roomsSearch', []);

    searchApp.controller('searchCtrl', ['$scope', '$http', function ($scope, $http) {
        $scope.t = t;
        $scope.LANG = LANG;
        $scope.search = {
            dateFrom: dateFrom,
            dateTo: dateTo,
            adults: adults,
            children: children,
            kids: kids,
            hotelId: $scope.hotelId
        };

        $('.input-daterange').on('changeDate', function (e) {
            if (e.target.name == 'dateFrom') {
                $scope.search.dateFrom = get_date(e.date);
            }
            if (e.target.name == 'dateTo') {
                $scope.search.dateTo = get_date(e.date);
            }
            //console.log(e);
        });

        // Поиск комнат
        $scope.find = function () {
            $.post('/' + LANG + '/hotel/search', $scope.search)
                .success(function (data) {
                    $scope.results = data;
                })
                .error(function(){
                    $scope.results = [];
                })
                .done(function(){
                    $scope.$digest();
                    init_rooms_gallery();
                });
        };

        // переход к бронированию
        $scope.goBooking = function(r) {
            var data = _.clone($scope.search);
            data.roomId = r.id;
            var l = '';
            if (LANG != 'ru') {
                l = '/' + LANG;
            }
            goWithPOST(l + '/hotel/booking', data, 'BookingParams');
        };

        setTimeout(function(){
            $scope.find()
        }, 1000);

    }]);
})();