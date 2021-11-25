        <?=$this->extend('layout/layout')?>
        <?=$this->section('content')?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Tambah Kelolah Kegiatan Proyek</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Kelola Kegiatan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="table-responsive p-3">
                            <div class="col-md-6">
                                <form action="<?=base_url('dashboard/kelola_kegiatan_store')?>" method="post"
                                    style="margin-top: 10px;">
                                    <div class="form-group row">
                                        <label for="nama_kegiatan" class="col-sm-3 col-form-label">Nama Kegiatan</label>
                                        <div class="col-sm-9">
                                            <input type="hidden" name="id_proyek" value="<?=$id_proyek;?>">
                                            <input type="text" name="nama_kegiatan" class="form-control"
                                                placeholder="Nama Kegiatan"
                                                value="<?php echo set_value('nama_kegiatan'); ?>">
                                            <span class="text-danger">
                                                <?php if (isset($validation['nama_kegiatan']) != '') {
    echo $validation['nama_kegiatan'];
}
?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_mulai" class="col-sm-3 col-form-label">Tanggal Mulai</label>
                                        <div class="col-sm-9">
                                            <input type="date" name="tgl_mulai" class="form-control"
                                                placeholder="Tanggal Mulai"
                                                value="<?php echo set_value('tgl_mulai'); ?>">
                                            <span class="text-danger">
                                                <?php if (isset($validation['tgl_mulai']) != '') {
    echo $validation['tgl_mulai'];
}
?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_selesai" class="col-sm-3 col-form-label">Tanggal Selesai</label>
                                        <div class="col-sm-9">
                                            <input type="date" name="tgl_selesai" class="form-control"
                                                placeholder="Tanggal Selesai"
                                                value="<?php echo set_value('tgl_selesai'); ?>">
                                            <span class="text-danger"><?php if (isset($validation['tgl_selesai']) != '') {
    echo $validation['tgl_selesai'];
}
?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="progress" class="col-sm-3 col-form-label">Progress
                                            Kegiatan</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="progress" class="form-control"
                                                placeholder="Progress" value="<?php echo set_value('progress'); ?>">
                                            <span class="text-danger">
                                                <?php if (isset($validation['progress']) != '') {
    echo $validation['progress'];
}
?></span>
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
        placeholder: "Pilih Proyek",
        allowClear: true
    });
});
        </script>
        <!---Container Fluid-->
        <?=$this->endSection()?>