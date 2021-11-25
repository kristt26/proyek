        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
       <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Profile</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-5 col-md-7 mb-5">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">
                        <img class="img-profile rounded-circle" src="<?=base_url()?>/public/template/img/boy.png" style="max-width: 80px">
                      </div>
                      <div class="h6 mb-0 text-gray-800">
                        <table>
                          <tr>
                            <td style="width: 90px;">Nama</td>
                            <td style="width: 5px;text-align: center;">:</td>
                            <td> &nbsp; <?=$data['nama']?></td>
                          </tr>
                          <tr>
                            <td style="width: 90px;">Username</td>
                            <td style="width: 5px;text-align: center;">:</td>
                            <td> &nbsp; <?=$data['username']?></td>
                          </tr>
                           <tr>
                            <td style="width: 90px;">Hak Akses</td>
                            <td style="width: 5px;text-align: center;">:</td>
                            <td> &nbsp; <?=ucfirst($data['hak_akses'])?></td>
                          </tr>
                          <tr>
                            <td style="width: 90px;">Proyek</td>
                            <td style="width: 5px;text-align: center;">:</td>
                            <td> &nbsp; <?=$lib->getNamaProyek($data['id_proyek'])?></td>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  
          </div>
          <!--Row-->
        </div>
        <!---Container Fluid-->
        <?= $this->endSection() ?>