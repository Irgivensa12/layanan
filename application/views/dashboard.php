<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div id="content-wrapper" class="d-flex flex-column">

    <div id="content">

        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <ul class="navbar-nav ml-auto">
                <img src="<?= base_url('img/kanisius.png') ?>">
            </ul>
        </nav>

        <div class="container-fluid">

            <div class="mb-4">
                <h2 class="font-weight-bold text-dark">
                    Halo, <?= $nama; ?> 👋
                </h2>
                <h5 class="text-primary">
                    <?= $role; ?>
                </h5>
            </div>

            <div class="row">

                <!-- Tiket SIM -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2"
                        onclick="window.location='<?= base_url('tiket_sim') ?>'" style="cursor: pointer;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="h6 font-weight-bold text-primary text-uppercase mb-1">
                                        Tiket SIM
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800" id="tiket_sim">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tiket Sarpras -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2"
                        onclick="window.location='<?= base_url('tiket_sarpras') ?>'"
                        style="cursor: pointer;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="h6 font-weight-bold text-primary text-uppercase mb-1">
                                        Tiket Sarpras
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800" id="tiket_sarpras">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tools fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>© <?= date("Y"); ?> PT Kanisius.</span>
            </div>
        </div>
    </footer>

</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Script Section (SAMAKAN DENGAN tiket_sarpras) -->
<script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('js/sb-admin-2.js') ?>"></script>

<script>
    $(document).ready(function() {
        loadDashboard();
    });

    function loadDashboard() {
        $.ajax({
            url: "<?= base_url('Select/dashboard') ?>",
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                $('#tiket_sim').text(data.tiket_sim);
                $('#tiket_sarpras').text(data.tiket_sarpras);
            }
        });
    }
</script>