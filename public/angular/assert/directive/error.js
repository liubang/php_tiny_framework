(function (angular) {
    angular
        .module('app')
        .directive('error', error);
    function error() {
        return {
            restrict: 'EAC',
            template: '<i style="color:red">*</i><span>error!</span>',
            transclude: true
        };
    }
})(window.angular);
