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