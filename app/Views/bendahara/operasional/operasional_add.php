        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Form Tambah Dana Operasional</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Dana Operasional</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="table-responsive p-3">
                            <div class="col-md-6">
                                <form action="<?=base_url('dashboard/operasional_store')?>" method="post"
                                    style="margin-top: 10px;">
                                    <div class="form-group row">
                                        <label for="id_proyek" class="col-sm-3 col-form-label">Proyek</label>
                                        <div class="col-sm-9">
                                            <select class="select2-single-placeholder form-control" name="id_proyek">
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
                                        <label for="tgl_kegiatan" class="col-sm-3 col-form-label">Tanggal
                                            Kegiatan</label>
                                        <div class="col-sm-9">
                                            <input type="date" name="tgl_kegiatan" class="form-control"
                                                placeholder="Tanggal Kegiatan"
                                                value="<?php echo set_value('tgl_kegiatan'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['tgl_kegiatan']) !='') echo $validation['tgl_kegiatan'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="keterangan" class="col-sm-3 col-form-label">Keterangan
                                            Kebutuhan</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="keterangan" class="form-control"
                                                placeholder="Keterangan Kebutuhan"
                                                value="<?php echo set_value('keterangan'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['keterangan']) !='') echo $validation['keterangan'];?></span>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label for="jenis_transaksi" class="col-sm-3 col-form-label">Jenis
                                            Transaksi</label>
                                        <div class="col-sm-9">
                                            <select class="select2-single-placeholder2 form-control"
                                                name="jenis_transaksi">
                                                <option value="">Select</option>
                                                <option value="kredit">Kredit</option>
                                                <option value="debit">Debit</option>
                                            </select>
                                            <span
                                                class="text-danger"><?php if(isset($validation['jenis_transaksi']) !='') echo $validation['jenis_transaksi'];?></span>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label for="jumlah" class="col-sm-3 col-form-label">Jumlah</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah"
                                                value="<?php echo set_value('jumlah'); ?>">
                                            <span
                                                class="text-danger"><?php if(isset($validation['jumlah']) !='') echo $validation['jumlah'];?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12" align="right">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                    class="fa fa-save"></i> Simpan</button>
                                            <a href="<?=base_url('dashboard/operasional')?>"
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
        placeholder: "Pilih Jenis Transaksi",
        allowClear: true
    });
});
        </script>
        <!---Container Fluid-->
        <?= $this->endSection() ?>