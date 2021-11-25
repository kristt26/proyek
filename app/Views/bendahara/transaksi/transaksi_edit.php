        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
       <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">Form Update Transaksi Keuangan</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Transaksi Keuangan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
          </div>

          <div class="row">
            <!-- Datatables -->
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="table-responsive p-3">
                  <div class="col-md-6">
                    <form action="<?=base_url('dashboard/transaksi_update')?>" method="post" style="margin-top: 10px;">
                      <input type="hidden" name="id" value="<?=$id?>">
                      <input type="hidden" name="_method" value="PUT">
                      <div class="form-group row">
                        <label for="tgl_transaksi" class="col-sm-3 col-form-label">Tanggal Transaksi</label>
                        <div class="col-sm-9">
                          <input type="date" name="tgl_transaksi" class="form-control" placeholder="Tanggal Kegiatan" value="<?php echo $data['tgl_transaksi']; ?>">
                          <span class="text-danger"><?php if(isset($validation['tgl_transaksi']) !='') echo $validation['tgl_transaksi'];?></span>
                        </div>
                      </div>
                       <div class="form-group row">
                        <label for="keterangan" class="col-sm-3 col-form-label">Keterangan Kebutuhan</label>
                        <div class="col-sm-9">
                          <input type="text" name="keterangan" class="form-control" placeholder="Keterangan Kebutuhan" value="<?php echo $data['keterangan']; ?>">
                          <span class="text-danger"><?php if(isset($validation['keterangan']) !='') echo $validation['keterangan'];?></span>
                        </div>
                      </div>
                       <div class="form-group row">
                        <label for="jumlah" class="col-sm-3 col-form-label">Jumlah Transaksi</label>
                        <div class="col-sm-9">
                          <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" value="<?php echo $data['jumlah']; ?>">
                          <span class="text-danger"><?php if(isset($validation['jumlah']) !='') echo $validation['jumlah'];?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-sm-12" align="right">
                          <button type="submit" class="btn btn-outline-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                          <a href="<?=base_url('dashboard/transaksi')?>" class="btn btn-outline-warning btn-sm"><i class="fa fa-undo-alt"></i> Batal</a>
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