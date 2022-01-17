        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Tambah Jenis Bahan Bakar</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Jenis Bahan Bakar</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="table-responsive p-3">
                            <div class="col-md-6">
                                <form action="<?=base_url('dashboard/bbm_store')?>" method="post"
                                    style="margin-top: 10px;">
                                    <div class="form-group row">
                                        <label for="nama_bahan_bakar" class="col-sm-4 col-form-label">Nama Bahan
                                            Bakar</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="nama_bahan_bakar" class="form-control"
                                                placeholder="Nama Bahan Bakar"
                                                value="<?php echo set_value('nama_bahan_bakar'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['nama_bahan_bakar']) !='') echo $validation['nama_bahan_bakar'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="satuan" class="col-sm-4 col-form-label">Satuan</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="satuan" class="form-control" placeholder="Satuan"
                                                value="<?php echo set_value('satuan'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['satuan']) !='') echo $validation['satuan'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12" align="right">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                    class="fa fa-save"></i> Simpan</button>
                                            <a href="<?=base_url('dashboard/bbm')?>"
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