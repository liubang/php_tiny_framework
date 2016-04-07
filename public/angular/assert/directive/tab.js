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
