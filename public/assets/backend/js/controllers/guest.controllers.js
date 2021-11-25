angular.module('ctrl', [])
    .controller('pageController', pageController)
    .controller('homeController', homeController)
    .controller('kecamatanServices', kecamatanServices)
    ;

function pageController($scope, helperServices) {
    $scope.Title = "Page Header";
}

function homeController($scope, $http, helperServices, dashboardServices, $sce) {
    $scope.datas = [];
    dashboardServices.get().then(res=>{
        $scope.datas = res
        $scope.datas.menu.forEach(element => {
            element.harga = parseFloat(element.harga);
            $scope.$applyAsync(x => {
                element.foto = $sce.trustAsResourceUrl(helperServices.url + "assets/backend/img/makanan/" + element.foto);
            })
        });
        console.log($scope.datas);
    })
}

function homeController($scope, $http, helperServices, dashboardServices, $sce) {
    $scope.datas = [];
    dashboardServices.get().then(res=>{
        $scope.datas = res
        $scope.datas.menu.forEach(element => {
            element.harga = parseFloat(element.harga);
            $scope.$applyAsync(x => {
                element.foto = $sce.trustAsResourceUrl(helperServices.url + "assets/backend/img/makanan/" + element.foto);
            })
        });
        console.log($scope.datas);
    })
}