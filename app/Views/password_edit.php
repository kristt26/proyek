        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
       <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">Form Update Password</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Password</a></li>
              <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
          </div>

          <div class="row">
            <!-- Datatables -->
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="table-responsive p-3">
                  <div class="col-md-6">
                    <form action="<?=base_url('pegawai/password_update')?>" method="post" style="margin-top: 10px;">
                      <input type="hidden" name="id" value="<?=$id?>">
                      <input type="hidden" name="_method" value="PUT">
                      <div class="form-group row">
                        <label for="password_lama" class="col-sm-4 col-form-label">Password Lama</label>
                        <div class="col-sm-8">
                          <input type="password" name="password_lama" class="form-control" placeholder="Password Lama" value="<?php echo set_value('password_lama'); ?>">
                          <span class="text-danger"><?php if(isset($validation['password_lama']) !='') echo $validation['password_lama'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="password_baru" class="col-sm-4 col-form-label">Password Baru</label>
                        <div class="col-sm-8">
                          <input type="password" name="password_baru" class="form-control" placeholder="Password Baru" value="<?php echo set_value('password_baru'); ?>">
                          <span class="text-danger"><?php if(isset($validation['password_baru']) !='') echo $validation['password_baru'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="confirm_password_baru" class="col-sm-4 col-form-label">Ulangi Password Baru</label>
                        <div class="col-sm-8">
                          <input type="password" name="confirm_password_baru" class="form-control" placeholder="Ulangi Password Baru" value="<?php echo set_value('confirm_password_baru'); ?>">
                          <span class="text-danger"><?php if(isset($validation['confirm_password_baru']) !='') echo $validation['confirm_password_baru'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-sm-12" align="right">
                          <button type="submit" class="btn btn-outline-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                          <button type="reset" class="btn btn-outline-warning btn-sm"><i class="fa fa-undo-alt"></i> Batal</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!---Container Fluid-->
        <?= $this->endSection() ?>