(function () {
    angular.module('ApiBase')
        .controller('MenuCtrl', function ($scope, $rootScope, $http, $location) {
            $scope.logout = function () {
                $http
                    .delete(apibaseurl + 'account/logout', {})
                    .success(function (data, status, headers, config) {})
                    .error(function (data, status, headers, config) {});
                token = null;
            }
        })
}());