        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Daftar Dana Masuk</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Dana Masuk</a></li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <div class="form-group">
                                <label for=""></label>

                                <select class="select2-single-placeholder form-control" name="id_proyek" id="proyek"
                                    onchange="check()">
                                    <option value=""></option>
                                    <?php foreach ($proyek as $key => $data):?>
                                    <option value="<?= $data['id']?>"><?= $data['nama_proyek']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush" id="myTableDana">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="text-align: center;">No</th>
                                        <th style="text-align: center;">Proyek</th>
                                        <th style="text-align: center;">Tanggal</th>
                                        <th style="text-align: center;">Keterangan</th>
                                        <th style="text-align: center;">Jumlah</th>
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
check = () => {
    console.log("Testing");
    var element = document.getElementById("proyek");
    var url = "<?php echo base_url('dashboard/dana_lap_list')?>" + "/" + element.value;
    $('#myTableDana').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": url,
            "type": "POST"
        },
        "lengthMenu": [
            [5, 10, 25],
            [5, 10, 25]
        ],
        "columnDefs": [{
                "targets": 0,
                "className": "dt-body-center"
            },
            {
                "targets": 2,
                "className": "dt-body-center"
            },
            {
                "targets": 4,
                "className": "dt-body-right"
            }
        ],
    });
}
// var element = document.getElementById("proyek"); element.onchange = function(event) {
// }
$(document).ready(function() {
    $('.select2-single-placeholder').select2({
        placeholder: "Pilih Proyek",
        allowClear: true
    });
    $('#myTableDana').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url('dashboard/dana_lap_list')?>",
            "type": "POST"
        },
        "lengthMenu": [
            [5, 10, 25],
            [5, 10, 25]
        ],
        "columnDefs": [{
                "targets": 0,
                "className": "dt-body-center"
            },
            {
                "targets": 2,
                "className": "dt-body-center"
            },
            {
                "targets": 4,
                "className": "dt-body-right"
            }
        ],
    });
});
        </script>
        <?= $this->endSection() ?>