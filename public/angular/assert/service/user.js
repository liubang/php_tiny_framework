!function(angular) {
    "use strict";
    angular
        .module('app')
        .service('app.service.user', ['$rootScope', '$http', user]);

    function user($rootScope, $http) {
        var service =  {
            userInfo : {},
            search : function(data) {
                $http.post('/home/index/search', {name:'liubang'})
                    .success(function(data, status, headers, config) {
                        service.userInfo = data;
                        $rootScope.$broadcast('user.search.success');
                    })
                    .error(function(data, status, headers, config) {
                        $rootScope.$broadcast('user.search.error');
                    });
            }
        }
        return service;
    }
}(window.angular);
