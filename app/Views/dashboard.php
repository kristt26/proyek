        <?= $this->extend('layout/layout') ?>
        <?= $this->section('content') ?>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>


        </div>
        <script src="<?=base_url()?>/template/js/demo/chart-area-demo.js"></script>
        <script src="<?=base_url()?>/template/js/demo/chart-pie-demo.js"></script>
        <script src="<?=base_url()?>/template/js/demo/chart-bar-demo.js"></script>
        <!---Container Fluid-->
        <?= $this->endSection() ?>