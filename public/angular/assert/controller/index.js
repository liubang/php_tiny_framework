(function(angular) {
    "use strict";
    angular
        .module('app')
        .controller('app.controller.index', [
            '$scope',
            IndexController
        ]);

    function IndexController($scope) {

        console.log($scope.name);

    }

})(window.angular);