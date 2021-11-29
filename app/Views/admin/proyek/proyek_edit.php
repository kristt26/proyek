        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Update Proyek</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Proyek</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update</li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="table-responsive p-3">
                            <div class="col-md-6">
                                <form action="<?=base_url('dashboard/proyek_update')?>" method="post"
                                    style="margin-top: 10px;">
                                    <input type="hidden" name="id" value="<?=$id?>">
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="form-group row">
                                        <label for="nama_proyek" class="col-sm-3 col-form-label">Nama Proyek</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nama_proyek" class="form-control"
                                                placeholder="Nama Proyek" value="<?php echo $data['nama_proyek']; ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['nama_proyek']) !='') echo $validation['nama_proyek'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="lokasi" class="col-sm-3 col-form-label">Lokasi</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="lokasi" class="form-control"
                                                placeholder="LOkasi Proyek" value="<?php echo $data['lokasi']; ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['lokasi']) !='') echo $validation['lokasi'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_mulai" class="col-sm-3 col-form-label">Tanggal Mulai</label>
                                        <div class="col-sm-9">
                                            <input type="date" name="tgl_mulai" class="form-control" id="tgl1"
                                                placeholder="Tanggal Mulai" value="<?php echo $data['tgl_mulai']; ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['tgl_mulai']) !='') echo $validation['tgl_mulai'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_selesai" class="col-sm-3 col-form-label">Tanggal Selesai</label>
                                        <div class="col-sm-9">
                                            <input type="date" name="tgl_selesai" class="form-control" id="tgl2"
                                                placeholder="Tanggal Selesai"
                                                value="<?php echo $data['tgl_selesai']; ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['tgl_selesai']) !='') echo $validation['tgl_selesai'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="jangka_waktu" class="col-sm-3 col-form-label">Jangka Waktu
                                            Pelaksanaan</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="jangka_waktu" class="form-control" id="jangkaWaktu"
                                                placeholder="Jangka Waktu Pelaksanaan" value="" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="konsultan_pengawas" class="col-sm-3 col-form-label">Konsultan
                                            Pengawas</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="konsultan_pengawas" class="form-control"
                                                placeholder="Konsultan Pengawas"
                                                value="<?php echo $data['konsultan_pengawas']; ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['konsultan_pengawas']) !='') echo $validation['konsultan_pengawas'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="kontraktor_pelaksana" class="col-sm-3 col-form-label">Kontraktor
                                            Pelaksana</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="kontraktor_pelaksana" class="form-control"
                                                placeholder="Kontraktor Pelaksana"
                                                value="<?php echo $data['kontraktor_pelaksana']; ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['kontraktor_pelaksana']) !='') echo $validation['kontraktor_pelaksana'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nilai_kontrak" class="col-sm-3 col-form-label">Nilai Kontrak</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="nilai_kontrak" class="form-control"
                                                placeholder="Nilai Kontrak"
                                                value="<?php echo $data['nilai_kontrak']; ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['nilai_kontrak']) !='') echo $validation['nilai_kontrak'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12" align="right">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                    class="fa fa-save"></i> Simpan</button>
                                            <a href="<?=base_url('dashboard/proyek')?>"
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
        placeholder: "Pilih Level",
        allowClear: true
    });
    $('.select2-single-placeholder2').select2({
        placeholder: "Pilih Unit",
        allowClear: true
    });
});

function hitungSelisih() {
    var tgl1 = $("#tgl1").val();
    var tgl2 = $("#tgl2").val();
    if (tgl1 && tgl2) {
        var m1 = moment(tgl1, 'YYYY-MM-DD');
        var m2 = moment(tgl2, 'YYYY-MM-DD');
        var diff = moment.preciseDiff(m1, m2);
        $("#jangkaWaktu").val(diff);
        console.log(diff);
    }
}
$("#tgl1").change(hitungSelisih);
$("#tgl2").change(hitungSelisih);
hitungSelisih();
        </script>
        <!---Container Fluid-->
        <?= $this->endSection() ?>