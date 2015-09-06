(function () {
    var searchApp = angular.module('roomsSearch', ['ngSanitize']);

    searchApp.controller('searchCtrl', ['$scope', '$http', function ($scope, $http) {
        $('[ng-app]').fadeIn('slow');

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
        $scope.loading = true;
        $scope.searchMode = window.searchMode;

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
                .success(function (data) {//console.log(data);
                    $scope.results = data;
                    $scope.searchMode = true;
                })
                .error(function(){
                    $scope.results = [];
                })
                .done(function(){
                    $scope.loading = false;
                    $scope.$digest();
                    init_rooms_gallery();
                });
        };

        $scope.getRooms = function () {
            $scope.searchMode = false;
            $.post('/' + LANG + '/hotel/rooms', {hotelId: $scope.search.hotelId})
                .success(function (data) {//console.log(data);
                    $scope.results = data;
                })
                .error(function(){
                    $scope.results = [];
                })
                .done(function(){
                    $scope.loading = false;
                    $scope.$digest();
                    init_rooms_gallery();
                });
        }

        // переход к бронированию
        $scope.goBooking = function(r) {
            if (!window.BOOKING_AVAILIBLE) {
                return false;
            }
            var data = _.clone($scope.search);
            data.roomId = r.id;
            var l = '';
            if (LANG != 'ru') {
                l = '/' + LANG;
            }
            goWithPOST(l + '/hotel/booking', data, 'BookingParams');
        };

        $scope.pFormat = function (p) {
            return window.priceFormat(p.price,p.sum_currency);
        }

        setTimeout(function(){
            if ($scope.searchMode) {
                $scope.find();
            } else {
                $scope.getRooms();
            }
        }, 1000);

    }]);
})();