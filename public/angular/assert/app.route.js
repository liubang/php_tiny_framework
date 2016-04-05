(function(angular) {
    "use strict";
    angular
        .module('app', ['ngRoute'])
        .config(['$routeProvider', configRoute]);

    function configRoute($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'tmp/index.html',
                controller: 'app.controller.index'
            })
            .when('/list', {
                templateUrl: 'tmp/list.html',
                controller: 'app.controller.list'
            });
    }
})(window.angular);