        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper" ng-controller="penggunaanKendaraanController"
            ng-init="initPut()">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Update Penggunaan Kendaraan</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Penggunaan Kendaraan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update</li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="table-responsive p-3">
                            <div class="col-md-6">
                                <form ng-submit="save()" style=" margin-top: 10px;">
                                    <input type="hidden" name="id" value="<?=$id?>">
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="form-group row">
                                        <label for="proyek" class="col-sm-4 col-form-label">Pilih Proyek</label>
                                        <div class="col-sm-8">
                                            <select class="select2-single-placeholder form-control" name="id_proyek"
                                                ng-options="item as item.nama_proyek for item in datas.proyek"
                                                ng-model="proyek" ng-change="model.id_proyek=proyek.id" required>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="kegiatan" class="col-sm-4 col-form-label">Pilih Kegiatan</label>
                                        <div class="col-sm-8">
                                            <select class="select2-single-placeholder1 form-control" name="id_kegiatan"
                                                ng-options="item as item.nama_kegiatan for item in proyek.kegiatan"
                                                ng-model="kegiatan" ng-change="model.id_kegiatan=kegiatan.id" required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="jenis_kendaraan" class="col-sm-4 col-form-label">Jenis
                                            Kendaraan</label>
                                        <div class="col-sm-8">
                                            <select class="select2-single-placeholder2 form-control"
                                                name="jenis_kendaraan"
                                                ng-options="item as item.jenis_kendaraan for item in datas.kendaraan"
                                                ng-model="kendaraan" ng-change="model.id_kendaraan=kendaraan.id"
                                                required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_kegiatan" class="col-sm-4 col-form-label">Tanggal
                                            Kegiatan</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="tgl_kegiatan" class="form-control"
                                                placeholder="Tanggal Kegiatan" required ng-model="model.tgl_kegiatan">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pemakaian_bbm" class="col-sm-4 col-form-label">Pemakaian Bahan
                                            Bakar</label>
                                        <div class="col-sm-8">
                                            <input type="number" step="0.01" name="pemakaian_bbm" class="form-control"
                                                placeholder="Pemakaian Bahan Bakar" required
                                                ng-model="model.pemakaian_bbm">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="id_bahan_bakar" class="col-sm-4 col-form-label">Jenis Bahan
                                            Bakar</label>
                                        <div class="col-sm-8">
                                            <select class="select2-single-placeholder3 form-control"
                                                name="id_bahan_bakar"
                                                ng-options="item as item.nama_bahan_bakar for item in datas.bbm"
                                                ng-model="bahanBakar" ng-change="model.id_bahan_bakar=bahanBakar.id"
                                                required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="jumlah_rpm" class="col-sm-4 col-form-label">Jumlah RPM
                                            Kendaraan</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="jumlah_rpm" class="form-control"
                                                placeholder="Jumlah RPM Kendaraan" required ng-model="model.jumlah_rpm">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12" align="right">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                    class="fa fa-save"></i> Simpan</button>
                                            <a href="<?=base_url('dashboard/penggunaan')?>"
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