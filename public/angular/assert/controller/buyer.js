(function(angular) {
    "use strict";
    angular
        .module('app', ['ngRoute'])
        .config(['$routeProvider', '$httpProvider', config])
        .controller('review.controller.buyer', ['$scope', '$routeParams', '$http', buyerController]);

    function config($routeProvider, $httpProvider) {
        $routeProvider.when('/buyer/:id', {
            templateUrl: 'tmp/buyer.html',
            controller: 'review.controller.buyer'
        });

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

    function buyerController($scope, $routeParams, $http) {
        $scope.tab = 1;
        $scope.reviews = {};
        $scope.changeTabAndSearch = function(tab, action) {
            $scope.tab = tab;
            $http({
                method: 'post',
                url: '/home/user/search',
                data: {userId: $routeParams.id, action: action}
            }).success(function(data) {
                if (data.status) {
                    $scope.reviews = data.data;
                } else {
                    alert('查询失败');
                }
            }).error(function() {
                alert('查询失败');
            });
        };

        $scope.changeTabAndSearch(1, 'toUser');
    }
})(window.angular);