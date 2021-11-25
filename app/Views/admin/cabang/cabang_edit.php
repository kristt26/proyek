        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
       <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">Form Update Cabang Perusahaan</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Cabang Perusahaan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
          </div>

          <div class="row">
            <!-- Datatables -->
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="table-responsive p-3">
                  <div class="col-md-6">
                    <form action="<?=base_url('dashboard/cabang_update')?>" method="post" style="margin-top: 10px;">
                      <input type="hidden" name="id" value="<?=$id?>">
                      <input type="hidden" name="_method" value="PUT">
                      <div class="form-group row">
                        <label for="nama_cabang" class="col-sm-3 col-form-label">Nama Cabang</label>
                        <div class="col-sm-9">
                          <input type="text" name="nama_cabang" class="form-control" placeholder="Nama Cabang" value="<?php echo $data['nama_cabang']; ?>">
                          <span class="text-danger"><?php if(isset($validation['nama_cabang']) !='') echo $validation['nama_cabang'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="alamat_cabang" class="col-sm-3 col-form-label">Alamat Cabang</label>
                        <div class="col-sm-9">
                          <input type="text" name="alamat_cabang" class="form-control" placeholder="Alamat Cabang" value="<?php echo $data['alamat_cabang']; ?>">
                          <span class="text-danger"><?php if(isset($validation['alamat_cabang']) !='') echo $validation['alamat_cabang'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email_cabang" class="col-sm-3 col-form-label">Email Cabang</label>
                        <div class="col-sm-9">
                          <input type="email" name="email_cabang" class="form-control" placeholder="Email Cabang" value="<?php echo $data['email_cabang']; ?>">
                          <span class="text-danger"><?php if(isset($validation['email_cabang']) !='') echo $validation['email_cabang'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="telp_cabang" class="col-sm-3 col-form-label">Telp Cabang</label>
                        <div class="col-sm-9">
                          <input type="text" name="telp_cabang" class="form-control" placeholder="Telp Cabang" value="<?php echo $data['telp_cabang']; ?>">
                          <span class="text-danger"><?php if(isset($validation['telp_cabang']) !='') echo $validation['telp_cabang'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-sm-12" align="right">
                          <button type="submit" class="btn btn-outline-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                          <a href="<?=base_url('dashboard/cabang')?>" class="btn btn-outline-warning btn-sm"><i class="fa fa-undo-alt"></i> Batal</a>
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