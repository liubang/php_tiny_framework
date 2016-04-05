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
            $scope.$on('user.search.success', function() {
                $scope.userInfo = user.userInfo;
                //$scope.$apply();
            });
            $scope.$on('user.search.error', function() {
                alert('查询失败!');
            })
        }
    }

})(window.angular);