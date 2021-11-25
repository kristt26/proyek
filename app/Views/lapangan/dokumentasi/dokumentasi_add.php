        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper" ng-controller="dokumentasiController" ng-init="initPost()">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Tambah Dokumentasi Proyek</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Dokumentasi Proyek</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="table-responsive p-3">
                            <div class="col-md-6">
                                <form ng-submit="save()" name="form" style="margin-top: 10px;"
                                    enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label for="proyek" class="col-sm-3 col-form-label">Pilih Proyek</label>
                                        <div class="col-sm-9">
                                            <select class="select2-single-placeholder form-control" name="id_proyek"
                                                ng-options="item as item.nama_proyek for item in datas"
                                                ng-model="proyek" ng-change="model.id_proyek=proyek.id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="kegiatan" class="col-sm-3 col-form-label">Pilih Kegiatan</label>
                                        <div class="col-sm-9">
                                            <select class="select2-single-placeholder form-control" name="id_kegiatan"
                                                ng-options="item as item.nama_kegiatan for item in proyek.kegiatan"
                                                ng-model="kegiatan" ng-change="model.id_kegiatan=kegiatan.id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="kegiatan" class="col-sm-3 col-form-label">Pekerjaan</label>
                                        <div class="col-sm-9">
                                            <textarea rows="3" class="form-control"
                                                ng-model="model.jenis_kegiatan"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_mulai" class="col-sm-3 col-form-label">Tanggal Mulai</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" placeholder="Tanggal Mulai"
                                                ng-model="model.tgl_mulai">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_selesai" class="col-sm-3 col-form-label">Tanggal Selesai</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" placeholder="Tanggal Selesai"
                                                ng-model="model.tgl_selesai">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_selesai" class="col-sm-3 col-form-label">Progress</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" placeholder="Progress Proyek"
                                                ng-model="model.progress_proyek">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="status" class="col-sm-3 col-form-label">status_proyek</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="status" ng-model="model.status_proyek">
                                                <option value="Belum Mulai">Belum Mulai</option>
                                                <option value="Sedang Berjalan">Sedang Berjalan</option>
                                                <option value="Selesai">Selesai</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input custom-file-input-sm"
                                                    id="gambar" aria-describedby="inputGroupFileAddon01"
                                                    ng-model="model.dokumentasi" base-sixty-four-input
                                                    ng-change="cekFile(model.dokumentasi)">
                                                <label class="custom-file-label"
                                                    for="gambar">{{model.dokumentasi ? model.dokumentasi.filename: model.dokumentasi && !model.dokumentasi ? model.dokumentasi: 'Pilih File'}}</label>
                                                <div class="col-sm-8">
                                                </div>
                                            </div>
                                            <span ng-show="form.model.dokumentasi.$error.maxsize">Files must not exceed
                                                5000
                                                KB</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12" align="right">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                    class="fa fa-save"></i> Simpan</button>
                                            <a href="<?=base_url('dashboard/dokumentasi')?>"
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
});
        </script>
        <!---Container Fluid-->
        <?= $this->endSection() ?>