<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!isset($_SESSION["id"])) { //jika tidak ada id
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
                <img src="img/kanisius.png">
            </ul>
        </nav> <!-- navbar-->

        <form action="<?= base_url('Insert/input') ?>" method="post" enctype="multipart/form-data" name="form" id="form">

            <div class="form-group row container-fluid">
                <label class="col-2">Tanggal</label>
                <input type="date" class="form-control col-2" name="tanggal">
            </div>
            <div class="form-group row container-fluid ">
                <label class="col-2">Penginput</label>
                <input type="nama_buku" class="form-control col-6" name="nama_buku" placeholder="Judul buku">
            </div>
            <div class="form-group row container-fluid">
                <label class="col-2">Penulis</label>
                <input type="penulis" class="form-control col-6" name="penulis" placeholder="Penulis">
            </div>
            <div class="form-group row container-fluid">
                <label class="col-2">Penerbit</label>
                <input type="penerbit" class="form-control col-6" name="penerbit" placeholder="Penerbit">
            </div>

            <div class="container-fluid">
                <button type="submit" class="btn btn-primary">Submit</button>
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

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.js"></script>

<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="vendor/datatables/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="vendor/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="vendor/datatables/dataTables.select.min.js"></script>

<!-- CKEditor -->
<script src="vendor/ckeditor/ckeditor.js"></script>
<script src="vendor/ckeditor/translations/id.js"></script>

<!-- xlsx -->
<script type="text/javascript" src="vendor/xlsx/xlsx.fix.js"></script>

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