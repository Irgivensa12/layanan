<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!$this->session->userdata('userId')) {
    $this->session->set_flashdata('type', 'alert-danger');
    $this->session->set_flashdata('pesan', '<strong>Error!</strong> Anda harus login terlebih dahulu');
    redirect();
    exit;
}
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

        <div class="container" style="max-width:900px;">

            <h3 class="text-center font-weight-bold mb-4">
                DATA LAYANAN SIM & SARPRAS
            </h3>

            <form action="<?= base_url('Insert/tambah_tiket_sim') ?>" method="post">

                <div class="card shadow p-4">

                    <!-- Nomor Tiket -->
                    <div class="form-group row mb-4 align-items-center">
                        <label class="col-md-3 font-weight-bold text-md-right pr-4">
                            Nomor Tiket
                        </label>
                       
                        <div class="col-md-9">
                            <input type="text"
                                class="form-control"
                                name="tikNomor"
                                value="<?= $no_tiket ?>" 
                                readonly
                              >
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div class="form-group row mb-4 align-items-center">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4">
                            Tanggal
                        </label>

                        <div class="col-md-5">
                            <input type="date"
                                class="form-control"
                                name="tikTanggal"
                                style="height:50px;font-size:16px;">
                        </div>

                    </div>

                    <!-- ID Karyawan -->
                    <div class="form-group row mb-4 align-items-center">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4">
                            ID Karyawan
                        </label>

                        <div class="col-md-9">
                            <input type="number"
                                class="form-control"
                                name="tikUserID"
                                value="<?= $this->session->userdata('userId'); ?>"
                                readonly
                                style="height:50px;font-size:16px;">
                        </div>

                    </div>

                    <!-- Ringkasan -->
                    <div class="form-group row mb-4 align-items-center">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4">
                            Ringkasan
                        </label>

                        <div class="col-md-9">
                            <input type="text"
                                class="form-control"
                                name="tikRingkasan"
                                placeholder="Ringkasan Keluhan"
                                style="height:50px;font-size:16px;">
                        </div>

                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group row mb-4">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4 pt-2">
                            Deskripsi Kerusakan
                        </label>

                        <div class="col-md-9">
                            <textarea
                                class="form-control"
                                name="tikDeskripsi"
                                rows="5"
                                placeholder="Deskripsi Kerusakan"
                                style="font-size:16px;padding:12px;">
</textarea>
                        </div>

                    </div>

                    <div class="text-left mt-3">
                        <button type="submit"
                            class="btn btn-primary"
                            style="padding:10px 30px;font-size:16px;">
                            Simpan
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <footer class="sticky-footer bg-white mt-4">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>© <?php echo date("Y"); ?> PT Kanisius.</span>
            </div>
        </div>
    </footer>

</div>



<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('js/sb-admin-2.js') ?>"></script>