(function(angular, $) {
    "use strict";

    angular.module('app',[])
        .controller('app.controller.index', ['$scope', indexController]);

    function indexController($scope) {
        $scope.userInfo = {userId: 1034285, nickname: '东北狠人'};

        $scope.load = function() {
            $.ajax({
                url: '/home/user/getUserInfo',
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    $scope.userInfo = data.data;
                    $scope.$apply();
                    console.log($scope.userInfo);
                }
            });
        }

        $scope.load();
    };
})(window.angular, window.jQuery);