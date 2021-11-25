angular.module('adminctrl', [])
    .controller('pageController', pageController)
    .controller('homeController', homeController)
    .controller('dokumentasiController', dokumentasiController)
    .controller('bahanBakarController', bahanBakarController)
    .controller('penggunaanKendaraanController', penggunaanKendaraanController)
    .controller('materialController', materialController)
    
    ;


function pageController($scope, helperServices) {
    $scope.Title = "Page Header";
}

function homeController($scope, $http, helperServices, homeServices, message) {
    $scope.$emit("SendUp", "Home");
}

function dokumentasiController($scope, dokumentasiServices, message, helperServices, $sce) {
    $scope.$emit("SendUp", "Kegiatan Proyek");
    $scope.datas = [];
    $scope.model = {};
    $scope.proyek = {};
    $scope.kegiatan = {};
    $scope.modelKelurahan = {};
    $scope.initPost = ()=>{
        dokumentasiServices.get().then(res => {
            $scope.datas = res;
        })
    }

    $scope.initPut = ()=>{
        dokumentasiServices.getEdit(document.location.href.substring(document.location.href.lastIndexOf('/') + 1)).then(res => {
            $scope.datas = res;
            $scope.proyek = $scope.datas.proyek.find(x=>x.id==$scope.datas.kegiatan.id_proyek);
            $scope.kegiatan = $scope.proyek.kegiatan.find(x=>x.id==$scope.datas.kegiatan.id_kegiatan);
            console.log($scope.kegiatan);
            $scope.datas.kegiatan.tgl_mulai = new Date($scope.datas.kegiatan.tgl_mulai);
            $scope.datas.kegiatan.tgl_selesai = new Date($scope.datas.kegiatan.tgl_selesai);
            $scope.model = $scope.datas.kegiatan;
        })
    }

    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.tambah = true;
    }

    $scope.cekFile =(item)=>{
        console.log(item);
    }

    $scope.save = ()=>{
        if($scope.model.id){
            dokumentasiServices.put($scope.model).then(res=>{
                document.location.href = helperServices.url + "dashboard/dokumentasi";
            })
        }else{
            dokumentasiServices.post($scope.model).then(res=>{
                document.location.href = helperServices.url + "dashboard/dokumentasi";
            })
        }
    }
}

function bahanBakarController($scope, bahanKabarServices, message, helperServices, $sce) {
    $scope.$emit("SendUp", "Kegiatan Proyek");
    $scope.datas = [];
    $scope.model = {};
    $scope.proyek = {};
    $scope.kegiatan = {};
    $scope.modelKelurahan = {};
    $scope.initPost = ()=>{
        bahanKabarServices.get().then(res => {
            $scope.datas = res;
            $scope.datas.proyek.forEach(kegiatans => {
                kegiatans.kegiatan.forEach(kegiatan => {
                    kegiatan.tgl_kegiatan = new Date(kegiatan.tgl_kegiatan);
                });
            });
        })
    }

    $scope.initPut = ()=>{
        bahanKabarServices.getEdit(document.location.href.substring(document.location.href.lastIndexOf('/') + 1)).then(res => {
            $scope.datas = res;
            $scope.datas.proyek.forEach(kegiatans => {
                kegiatans.kegiatan.forEach(kegiatan => {
                    kegiatan.tgl_kegiatan = new Date(kegiatan.tgl_kegiatan);
                });
            });
            $scope.proyek = $scope.datas.proyek.find(x=>x.id==$scope.datas.pemakaian.id_proyek);
            $scope.kendaraan = $scope.datas.kendaraan.find(x=>x.id==$scope.datas.pemakaian.id_kendaraan);
            $scope.kegiatan = $scope.proyek.kegiatan.find(x=>x.id==$scope.datas.pemakaian.id_pemakaian_kendaraan);
            $scope.bahanBakar = $scope.datas.bahanBakar.find(x=>x.id==$scope.datas.pemakaian.id_bahan_bakar);
            $scope.datas.pemakaian.tanggal_pakai = new Date($scope.datas.pemakaian.tanggal_pakai);
            $scope.datas.pemakaian.jumlah_pemakaian = parseFloat($scope.datas.pemakaian.jumlah_pemakaian);
            $scope.model = $scope.datas.pemakaian;
        })
    }

    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.tambah = true;
    }

    $scope.cekFile =(item)=>{
        console.log(item);
    }

    $scope.save = ()=>{
        if($scope.model.id){
            bahanKabarServices.put($scope.model).then(res=>{
                document.location.href = helperServices.url + "dashboard/pemakaian_bbm";
            })
        }else{
            bahanKabarServices.post($scope.model).then(res=>{
                document.location.href = helperServices.url + "dashboard/pemakaian_bbm";
            })
        }
    }
}

function penggunaanKendaraanController($scope, penggunaanKendaraanServices, message, helperServices, $sce) {
    $scope.$emit("SendUp", "Kegiatan Proyek");
    $scope.datas = [];
    $scope.model = {};
    $scope.proyek = {};
    $scope.kegiatan = {};
    $scope.modelKelurahan = {};
    $scope.initPost = ()=>{
        penggunaanKendaraanServices.get().then(res => {
            $scope.datas = res;

        })
    }

    $scope.initPut = ()=>{
        penggunaanKendaraanServices.getEdit(document.location.href.substring(document.location.href.lastIndexOf('/') + 1)).then(res => {
            $scope.datas = res;
            $scope.proyek = $scope.datas.proyek.find(x=>x.id==$scope.datas.pemakaian.id_proyek);
            $scope.kegiatan = $scope.proyek.kegiatan.find(x=>x.id==$scope.datas.pemakaian.id_pemakaian_kendaraan);
            $scope.bahanBakar = $scope.datas.bbm.find(x=>x.id==$scope.datas.pemakaian.id_bahan_bakar);
            $scope.kendaraan = $scope.datas.kendaraan.find(x=>x.id==$scope.datas.pemakaian.id_kendaraan);
            $scope.datas.pemakaian.tgl_kegiatan = new Date($scope.datas.pemakaian.tgl_kegiatan);
            $scope.datas.pemakaian.pemakaian_bbm = parseFloat($scope.datas.pemakaian.pemakaian_bbm)
            $scope.datas.pemakaian.jumlah_rpm = parseFloat($scope.datas.pemakaian.jumlah_rpm)
            $scope.model = $scope.datas.pemakaian;
            console.log($scope.kegiatan);
        })
    }
           
    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.tambah = true;
    }

    $scope.cekFile =(item)=>{
        console.log(item);
    }

    $scope.save = ()=>{
        if($scope.model.id){
            penggunaanKendaraanServices.put($scope.model).then(res=>{
                document.location.href = helperServices.url + "dashboard/penggunaan";
            })
        }else{
            penggunaanKendaraanServices.post($scope.model).then(res=>{
                document.location.href = helperServices.url + "dashboard/penggunaan";
            })
        }
    }
}

function materialController($scope, materialServices, message, helperServices, $sce) {
    $scope.$emit("SendUp", "Kegiatan Proyek");
    $scope.datas = [];
    $scope.model = {};
    $scope.proyek = {};
    $scope.kegiatan = {};
    $scope.modelKelurahan = {};
    $scope.initPost = ()=>{
        materialServices.get().then(res => {
            $scope.datas = res;

        })
    }

    $scope.initPut = ()=>{
        materialServices.getEdit(document.location.href.substring(document.location.href.lastIndexOf('/') + 1)).then(res => {
            $scope.datas = res;
            $scope.proyek = $scope.datas.proyek.find(x=>x.id==$scope.datas.pemakaian.id_proyek);
            $scope.kegiatan = $scope.proyek.kegiatan.find(x=>x.id==$scope.datas.pemakaian.id_pemakaian_kendaraan);
            $scope.datas.pemakaian.tgl_kegiatan = new Date($scope.datas.pemakaian.tgl_kegiatan);
            $scope.datas.pemakaian.pemakaian_bbm = parseFloat($scope.datas.pemakaian.pemakaian_bbm)
            $scope.datas.pemakaian.jumlah_rpm = parseFloat($scope.datas.pemakaian.jumlah_rpm)
            $scope.model = $scope.datas.pemakaian;
            console.log($scope.kegiatan);
        })
    }
           
    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.tambah = true;
    }

    $scope.cekFile =(item)=>{
        console.log(item);
    }

    $scope.save = ()=>{
        if($scope.model.id){
            materialServices.put($scope.model).then(res=>{
                document.location.href = helperServices.url + "dashboard/material";
            })
        }else{
            materialServices.post($scope.model).then(res=>{
                document.location.href = helperServices.url + "dashboard/material";
            })
        }
    }
}


