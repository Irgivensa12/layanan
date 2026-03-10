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
        </nav>

        <div class="container-fluid">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Halaman Admin</h1>
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
                                <th>Detail</th>
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
        $('#admin').trigger('click');
        table = $('#tabel').DataTable({ //https://datatables.net/reference/option/
            "scrollX": true,
            "scrollY": "400px", // Membuat tabel bisa discroll vertikal dengan tinggi 400px
            "select": true,
            "bSort": false
        });
        getAdmin_SIM(); // Memanggil function get
    });

    // menggunakan AJAX untuk membuat tabel dari data tabel
    // AJAX (View) -> Controller -> Model -> dapat hasil
    function getAdmin_SIM() {

        $.ajax({
            url: "<?php echo base_url('Select/admin_sim'); ?>",
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                //var html = '';
                table.clear();

                for (var i = 0; i < data.length; i++) {

                    table.row.add([
                        data[i].tikNomor,
                        data[i].tikTanggal,
                        data[i].tikUserID,
                        data[i].tikRingkasan,
                        data[i].tikStatus == 'N' ? // jika tikStatus = N, maka tampilkan tombol OPEN, jika tidak tampilkan tombol CLOSED
                        '<button class="btn btn-success btn-sm statusBtn" data-id="' + data[i].tikNomor + '">OPEN</button>' :
                        '<button class="btn btn-secondary btn-sm" disabled>CLOSED</button>',

                        '<button class="btn btn-primary btn-sm detailBtn" data-id="' + data[i].tikNomor + '" data-toggle="modal" data-target="#exampleModal">Detail</button>'
                    ]);
                }

                table.draw();
            }
        });
    }

    $(document).on("click", ".detailBtn", function() { // Event listener untuk tombol detail

        var tikNomor = $(this).data("id");

        $.ajax({
            url: "<?= base_url('Select/detail_tiket/') ?>" + tikNomor,
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                $("#m_tikNomor").text(data.tikNomor);
                $("#m_tikTanggal").text(data.tikTanggal);
                $("#m_userNama").text(data.userNama);
                $("#m_tikRingkasan").text(data.tikRingkasan);
                $("#m_tiketStatus").text(data.tiketStatus);

            }
        });

    });

    $(document).on("click", ".statusBtn", function() { // Event listener untuk tombol status

        var tikNomor = $(this).data("id");

        if (confirm("Apakah Anda yakin ingin menutup tiket ini?")) {

            $.ajax({
                url: "<?= base_url('Controller/close_tiket/') ?>" + tikNomor,
                type: "POST",
                success: function() {

                    alert("Tiket berhasil ditutup!");

                    getAdmin_SIM(); // reload tabel

                }
            });

        }

    });

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

<div class="modal fade" id="exampleModal" tabindex="-1">
    <div class="modal-dialog" style="max-width:80%; width:80%; margin:30px auto;">
        <div class="modal-content" style="border-radius:15px;">

            <div class="modal-header bg-primary text-white" style="padding:25px;">
                <h2 class="modal-title" style="font-size:28px;">Detail Tiket</h2>
                <button type="button" class="close text-white" data-dismiss="modal" style="font-size:32px;">
                    &times;
                </button>
            </div>

            <div class="modal-body" style="font-size:22px; padding:40px;">

                <div class="container-fluid">

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong style="font-size:24px;">No Tiket</strong>
                            <div id="m_tikNomor"
                                style="font-size:24px; padding:20px; background:#f8f9fa; border-radius:10px; margin-top:10px;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <strong style="font-size:24px;">Tanggal</strong>
                            <div id="m_tikTanggal"
                                style="font-size:24px; padding:20px; background:#f8f9fa; border-radius:10px; margin-top:10px;">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong style="font-size:24px;">User</strong>
                            <div id="m_userNama"
                                style="font-size:24px; padding:20px; background:#f8f9fa; border-radius:10px; margin-top:10px;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <strong style="font-size:24px;">Status</strong>
                            <div id="m_tiketStatus"
                                style="font-size:24px; padding:20px; background:#f8f9fa; border-radius:10px; margin-top:10px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <strong style="font-size:24px;">Ringkasan</strong>
                            <div id="m_tikRingkasan"
                                style="font-size:24px; padding:25px; background:#f8f9fa; border-radius:10px; margin-top:10px; min-height:180px;">
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer justify-content-center" style="padding:25px;">
                <button type="button" class="btn btn-danger"
                    style="font-size:22px; padding:12px 40px;"
                    data-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

</body>

</html>