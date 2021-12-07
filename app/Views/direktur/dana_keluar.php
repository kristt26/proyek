        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Daftar Dana Keluar</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Dana Keluar</a></li>
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
                                    <?php foreach ($proyek as $key => $data):?>
                                    <option value="<?= $data['id'].'-'.$data['nama_proyek']?>">
                                        <?= $data['nama_proyek']?></option>
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
    $("#proyek").change(function() {
        var item = $("#proyek").val().split("-");
        var url = "<?php echo base_url('dashboard/operasional_lap_list')?>" + "/" + item[0];
        var table;
        if (table) {
            table.destroy();
        }
        table = $('#myTableDana').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": url,
                "type": "POST"
            },

            "columnDefs": [{
                    "targets": 0,
                    "className": "dt-body-center"
                },
                {
                    "targets": 1,
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
            dom: 'Bfrtip',
            buttons: {
                buttons: [{
                    extend: 'print',
                    className: 'btn btn-primary btn-icon-split',
                    titleAttr: 'Stampa Tabella',
                    text: '<span class="icon text-white-50"><i class="fa fa-print"></i></span><span class="text"> Print</span>',
                    footer: false,
                    //autoPrint: false,
                    customize: function(doc) {
                        var now = new Date();
                        var jsDate = now.getDate() + '-' + (now.getMonth() + 1) +
                            '-' + now.getFullYear();
                        $(doc.document.body)

                            .prepend(
                                '<div style="position:absolute; top:10; left:50;font-size:30px;">Dana Masuk</div>'
                            )
                            .prepend(
                                '<div style="position:absolute; top:30; left:450;font-size:20px;margin-botton:50px">Proyek:' +
                                item[1] + '</div>'
                            )

                        $(doc.document.body).find('table')
                            .removeClass('dataTable')
                            .css('font-size', '12px')
                            .css('margin-top', '65px')
                            .css('margin-bottom', '60px')
                        $(doc.document.body).find('th').each(function(index) {
                            $(this).css('font-size', '18px');
                            $(this).css('color', '#fff');
                            $(this).css('background-color', 'blue');
                        });
                    },
                    title: '',
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)',
                        orthogonal: "Export-print"
                    },
                    init: function(api, node, config) {
                        $(node).removeClass('dt-button')
                    }
                }],
                dom: {
                    container: {
                        className: 'dt-buttons'
                    },
                    button: {
                        className: 'btn btn-default'
                    }
                }
            }
        });
    })
});
        </script>
        <?= $this->endSection() ?>