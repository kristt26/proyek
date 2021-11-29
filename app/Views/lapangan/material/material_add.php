        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper" ng-controller="materialController" ng-init="initPost()">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Tambah Pemakaian Material</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pemakaian Material</a></li>
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
                                                ng-model="proyek" ng-change="model.id_proyek=proyek.id" required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="kegiatan" class="col-sm-4 col-form-label">Pilih Kegiatan</label>
                                        <div class="col-sm-8">
                                            <select class="select2-single-placeholderr form-control" name="id_kegiatan"
                                                ng-options="item as item.jenis_kegiatan for item in proyek.kegiatan"
                                                ng-model="kegiatan" ng-change="model.id_kegiatan=kegiatan.id;" required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="kegiatan" class="col-sm-4 col-form-label">Pilih Material</label>
                                        <div class="col-sm-8">
                                            <select class="select2-single-placeholderr form-control" name="id_material"
                                                ng-options="item as item.nama_material for item in datas.jenis_material"
                                                ng-model="material" ng-change="model.id_material = material.id"
                                                required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_penggunaan" class="col-sm-4 col-form-label">Tanggal
                                            Penggunaan</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="tgl_penggunaan" class="form-control"
                                                placeholder="Tanggal Penggunaan" ng-model="model.tgl_penggunaan"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="jumlah_pemakaian" class="col-sm-4 col-form-label">Jumlah Pemakaian
                                            Material</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="jumlah_pemakaian" class="form-control"
                                                placeholder="Jumlah Pemakaian Material"
                                                ng-model="model.jumlah_pemakaian" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12" align="right">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                    class="fa fa-save"></i> Simpan</button>
                                            <a href="<?=base_url('dashboard/material')?>"
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
    $('.select2-single-placeholderr').select2({
        placeholder: "Pilih Kegiatan",
        allowClear: true
    });
});
        </script>
        <!---Container Fluid-->
        <?= $this->endSection() ?>