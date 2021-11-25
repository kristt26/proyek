        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
       <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">Form Update Pemakaian Material</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Pemakaian Material</a></li>
              <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
          </div>

          <div class="row">
            <!-- Datatables -->
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="table-responsive p-3">
                  <div class="col-md-6">
                    <form action="<?=base_url('dashboard/material_update')?>" method="post" style="margin-top: 10px;">
                      <input type="hidden" name="id" value="<?=$id?>">
                      <input type="hidden" name="_method" value="PUT">
                      <div class="form-group row">
                        <label for="nama_bahan" class="col-sm-4 col-form-label">Nama Bahan</label>
                        <div class="col-sm-8">
                          <input type="text" name="nama_bahan" class="form-control" placeholder="Nama Bahan" value="<?php echo $data['nama_bahan']; ?>">
                          <span class="text-danger"><?php if(isset($validation['nama_bahan']) !='') echo $validation['nama_bahan'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="tgl_penggunaan" class="col-sm-4 col-form-label">Tanggal Penggunaan</label>
                        <div class="col-sm-8">
                          <input type="date" name="tgl_penggunaan" class="form-control" placeholder="Tanggal Penggunaan" value="<?php echo $data['tgl_penggunaan']; ?>">
                          <span class="text-danger"><?php if(isset($validation['tgl_penggunaan']) !='') echo $validation['tgl_penggunaan'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="jumlah_pemakaian" class="col-sm-4 col-form-label">Jumlah Pemakaian Material</label>
                        <div class="col-sm-8">
                          <input type="number" name="jumlah_pemakaian" class="form-control" placeholder="Jumlah Pemakaian Material" value="<?php echo $data['jumlah_pemakaian']; ?>">
                          <span class="text-danger"><?php if(isset($validation['jumlah_pemakaian']) !='') echo $validation['jumlah_pemakaian'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-sm-12" align="right">
                          <button type="submit" class="btn btn-outline-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                          <a href="<?=base_url('dashboard/material')?>" class="btn btn-outline-warning btn-sm"><i class="fa fa-undo-alt"></i> Batal</a>
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
          $(document).ready(function () {
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