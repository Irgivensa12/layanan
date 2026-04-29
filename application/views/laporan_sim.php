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
                <h1 class="h3 mb-0 text-gray-800">Laporan SIM (Sistem Informasi Manajemen) PT Kanisius</h1>
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

            <div class="row">
                <div class="col">
                    <table id="tabel" class="display nowrap" style="width:100%">
                        <thead style="color: black;">
                            <tr>
                                <th style="width:10px">No</th>
                                <th>Tanggal</th>
                                <th>Penginput</th>
                                <th>Ringkasan</th>
                                <th>Status</th>
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

<!-- DataTables untuk Export file excel -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

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
        $('#menuLaporan').trigger('click');
        table = $('#tabel').DataTable({ //https://datatables.net/reference/option/
            "scrollX": true,
            "scrollY": "400px", // Membuat tabel bisa discroll vertikal dengan tinggi 400px
            "select": true,
            "bSort": false,

            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Download Excel',
                title: 'Laporan_Tiket_SIM_PT_Kanisius',
                className: 'btn btn-success btn-lg'
            }]
        });
        getTiket_SIM(); // Memanggil function get
    });

    // menggunakan AJAX untuk membuat tabel dari data tabel
    // AJAX (View) -> Controller -> Model -> dapat hasil
    function getTiket_SIM() {

        $.ajax({
            url: "<?php echo base_url('Select/tiket_sim'); ?>",
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                //var html = '';
                table.clear();

                for (var i = 0; i < data.length; i++) {
                    // Membuat tanggal format Indonesia
                    var tanggal_db = new Date(data[i].tikTanggal); // Mengubah string tanggal dari database menjadi objek Date

                    var options = { // Opsi untuk format tanggal Indonesia
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };

                    var statusBtn = '';

                    if (data[i].tikStatus == 'N') {
                        statusBtn = '<span class="badge badge-success">OPEN</span>';
                    } else {
                        statusBtn = '<span class="badge badge-secondary">CLOSED</span>';
                    }
                    var tanggal_benar = tanggal_db.toLocaleDateString('id-ID', options); // Mengubah tanggal menjadi format Indonesia
                    table.row.add([
                        data[i].tikNomor,
                        tanggal_benar,
                        data[i].tikUserID,
                        data[i].tikRingkasan,
                        statusBtn
                    ]);
                }

                table.draw();
            }
        });
    }

    // kalau butuh input dari user
    function getContoh2() {
        var test = 'inputtan dari user';
        // var test = $('#nama_input_id).val();

        var url = "<?php echo base_url('Insert/contoh2?'); ?>"; //Controller Insert.php, function contoh2()
        var urlBaru = url + "inputAjax=" + test;

        var html = '';
        var no = 1;

        $.ajax({
            url: urlBaru,
            method: "POST",
            dataType: "JSON",
            async: false,
            success: function(data) {
                for (var i = 0; i < data.length; i++) {

                    html += '<tr>';
                    html += '<td>' + no + '</td>';
                    html += '<td></td>';
                    html += '<td></td>';
                    html += '<td></td>';
                    html += '</tr>';

                    no++;
                }
                $("#tbody").html(html);
            }
        });
    }
</script>

<!-- Modal -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog" style="max-width:75%; width:75%; margin:40px auto;">
        <div class="modal-content" style="border-radius:15px;">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title" style="font-size:26px;">
                    DATA LAYANAN SIM
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
                            <div id="m_tikNomor" class="form-control d-flex align-items-center"
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
                            <div id="m_tikTanggal" class="form-control d-flex align-items-center"
                                style="height:55px;font-size:20px;width:300px;">
                            </div>
                        </div>

                    </div>


                    <!-- User -->
                    <div class="form-group row align-items-center mb-4">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4">
                            User
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
                            <div id="m_tiketStatus" class="form-control d-flex align-items-center"
                                style="height:55px;font-size:20px;width:200px;">
                            </div>
                        </div>

                    </div>


                    <!-- Ringkasan -->
                    <div class="form-group row mb-4">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4 pt-3">
                            Ringkasan
                        </label>

                        <div class="col-md-9">
                            <div id="m_tikRingkasan" class="form-control"
                                style="font-size:20px;padding:20px;min-height:120px;">
                            </div>
                        </div>

                    </div>


                    <!-- Deskripsi -->
                    <div class="form-group row">

                        <label class="col-md-3 font-weight-bold text-md-right pr-4 pt-3">
                            Deskripsi Kerusakan
                        </label>

                        <div class="col-md-9">
                            <div id="m_tikDeskripsi" class="form-control"
                                style="font-size:20px;padding:20px;min-height:200px;">
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