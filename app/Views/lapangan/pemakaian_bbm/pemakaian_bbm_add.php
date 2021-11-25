        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper" ng-controller="bahanBakarController" ng-init="initPost()">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Tambah Pemakaian Bahan Bakar</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pemakaian Bahan Bakar</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="table-responsive p-3">
                            <div class="col-md-6">
                                <form ng-submit="save()" style="margin-top: 10px;">
                                    <div class="form-group row">
                                        <label for="proyek" class="col-sm-4 col-form-label">Pilih Proyek</label>
                                        <div class="col-sm-8">
                                            <select class="select2-single-placeholder form-control" name="id_proyek"
                                                ng-options="item as item.nama_proyek for item in datas.proyek"
                                                ng-model="proyek" ng-change="model.id_proyek=proyek.id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="kegiatan" class="col-sm-4 col-form-label">Pilih Kegiatan</label>
                                        <div class="col-sm-8">
                                            <select class="select2-single-placeholder form-control" name="id_kegiatan"
                                                ng-options="item as item.jenis_kegiatan for item in proyek.kegiatan"
                                                ng-model="kegiatan"
                                                ng-change="model.id_pemakaian_kendaraan=kegiatan.id; model.jumlah_pemakaian=kegiatan.pemakaian_bbm ; model.tanggal_pakai = kegiatan.tgl_kegiatan">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="jenis_kendaraan" class="col-sm-4 col-form-label">Jenis
                                            Kendaraan</label>
                                        <div class="col-sm-8">
                                            <select class="select2-single-placeholder form-control"
                                                name="jenis_kendaraan"
                                                ng-options="item as item.jenis_kendaraan for item in datas.kendaraan"
                                                ng-model="kendaraan" ng-change="model.id_kendaraan=kendaraan.id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="id_bahan_bakar" class="col-sm-4 col-form-label">Jenis Bahan
                                            Bakar</label>
                                        <div class="col-sm-8">
                                            <select class="select2-single-placeholder form-control"
                                                name="id_bahan_bakar"
                                                ng-options="item as item.nama_bahan_bakar for item in datas.bahanBakar"
                                                ng-model="bahanBakar" ng-change="model.id_bahan_bakar=bahanBakar.id"
                                                required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="jumlah_pemakaian" class="col-sm-4 col-form-label">Jumlah Pemakaian
                                            BBM</label>
                                        <div class="col-sm-8">
                                            <input type="number" step="0.01" name="jumlah_pemakaian"
                                                class="form-control" placeholder="Jumlah Pemakaian BBM"
                                                ng-model="model.jumlah_pemakaian" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tanggal_pakai" class="col-sm-4 col-form-label">Tanggal Pakai</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="tanggal_pakai" class="form-control"
                                                placeholder="Tanggal Pemakaian" ng-model="model.tanggal_pakai" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12" align="right">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                    class="fa fa-save"></i> Simpan</button>
                                            <a href="<?=base_url('dashboard/pemakaian_bbm')?>"
                                                class="btn btn-outline-warning btn-sm"><i class="fa fa-undo-alt"></i>
                                                Batal</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script>
$(document).ready(function() {
    $('.select2-single-placeholder').select2({
        placeholder: "Pilih Proyek",
        allowClear: true
    });
    $('.select2-single-placeholder2').select2({
        placeholder: "Pilih Jenis Kendaraan",
        allowClear: true
    });
    $('.select2-single-placeholder3').select2({
        placeholder: "Pilih Jenis Bahan Bakar",
        allowClear: true
    });
});
        </script>
        <!---Container Fluid-->
        <?= $this->endSection() ?>