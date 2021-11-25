        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Tambah Pengguna</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pengguna</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="table-responsive p-3">
                            <div class="col-md-6">
                                <form action="<?=base_url('dashboard/users_store')?>" method="post"
                                    style="margin-top: 10px;">
                                    <div class="form-group row">
                                        <label for="id_pegawai" class="col-sm-3 col-form-label">Proyek</label>
                                        <div class="col-sm-9">
                                            <select class="select2-single-placeholder3 form-control" name="id_pegawai">
                                                <option value="">Select</option>
                                                <?php
                                    foreach($pegawai as $rowp){
                                      echo '<option value="'.$rowp['id'].'">'.$rowp['nama'].'</option>';
                                    }
                              ?>
                                            </select>
                                            <span
                                                class="text-danger"><?php if(isset($validation['id_pegawai']) !='') echo $validation['id_pegawai'];?></span>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nama" class="form-control" placeholder="Nama"
                                                value="<?php echo set_value('nama'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['nama']) !='') echo $validation['nama'];?></span>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label for="hak_akses" class="col-sm-3 col-form-label">Hak Akses</label>
                                        <div class="col-sm-9">
                                            <select class="select2-single-placeholder form-control" name="hak_akses">
                                                <option value="">Select</option>
                                                <option value="admin">Admin</option>
                                                <option value="tim lapangan">Tim Lapangan</option>
                                                <option value="bendahara">Bendahara</option>
                                                <option value="direktur">Direktur</option>
                                            </select>
                                            <span
                                                class="text-danger"><?php if(isset($validation['hak_akses']) !='') echo $validation['hak_akses'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="id_proyek" class="col-sm-3 col-form-label">Proyek</label>
                                        <div class="col-sm-9">
                                            <select class="select2-single-placeholder2 form-control" name="id_proyek">
                                                <option value="">Select</option>
                                                <?php
                                    foreach($proyek as $rowp){
                                      echo '<option value="'.$rowp['id'].'">'.$rowp['nama_proyek'].'</option>';
                                    }
                              ?>
                                            </select>
                                            <span
                                                class="text-danger"><?php if(isset($validation['id_proyek']) !='') echo $validation['id_proyek'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="username" class="col-sm-3 col-form-label">Username</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="username" class="form-control"
                                                placeholder="Username" value="<?php echo set_value('username'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['username']) !='') echo $validation['username'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-3 col-form-label">Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Password" value="<?php echo set_value('password'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['password']) !='') echo $validation['password'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="confirm_password" class="col-sm-3 col-form-label">Konfirmasi
                                            Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" name="confirm_password" class="form-control"
                                                placeholder="Konfirmasi Password"
                                                value="<?php echo set_value('confirm_password'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['confirm_password']) !='') echo $validation['confirm_password'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12" align="right">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                    class="fa fa-save"></i> Simpan</button>
                                            <a href="<?=base_url('dashboard/users')?>"
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
        placeholder: "Pilih Hak Akses",
        allowClear: true
    });
    $('.select2-single-placeholder2').select2({
        placeholder: "Pilih Proyek",
        allowClear: true
    });
    $('.select2-single-placeholder3').select2({
        placeholder: "Pilih Pegawai",
        allowClear: true
    });
});
        </script>
        <!---Container Fluid-->
        <?= $this->endSection() ?>