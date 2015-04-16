(function () {
    angular.module('ApiBase')
        .controller('LoginCtrl', function ($scope, $rootScope, $http, $location) {
            $scope.login = function () {
                $('#accountUsersLoginBtn').prop('disabled', true);
                var _data = {};
                _data.username = $scope.username;
                _data.password = $scope.password;
                $http
                    .post(apibaseurl + 'account/login', _data)
                    .success(function (data, status, headers, config) {
                        $('#accountUsersLoginBtn').prop('disabled', false);
                        if(data.data) {
                            token = data.data;
                        }
                    })
                    .error(function (data, status, headers, config) {
                        $('#accountUsersLoginBtn').prop('disabled', false);
                    });
            }
        })
        .controller('RegisterCtrl', function ($scope, $rootScope, $http, $location) {
            $scope.signup = function () {
                $('#accountUsersLoginBtn').prop('disabled', true);
                var _data = {};
                _data.username = $scope.username;
                _data.password = $scope.password;
                $http
                    .post(apibaseurl + 'users', _data)
                    .success(function (data, status, headers, config) {
                        $('#accountUsersLoginBtn').prop('disabled', false);
                        if(status == 201){
                            $location.path('/login');
                        }
                    })
                    .error(function (data, status, headers, config) {
                        $('#accountUsersLoginBtn').prop('disabled', false);
                    });
            }
        })
        .controller('MeCtrl', function ($scope, $rootScope, $http, $location) {
            me = function () {
                $http
                    .get(apibaseurl + 'account/me')
                    .success(function (data, status, headers, config) {
                        
                    })
                    .error(function (data, status, headers, config) {});
                }
            me();
        })
}());