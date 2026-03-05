<?php
defined('BASEPATH') or exit('No direct script access allowed');

//if (!isset($_SESSION["id_crm"])) { //jika tidak ada id
//    $this->session->set_flashdata('type', 'alert-danger');
//    $this->session->set_flashdata('pesan', '<strong>Error!</strong> Anda harus login terlebih dahulu');
//    redirect();
//    exit;
//}

?>
<!-- untuk daftar menu dst, cek header.php-->

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

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Laporan Sarana dan Pra Sarana PT Kanisius</h1>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <?php if (isset($_SESSION["pesan"])) { ?>
                        <div class="alert <?= $_SESSION['type'] ?> alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?= $_SESSION['pesan'] ?>
                        </div>
                    <?php unset($_SESSION['pesan']);
                    } ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="mb-4">
                    <div class="mb-4">
                        <p>Fitur ini masih dalam pengembangan, silakan hubungi Penpen dan Chandra untuk informasi lebih lanjut :)</p>
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

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Harus Menggunakan base_url karena asset dipanggil relatif terhadap URL aktif -->
    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= base_url('js/sb-admin-2.js') ?>"></script>

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('vendor/datatables/jquery.dataTables.min.css') ?>">
    <script type="text/javascript" charset="utf8" src="<?= base_url('vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" charset="utf8" src="<?= base_url('vendor/datatables/dataTables.select.min.js') ?>"></script>

    <!-- CKEditor -->
    <script src="<?= base_url('vendor/ckeditor/ckeditor.js') ?>"></script>
    <script src="<?= base_url('vendor/ckeditor/translations/id.js') ?>"></script>

    <!-- xlsx -->
    <script type="text/javascript" src="<?= base_url('vendor/xlsx/xlsx.fix.js') ?>"></script>

    <script>
        // gunakan Javascript dan jQuery
        // bisa cek untuk contoh membuat form: https://getbootstrap.com/docs/4.0/components/forms/

        // var table;

        // $(document).ready(function() {

        //     $('#menuMenu').trigger('click');
        //     table = $('#tabel').DataTable({ //https://datatables.net/reference/option/
        //         "scrollX": true,
        //         "select": true,
        //         "bSort": false
        //     });
        //     getTiket_Sarpras(); // Memanggil function get
        // });

        $(document).ready(function() {
            $('#menuLaporan').trigger('click');
        });
    </script>


    </body>

    </html>