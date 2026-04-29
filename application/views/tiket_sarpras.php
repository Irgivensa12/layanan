<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!$this->session->userdata('helpdesk_userId')) { //jika tidak ada id
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
        </nav>

        <div class="container-fluid">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">List Semua Keluhan SARPRAS</h1>
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

            <!-- Button Tambah -->
            <div class="form-group row">
                <div class="col-sm-2">
                    <a href="<?= base_url('tambah_tiket_sarpras') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <table id="tabel" class="display nowrap" style="width:100%">
                        <thead style="color: black;">
                            <tr>
                                <th style="width:10px">No</th>
                                <th>Tanggal</th>
                                <th>User ID</th>
                                <th>Keluhan</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th style="width:100px">Detail</th>
                            </tr>
                        </thead>
                        <tbody id="tbody" name="tbody" style="color: black;">

                        </tbody>
                    </table>
                </div>
            </div>
            <br>
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

    var table;

    $(document).ready(function() {

        $('#menuTiket').trigger('click');
        table = $('#tabel').DataTable({ //https://datatables.net/reference/option/
            "scrollX": true,
            "scrollY": "400px", // Membuat tabel bisa discroll vertikal dengan tinggi 400px
            "select": true,
            "bSort": false
        });
        getTiket_Sarpras(); // Memanggil function get
    });

    // menggunakan AJAX untuk membuat tabel dari data tabel
    // AJAX (View) -> Controller -> Model -> dapat hasil
     function getTiket_Sarpras() {

        $.ajax({
            url: "<?php echo base_url('Select/tiket_sarpras'); ?>",
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                console.log(data);

                data.sort(function(a, b) {
                    let numA = parseInt(a.layID.toString().split('-').pop()) || 0;
                    let numB = parseInt(b.layID.toString().split('-').pop()) || 0;
                    return numA - numB; // Urutkan berdasarkan angka di belakang ID
                });

                table.clear();

                for (var i = 0; i < data.length; i++) {

                    var tanggal_db = new Date(data[i].layTanggal);

                    var options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };

                    var tanggal_benar = tanggal_db.toLocaleDateString('id-ID', options);

                    var tanggal_db = new Date(data[i].layTanggal);

                    var options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };

                    table.row.add([
                        data[i].layID,
                        tanggal_benar,
                        data[i].layUserID,
                        data[i].layHal,
                        data[i].layDiskripsi,
                        data[i].layStatus == 'N' ?
                        '<button class="btn btn-success btn-sm statusBtn" data-id="' + data[i].layID + '">OPEN</button>' :
                        '<button class="btn btn-secondary btn-sm" disabled>CLOSED</button>',
                        '<button class="btn btn-primary btn-sm detailBtn" data-id="' +
                        data[i].layID + '" data-toggle="modal" data-target="#modalDetail">Detail</button>',
                        '<button class="btn btn-info btn-sm formSarprasBtn" data-id="' + data[i].layID + '" data-toggle="modal" data-target="#modalFormSarpras">Form Sarpras</button>'
                    ]);
                }

                table.draw();
            }
        });
    }

    $(document).on("click", ".detailBtn", function() { // Event listener untuk tombol detail

        var tikNomor = $(this).data("id"); // Mengambil data-id dari tombol yang diklik

        $.ajax({
            url: "<?= base_url('Select/detail_tiket_sarpras/') ?>" + tikNomor,
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                var tanggal_db = new Date(data.layTanggal);

                var options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };

                var tanggal_benar = tanggal_db.toLocaleDateString('id-ID', options);

                $("#m_layID").text(data.layID);
                $("#m_layTanggal").text(tanggal_benar);
                $("#m_userNama").text(data.userNama);
                $("#m_layHal").text(data.layHal);

                // Status dengan badge warna
                $("#m_layStatus").html(
                    data.layStatus === 'OPEN' ?
                    '<span class="badge badge-success">OPEN</span>' :
                    '<span class="badge badge-secondary">CLOSED</span>'
                );

                $('#m_layDiskripsi').html(data.layDiskripsi);

                // Tampilkan penanggung jawab atau teks default jika kosong
                $("#m_penanggungJawab").html(
                    data.penanggungJawab ?
                    data.penanggungJawab :
                    '<span class="text-muted">Belum ditentukan</span>'
                );
                $('#m_layTarget').text(data.layTarget);
            }
        });

    });
</script>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog" style="max-width:75%; width:75%; margin:40px auto;">
        <div class="modal-content" style="border-radius:15px;">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title text-center w-100" style="font-size:26px;">
                    DATA LAYANAN SARPRAS
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" style="font-size:30px;">&times;</button>
            </div>

            <!-- BODY -->
            <div class="modal-body" style="font-size:20px; padding:40px;">

                <div class="container-fluid">

                    <!-- Nomor Tiket -->
                    <div class="form-group row align-items-center mb-4">
                        <label class="col-md-3 font-weight-bold text-md-right pr-4">
                            Nomor Tiket
                        </label>

                        <div class="col-md-9">
                            <div id="m_layID" class="form-control d-flex align-items-center"
                                style="height:55px;font-size:20px;">
                            </div>
                        </div>
                    </div>


                    <!-- Tanggal -->
                    <div class="form-group row align-items-center mb-4">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4">
                            Tanggal
                        </label>

                        <div class="col-md-9">
                            <div id="m_layTanggal" class="form-control d-flex align-items-center"
                                style="height:55px;font-size:20px;width:300px;">
                            </div>
                        </div>

                    </div>


                    <!-- User -->
                    <div class="form-group row align-items-center mb-4">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4">
                            Penginput
                        </label>

                        <div class="col-md-9">
                            <div id="m_userNama" class="form-control d-flex align-items-center"
                                style="height:55px;font-size:20px;">
                            </div>
                        </div>

                    </div>


                    <!-- Status -->
                    <div class="form-group row align-items-center mb-4">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4">
                            Status
                        </label>

                        <div class="col-md-9">
                            <div id="m_layStatus" class="d-flex align-items-center font-weight-bold"
                                style="font-size:28px;">
                            </div>
                        </div>

                    </div>


                    <!-- Ringkasan -->
                    <div class="form-group row mb-4">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4 pt-3">
                            Ringkasan
                        </label>

                        <div class="col-md-9">
                            <div id="m_layHal" class="form-control"
                                style="font-size:20px;padding:20px;min-height:120px;">
                            </div>
                        </div>

                    </div>


                    <!-- Deskripsi -->
                    <div class="form-group row">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4 pt-3">
                            Deskripsi Keluhan
                        </label>

                        <div class="col-md-9">
                            <div id="m_layDiskripsi" class="form-control"
                                style="font-size:20px;padding:20px;min-height:200px;">
                            </div>
                        </div>

                    </div>

                    <!-- PENANGGUNG JAWAB -->
                    <div class="form-group row mt-4">
                        <label class="col-md-3 font-weight-bold text-md-right pr-4 pt-3">
                            Penanggung Jawab
                        </label>

                        <div class="col-md-9">
                            <div id="m_penanggungJawab" class="form-control"
                                style="font-size:20px;padding:20px;min-height:80px;">
                            </div>
                        </div>
                    </div>

                </div>

            </div>



            <!-- FOOTER -->
            <div class="modal-footer justify-content-center">

                <button type="button"
                    class="btn btn-danger"
                    style="font-size:20px;padding:10px 40px;"
                    data-dismiss="modal">

                    Tutup

                </button>

            </div>

        </div>
    </div>
</div>

</body>

</html>