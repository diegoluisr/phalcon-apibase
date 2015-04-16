var apibaseurl = "api/";
var token = null;

(function () {
    angular.module('ApiBase', ['ngRoute'])
        .factory('msgInterceptor', ['$log', '$location', function ($log, $location, $translate) {
                var msgInterceptor = {
                    response: function (response) {
                        if (response.status && response.data.message) {
                            addMessage(response.status, response.data.message);
                        }
                        return response;
                    }
                };
                return msgInterceptor;
            }])
        .factory('httpRequestInterceptor', ['$rootScope', function($rootScope) {
            return {
                request: function(request) {
                    if(token){
                        request.headers['Token'] = token;
                    }
                    return request;
                }
            };
        }])
        .config(function ($routeProvider, $httpProvider) {

            $httpProvider.interceptors.push('msgInterceptor');
            $httpProvider.interceptors.push('httpRequestInterceptor');

            $routeProvider
                    .when('/login', {templateUrl: 'cpanel/views/account/login.html', controller: 'LoginCtrl'})
                    .when('/me', {templateUrl: 'cpanel/views/account/me.html', controller: 'MeCtrl'})
                    .when('/register', {templateUrl: 'cpanel/views/account/register.html', controller: 'RegisterCtrl'})
                    .otherwise({redirectTo: '/'});
        })
}());