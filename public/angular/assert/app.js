(function(angular) {
    "use strict";
    angular
        .module('app', ['ngRoute'])
        .config(['$routeProvider', configRoute]);

    /**
     * config routers
     * @param $routeProvider
     */
    function configRoute($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'tmp/index.html',
                controller: 'app.controller.index'
            })
            .when('/list', {
                templateUrl: 'tmp/list.html',
                controller: 'app.controller.list'
            })
            .otherwise({
                redirectTo: '/'
            });
    }
})(window.angular);