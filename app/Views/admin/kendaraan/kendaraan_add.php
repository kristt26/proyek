        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Tambah Kendaraan Proyek</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Kendaraan Proyek</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="table-responsive p-3">
                            <div class="col-md-6">
                                <form action="<?=base_url('dashboard/kendaraan_store')?>" method="post"
                                    style="margin-top: 10px;">
                                    <div class="form-group row">
                                        <label for="jenis_kendaraan" class="col-sm-3 col-form-label">Jenis
                                            Kendaraan</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="jenis_kendaraan" class="form-control"
                                                placeholder="Jenis Kendaraan"
                                                value="<?php echo set_value('jenis_kendaraan'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['jenis_kendaraan']) !='') echo $validation['jenis_kendaraan'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nomor_polisi" class="col-sm-3 col-form-label">Nomor Polisi</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nomor_polisi" class="form-control"
                                                placeholder="Nomor Polisi"
                                                value="<?php echo set_value('nomor_polisi'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['nomor_polisi']) !='') echo $validation['nomor_polisi'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nomor_mesin" class="col-sm-3 col-form-label">Nomor Mesin</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nomor_mesin" class="form-control"
                                                placeholder="Nomor Mesin"
                                                value="<?php echo set_value('nomor_mesin'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['nomor_mesin']) !='') echo $validation['nomor_mesin'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12" align="right">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                    class="fa fa-save"></i> Simpan</button>
                                            <a href="<?=base_url('dashboard/kendaraan')?>"
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