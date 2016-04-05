!function(angular, $) {
    "use strict";
    angular
        .module('app')
        .service('app.service.user', ['$rootScope', user]);

    function user($rootScope) {
        var service =  {
            userInfo : {},
            search : function(data) {
                $.ajax({
                    url: 'http://linger.iliubang.cn/home/index/search',
                    data : data,
                    dataType: 'json',
                    type: 'post',
                    success:function(status) {
                        if (data.status) {
                            service.userInfo = data.data;
                            $rootScope.$broadcast('user.search.success');
                        } else {
                            $rootScope.$broadcast('user.search.faild');
                        }
                    },
                    error: function(msg) {
                        $rootScope.$broadcast('user.search.error', msg);
                    }
                });
            }
        }
        return service;
    }
}(window.angular, window.$);
