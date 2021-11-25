        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
       <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Pengguna</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page"><a href="#">Pengguna</a></li>
            </ol>
          </div>

          <div class="row">
            <!-- Datatables -->
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <!-- <h6 class="m-0 font-weight-bold text-secondary">Data Pegawai</h6> -->
                </div>
                <div class="table-responsive p-3">
                  <a href="<?=base_url('dashboard/users_add')?>" class="btn btn-outline-primary btn-sm" style="margin-bottom: 10px;margin-top: -17px;"><i class="fa fa-plus"></i> Tambah</a>
                  <table class="table align-items-center table-flush" id="myTableUsers">
                    <thead class="thead-light">
                      <tr>
                        <th style="text-align: center;">No</th>
                        <th style="text-align: center;">Nama</th>
                        <th style="text-align: center;">Hak Akses</th>
                        <th style="text-align: center;">Proyek</th>
                        <th style="text-align: center;">Username</th>
                        <th style="text-align: center;"><i class="fa fa-cog"></i></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>

              </div>
            </div>
          </div>

        </div>
        <!---Container Fluid-->
        <script>
          $(document).ready(function () {
            $('#myTableUsers').DataTable({ 
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "<?php echo base_url('dashboard/users_list')?>",
                    "type": "POST"
                },
                //optional
                "lengthMenu": [[5, 10, 25], [5, 10, 25]],
                "columnDefs": [
                    { "targets": 0, "className": "dt-body-center" },
                    { "targets": 5, "className": "dt-body-center" }
                ],
            });
          });
        </script>
        <script type="text/javascript">
          function deleteUsers(id){
              Swal.fire({
                  title: "",
                  text: "Anda yakin akan menghapus data pengguna?",
                  type: "warning",
                  showCancelButton: true,
                  cancelButtonText: "Batal",
                  cancelButtonClass: "btn-light",
                  confirmButtonColor: "#fc544b",
                  confirmButtonText: "Ya"
              }).then((res) => {
                  if(res.value){
                    var en = id + '-project';
                    var id_en = btoa(en);
                    $.ajax({
                        type: "DELETE",
                        url: "<?=base_url('dashboard/users_delete')?>",
                        cache:false,
                        data: {id:id_en},
                        success: function(response){
                            if(response.status == 201){
                              Swal.fire({
                                  title: "",
                                  text: response.message,
                                  type: "success",
                                  confirmButtonColor: "#66bb6a",
                                  confirmButtonText: "Ok"
                              }).then(function(){
                                location.reload();
                              });
                            } else {
                              Swal.fire({
                                  title: "",
                                  text: response.message,
                                  type: "error",
                                  confirmButtonColor: "#fc544b",
                                  confirmButtonText: "Ok"
                              });
                            }
                        }
                    });
                  } else if(res.dismiss == 'cancel'){
                    console.log('cancel');
                  }
               });
          }
        </script>
        <?= $this->endSection() ?>