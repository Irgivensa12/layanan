<?php
defined('BASEPATH') or exit('No direct script access allowed');

//if (!isset($_SESSION["id_crm"])) { //jika tidak ada id
//    $this->session->set_flashdata('type', 'alert-danger');
//    $this->session->set_flashdata('pesan', '<strong>Error!</strong> Anda harus login terlebih dahulu');
//    redirect();
//    exit;
//}

?>
<div id="content-wrapper" class="d-flex flex-column">

    <div id="content">

        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <ul class="navbar-nav ml-auto">
                <img src="<?= base_url('img\kanisius.png') ?>">
            </ul>
        </nav>
        <div class="container-fluid">

            <div class="row">

                <!-- Pelanggan Baru -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Pelanggan Baru
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800" id="jml_pelanggan_baru">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wishlist -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Wishlist Pelanggan
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800" id="jml_wishlist_pelanggan">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-heart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registrasi -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Registrasi / Perpanjang KRC
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800" id="jml_registrasi">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-id-card fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Masa Aktif -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Masa Aktif KRC Habis
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800" id="jml_krc_habis">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-id-card fa-2x text-gray-300"></i>
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
                <span>© <?php echo date("Y"); ?> PT Kanisius.</span>
            </div>
        </div>
    </footer>
</div>
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

                console.log(data); // untuk debug

                $('#jml_pelanggan_baru').text(data.pelanggan_baru); // isi data pelanggan baru ke dalam card Pelanggan Baru
                $('#jml_wishlist_pelanggan').text(data.wishlist_pelanggan);
            }
        });
    }
</script>