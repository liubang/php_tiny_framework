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
            .when('/testDirective', {
                templateUrl: 'tmp/testDirective.html',
                controller: 'app.controller.test.directive'
            })
            .otherwise({
                redirectTo: '/'
            });
    }
})(window.angular);
(function(angular) {
    angular
        .module('app')
        .directive('accordion', accordion)
        .directive('expander', expander);
    /**
     * accordion directive
     * @returns {{restrict: string, replace: boolean, transclude: boolean, template: string, controller: controller}}
     */
    function accordion() {
        return {
            restrict: 'EA',
            replace: true,
            transclude: true,
            template: '<div ng-transclude></div>',
            controller: function() {
                var expanders = [];
                this.gotOpened = function(selectedExpander) {
                    angular.forEach(expanders, function(expander) {
                        if (selectedExpander != expander) {
                            expander.showMe = false;
                        }
                    });
                };
                this.addExpander = function(expander) {
                    expanders.push(expander);
                };
            }
        }
    }

    /**
     * expander directive
     * @returns {{restrict: string, replace: boolean, transclude: boolean, require: string, scope: {title: string}, template: string, link: link}}
     */
    function expander() {
        return {
            restrict: 'EA',
            replace: true,
            transclude: true,
            require: '^?accordion',
            scope: {
                title: '=expanderTitle'
            },
            template: '<div>'
                      + '<div class="title" ng-click="toggle()">{{title}}</div>'
                      + '<div class="body" ng-show="showMe" ng-transclude></div>'
                      + '</div>',
            link: function(scope, element, attrs, accordionController) {
                scope.showMe = false;
                accordionController.addExpander(scope);
                scope.toggle = function toggle() {
                    scope.showMe = !scope.showMe;
                    accordionController.gotOpened(scope);
                }
            }
        }
    }
})(window.angular);
(function (angular) {
    angular
        .module('app')
        .directive('error', error);
    function error() {
        return {
            restrict: 'EAC',
            template: '<i style="color:red">*</i><span>error!</span>',
            transclude: true
        };
    }
})(window.angular);

(function(angular) {
    "use strict";
    angular
        .module('app')
        .controller('app.controller.index', [
            '$scope',
            'app.service.user',
            indexController
        ]);

    function indexController($scope, user) {
        $scope.userInfo = user.userInfo;
        $scope.search = function() {
            var data = {name: $scope.name};
            user.search(data);
            $scope.$on('userInfo.update', function(e, d) {
                console.log(d);
                $scope.error = false;
                $scope.userInfo = user.userInfo;
                //$scope.$apply();
            });
            $scope.$on('user.search.error', function(e) {
                $scope.error = true;
                $scope.userInfo = user.userInfo;
            });
        }
    }
})(window.angular);
(function(angular) {
    "use strict";
    angular
        .module('app')
        .controller('app.controller.list', [
            '$scope',
            listController
        ]);
    function listController($scope) {
        $scope.title = 'hello liubang';
    }
})(window.angular);
(function(angular) {
    angular
        .module('app')
        .controller('app.controller.test.directive', ['$scope', directiveController]);

    function directiveController($scope) {
        $scope.expanders = [{
            title: 'Click me to expand',
            text: 'Hi there folks, I am the content that was hidden but is now shown.'
        }, {
            title: 'Click this',
            text: 'I am even better text than you have seen previously'
        }, {
            title: 'Test',
            text: 'test'
        }];
    }
})(window.angular);
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