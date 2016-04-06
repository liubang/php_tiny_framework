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
        $scope.userInfo = user.userInfo;
        $scope.search = function() {
            var data = {name: $scope.name};
            user.search(data);
            $scope.$on('userInfo.update', function(e, d) {
                console.log(d);
                $scope.userInfo = user.userInfo;
                //$scope.$apply();
            });
            $scope.$on('user.search.error', function(e) {
                alert('error');
            });
        }
    }
})(window.angular);