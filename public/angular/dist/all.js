(function(angular) {
    "use strict";
    angular
        .module('app', [])
    //    .config(['$routeProvider', configRoute]);
    //
    // /**
    //  * config routers
    //  * @param $routeProvider
    //  */
    // function configRoute($routeProvider) {
    //     $routeProvider.
    //         when('/', {
    //             templateUrl: 'tmp/index.html',
    //             controller: 'app.controller.index'
    //         })
    //         .when('/list', {
    //             templateUrl: 'tmp/list.html',
    //             controller: 'app.controller.list'
    //         })
    //         .when('/testDirective', {
    //             templateUrl: 'tmp/testDirective.html',
    //             controller: 'app.controller.test.directive'
    //         })
    //         .when('/testTab', {
    //             templateUrl: 'tmp/testTab.html',
    //             controller: 'app.controller.test.tab'
    //         })
    //         .otherwise({
    //             redirectTo: '/'
    //         });
    // }
})(window.angular);
// (function(angular) {
//     "use strict";
//     angular
//         .module('review', ['ngRoute'])
//         .config(['$routeProvider', config])
//         .controller('review.controller.buyer', ['$scope', '$routeParams', buyerController]);
//
//     function config($routeProvider) {
//         $routeProvider.when('/buyer/:id', {
//             templateUrl: 'tmp/buyer.html',
//             controller: 'review.controller.buyer'
//         })
//     }
//
//     function buyerController($scope, $routeParams) {
//         $scope.id = $routeParams.id;
//     }
// })(window.angular);
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
        }
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
    angular
        .module('app')
        .controller('app.controller.test.tab', ['$scope', tabController]);

    function tabController($scope) {
        $scope.tabs = [{
            id: 1,
            title: 'Click me to expand',
        }, {
            id: 2,
            title: 'Click this',
        }, {
            id: 3,
            title: 'Test',
        }];
    }
})(window.angular);
(function(angular) {
    angular
        .module('app')
        .directive('accordion', ['$http',accordion])
        .directive('expander', expander);
    /**
     * accordion directive
     * @returns {{restrict: string, replace: boolean, transclude: boolean, template: string, controller: controller}}
     */
    function accordion($http) {
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

(function (angular) {
    "use strict";
    angular
        .module('app')
        .service('servicetab', serviceTab)
        .directive('lgtab', lgTab)
        .directive('lgtabmenu', lgTabMenu)
        .directive('lgtabmenuel', ['servicetab',lgTabMenuEl])
        .directive('lgtabcon', lgTabCon)
        .directive('lgtabconel', ['servicetab',lgTabConEl]);

    function serviceTab() {
        var service = {
            tabMenus : [],
            tabCons : [],
            getActive: function(activeTabMenu) {
                angular.forEach(service.tabMenus, function(tabMenu) {
                    if (activeTabMenu != tabMenu) {
                        tabMenu.showMe = false;
                    } else {
                        tabMenu.showMe = true;
                    }
                })
            },
            addTabMenu: function(tabMenu) {
                service.tabMenus.push(tabMenu);
            },
            addTabCon: function(tabCon) {
                service.tabCons.push(tabCon);
            }
        }
    }

    function lgTab() {
        return {
            restrict: 'EA',
            replace: true,
            transclude: true,
            template: '<div class="lg-tab" ng-transclude></div>'
        }
    }

    function lgTabMenu() {
        return {
            restrict: 'EA',
            replace: true,
            transclude: true,
            template: '<ul class="lg-tab-menu" ng-transclude></ul>',
        }
    }

    function lgTabMenuEl(service) {
        return {
            restrict: 'EA',
            replace: true,
            transclude: true,
            scope: {
                id: '=tabId',
                title: '=tabTitle',
            },
            template: '<li ng-class="{true: \'active\', false: \'\'}[showMe]" ng-click="checkTab()">{{title}}</li>',
            link: function(scope, element, attrs) {
                service.addTabMenu(scope);
                scope.checkTab = function() {
                    service.getActive(scope);
                };
            }
        }
    }

    function lgTabCon() {
        return {
            restrict: 'EA',
            replace: true,
            transclude: true,
            template: '<div class="lg-tab-content" ng-transclude></div>'
        }
    }

    function lgTabConEl(service) {
        return {
            restrict: 'EA',
            replace: true,
            transclude: true,
            template: '<div ng-show="showMe" class="lg-tab-content-area" ng-transclude></div>',
            link: function(scope, element, attrs) {
                console.log(service);
                service.addTabCon(scope);
            }
        }
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