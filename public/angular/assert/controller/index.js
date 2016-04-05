(function(angular) {
    "use strict";
    angular
        .module('app')
        .controller('app.controller.index', [
            '$scope',
            'app.service.user',
            IndexController
        ]);

    function IndexController($scope, user) {
        $scope.search = function() {
            var data = {name: $scope.name};
            user.search(data);
            $scope.$on('user.search.success', function(event) {
                $scope.userInfo = user.userInfo;
                $scope.$apply();
            });
            $scope.userInfo = user.userInfo;
            $scope.$on('user.search.error', function(event) {
                alert('查询失败!');
            })
        }
    }
})(window.angular);