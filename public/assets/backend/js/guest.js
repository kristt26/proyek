angular.module('apps', [
    'ctrl',
    'helper.service',
    'admin.service',
    'auth.service',
    'naif.base64',
    'swangular',
    'message.service',
    'ngLocale',
    'datatables',
    'cur.$mask'

])
    .controller('indexController', indexController)
    ;

function indexController($scope) {
    $scope.titleHeader = "PTC";
    $scope.header = "";
    $scope.breadcrumb = "";
    $scope.title;
    $scope.$on("SendUp", function (evt, data) {
        $scope.header = data;
        $scope.header = data;
        $scope.breadcrumb = data;
        $scope.title = data;
        $.LoadingOverlay("hide");
    });
}
