(function(angular) {
    "use strict";
    angular
        .module('app')
        .config(['$httpProvider', config])
        .service('app.service.user', ['$rootScope', '$http', user]);
    /**
     * config $httpProvider
     * @param $httpProvider
     */
    function config($httpProvider) {
        $httpProvider.defaults.headers.post = {
            'Content-Type': 'application/x-www-form-urlencoded'
        };
        $httpProvider.defaults.headers.get = {
            'Content-Type': 'application/x-www-form-urlencoded'
        };
        $httpProvider.defaults.transformRequest = [function(data) {
            /**
             * 将$http模块POST/GET请求request payload转form data
             * @param obj
             * @returns {string}
             */
            var param = function(obj) {
                var query = '';
                var name, value, fullSubName, subName, subValue, innerObj, i;
                for (name in obj) {
                    value = obj[name];
                    if (angular.isArray(value)) {
                        for (i = 0; i < value.length; ++i) {
                            subValue = value[i];
                            fullSubName = name + '[' + i + ']';
                            innerObj = {};
                            innerObj[fullSubName] = subValue;
                            query += param(innerObj) + '&';
                        }
                    } else if (angular.isObject(value)) {
                        for(subName in value) {
                            subValue = value[subName];
                            if (subValue !== null) {
                                fullSubName = name + '.' +subName;
                                innerObj = {};
                                innerObj[fullSubName] = subValue;
                                query += param(innerObj) + '&';
                            }
                        }
                    } else if (value !== undefined) {
                        query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
                    }
                }
                return query.length ? query.substr(0, query.length - 1) : query;
            };
            return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
        }];
    }

    /**
     * user service
     * @param $rootScope
     * @param $http
     * @returns {{userInfo: {}, search: service.search}}
     */
    function user($rootScope, $http) {
        var service =  {
            userInfo: {},
            /**
             * search userInfo
             * @param data
             */
            search : function(data) {
                $http({
                    method: 'post',
                    url: '/home/index/search',
                    data: data
                }).success(function(data) {
                    if (data.status) {
                        service.userInfo = data.data;
                        $rootScope.$broadcast('userInfo.update', '终于弄好了');
                    } else {
                        service.userInfo = {};
                        $rootScope.$broadcast('user.search.error');
                    }
                }).error(function(data) {
                    service.userInfo = {};
                    $rootScope.$broadcast('user.search.error');
                });
            }
        };
        return service;
    }
})(window.angular);
