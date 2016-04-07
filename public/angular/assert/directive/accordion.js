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