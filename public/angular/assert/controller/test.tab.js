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