!function(angular, $) {
    "use strict";
    angular
        .module('app')
        .service('app.service.user', ['$rootScope', '$http', user]);

    function user($rootScope, $http) {
        var service =  {
            userInfo: {},
            search : function(data) {
                /*
                $.ajax({
                    url: '/home/index/search',
                    data : data,
                    dataType: 'json',
                    type: 'post',
                    success:function(data) {
                        if (data.status) {
                            service.userInfo = data.data;
                            $rootScope.$broadcast('userInfo.update');
                        } else {
                            $rootScope.$broadcast('user.search.error');
                        }
                    },
                    error: function(msg) {
                        return false;
                    }
                });
                */
                $http({
                    method: 'post',
                    url: '/home/index/search',
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        for(var p in obj) {
                            var str = [];
                            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        }
                        return str.join('&');
                    }
                }).success(function(data) {
                    if (data.status) {
                        service.userInfo = data.data;
                        $rootScope.$broadcast('userInfo.update', '终于弄好了');
                    } else {
                        $rootScope.$broadcast('user.search.error');
                    }
                }).error(function(data) {
                    $rootScope.$broadcast('user.search.error');
                });
            }
        };
        return service;
    }
}(window.angular, window.$);
