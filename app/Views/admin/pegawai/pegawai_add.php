        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Tambah Pegawai</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pegawai</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="table-responsive p-3">
                            <div class="col-md-6">
                                <form action="<?=base_url('dashboard/pegawai_store')?>" method="post"
                                    style="margin-top: 10px;">
                                    <div class="form-group row">
                                        <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nama" class="form-control" placeholder="Nama"
                                                value="<?php echo set_value('nama'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['nama']) !='') echo $validation['nama'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="alamat" class="form-control" placeholder="Alamat"
                                                value="<?php echo set_value('alamat'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['alamat']) !='') echo $validation['alamat'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tempat_lahir" class="col-sm-3 col-form-label">Tempat Lahir</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="tempat_lahir" class="form-control"
                                                placeholder="Tempat Lahir"
                                                value="<?php echo set_value('tempat_lahir'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['tempat_lahir']) !='') echo $validation['tempat_lahir'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_lahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                                        <div class="col-sm-9">
                                            <input type="date" name="tgl_lahir" class="form-control"
                                                placeholder="Tanggal Lahir"
                                                value="<?php echo set_value('tgl_lahir'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['tgl_lahir']) !='') echo $validation['tgl_lahir'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" name="email" class="form-control" placeholder="Email"
                                                value="<?php echo set_value('email'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['email']) !='') echo $validation['email'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="no_telp" class="col-sm-3 col-form-label">No. Telp</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="no_telp" class="form-control"
                                                placeholder="No. Telp" value="<?php echo set_value('no_telp'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['no_telp']) !='') echo $validation['no_telp'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12" align="right">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                    class="fa fa-save"></i> Simpan</button>
                                            <a href="<?=base_url('dashboard/pegawai')?>"
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
        </script>
        <!---Container Fluid-->
        <?= $this->endSection() ?>