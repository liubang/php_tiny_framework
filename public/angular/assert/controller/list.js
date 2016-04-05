!function(angular) {
    "use strict";
    angular
        .module('app')
        .controller('app.controller.list', [
            '$scope',
            list]
        );

    function list($scope) {
        alert('this is list page')
    }
}(window.angular);