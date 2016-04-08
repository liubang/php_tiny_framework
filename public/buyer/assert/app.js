(function(angular) {
    "use strict";
    angular
        .module('app', ['ngRoute'])
        .config(['$routeProvider', config]);

    function config($routeProvider) {
        $routeProvider.when('/', {
            templateUrl: '../tpl/index.html',
            controller: 'indexController'
        }).when('/list', {
            templateUrl: 'tpl/list.html',
            controller: 'listController'
        }).otherwise({
            redirectTo: '/'
        });
    }
})(window.angular);