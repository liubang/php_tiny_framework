// (function(angular) {
//     "use strict";
//     angular
//         .module('review', ['ngRoute'])
//         .config(['$routeProvider', config])
//         .controller('review.controller.buyer', ['$scope', '$routeParams', buyerController]);
//
//     function config($routeProvider) {
//         $routeProvider.when('/buyer/:id', {
//             templateUrl: 'tmp/buyer.html',
//             controller: 'review.controller.buyer'
//         })
//     }
//
//     function buyerController($scope, $routeParams) {
//         $scope.id = $routeParams.id;
//     }
// })(window.angular);