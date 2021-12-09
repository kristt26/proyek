        <?=$this->extend('layout/layout')?>
        <?=$this->section('content')?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Daftar Dana Operasional</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Dana Operasional</a></li>
                </ol>
            </div>

            <div class="row">
                <!-- Datatables -->
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <div class="form-group">
                                <label for=""></label>
                                <select class="select2-single-placeholder form-control" name="id_proyek" id="proyek">
                                    <option value=""></option>
                                    <?php foreach ($proyek as $key => $data): ?>
                                    <option value="<?=$data['id'] . '-' . $data['nama_proyek']?>">
                                        <?=$data['nama_proyek']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive p-3">
                            <a href="<?=base_url('dashboard/operasional_add')?>" class="btn btn-outline-primary btn-sm"
                                style="margin-bottom: 10px;margin-top: -17px;"><i class="fa fa-plus"></i> Tambah</a>
                            <table class="table align-items-center table-flush table-bordered" id="myTableOperasional">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="text-align: center;">No</th>
                                        <th style="text-align: center;">Nama Proyek</th>
                                        <th style="text-align: center;">Tanggal</th>
                                        <th style="text-align: center;">Keterangan Kebutuhan</th>
                                        <th style="text-align: center;">Nominal</th>
                                        <th style="text-align: center;"><i class="fa fa-cog"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align: center;"><strong>Total</strong></td>
                                        <td style="text-align: right; font-weight: bold;"></td>
                                        <td style="text-align: center;"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <!---Container Fluid-->
        <script>
$(document).ready(function() {
    $("#proyek").change(function() {
        var item = $("#proyek").val().split("-");
        var url = "<?php echo base_url('dashboard/operasional_lap_list') ?>" + "/" + item[0];
        var urlf = "<?php echo base_url('dashboard/operasional_lap_list_foot') ?>" + "/" + item[0];
        var table;
        if (table) {
            table.destroy();
        }
        table = $('#myTableOperasional').DataTable({
            "destroy": true,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": url,
                "type": "POST"
            },
            'footerCallback': function(tfoot, data, start, end, display) {
                $.ajax({
                    "async": true,
                    "crossDomain": true,
                    "url": urlf,
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json"
                    },
                }).done(function(response) {
                    if (response) {
                        var $td = $(tfoot).find('td');
                        var a = $td.eq(1).html(response.jumlah);
                        console.log(a);
                    }
                });
            },
            //optional
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
                },
            ],
        });
    })

});
        </script>
        <script type="text/javascript">
function deleteOperasional(id) {
    Swal.fire({
        title: "",
        text: "Anda yakin akan menghapus data dana operasional?",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Batal",
        cancelButtonClass: "btn-light",
        confirmButtonColor: "#fc544b",
        confirmButtonText: "Ya"
    }).then((res) => {
        if (res.value) {
            var en = id + '-project';
            var id_en = btoa(en);
            $.ajax({
                type: "DELETE",
                url: "<?=base_url('dashboard/operasional_delete')?>",
                cache: false,
                data: {
                    id: id_en
                },
                success: function(response) {
                    if (response.status == 201) {
                        Swal.fire({
                            title: "",
                            text: response.message,
                            type: "success",
                            confirmButtonColor: "#66bb6a",
                            confirmButtonText: "Ok"
                        }).then(function() {
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
        } else if (res.dismiss == 'cancel') {
            console.log('cancel');
        }
    });
}
        </script>
        <?=$this->endSection()?>