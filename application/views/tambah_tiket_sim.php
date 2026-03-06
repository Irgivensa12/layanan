<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!$this->session->userdata('userId')) { //jika tidak ada id
    $this->session->set_flashdata('type', 'alert-danger');
    $this->session->set_flashdata('pesan', '<strong>Error!</strong> Anda harus login terlebih dahulu');
    redirect();
    exit;
}

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
        </nav> <!-- navbar-->

        <form action="<?= base_url('Insert/tambah_tiket_sim') ?>" method="post" enctype="multipart/form-data" name="form" id="form">

            <div class="form-group row container-fluid ">
                <label class="col-2">Nomor Tiket</label>
                <input type="text" class="form-control col-6" name="tikNomor" placeholder="Masukan Nomor Tiket">
            </div>

            <div class="form-group row container-fluid">
                <label class="col-2">Tanggal</label>
                <input type="date" class="form-control col-2" name="tikTanggal">
            </div>
            <div class="form-group row container-fluid ">
                <label class="col-2">Nama</label>
                <input type="text" class="form-control col-6" name="tikUserID" placeholder="Masukan Nama Anda">
            </div>
            <div class="form-group row container-fluid">
                <label class="col-2">Divisi</label>
                <input type="text" class="form-control col-6" name="userNama" placeholder="Masukan Divisi Anda">
            </div>
            <div class="form-group row container-fluid">
                <label class="col-2">Ringkasan</label>
                <input type="text" class="form-control col-6" name="tikRingkasan" placeholder="Ringkasan Keluhan">
            </div>
            <div class="form-group row container-fluid">
                <label class="col-2">Detail</label>
                <input type="text" class="form-control col-6" name="tikDetail" placeholder="Detail Kerusakan">
            </div>

            <div class="container-fluid">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>


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

<!-- <script>
        // gunakan Javascript dan jQuery
        // bisa cek untuk contoh membuat form: https://getbootstrap.com/docs/4.0/components/forms/
		
        $(document).ready(function() { // jika jalaman web selesai diload, maka jalankan script ini
            $('#menuMenu').trigger('click'); //buka "Contoh Menu" (id: menuMenu) pada header
            
            getContoh1();
            
            $('#tabel').DataTable({ //https://datatables.net/reference/option/
                "scrollX": true,
                "select": true,
                "bSort": false
            });
        }); -->