<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!$this->session->userdata('helpdesk_userId')) {
    $this->session->set_flashdata('type', 'alert-danger');
    $this->session->set_flashdata('pesan', '<strong>Error!</strong> Anda harus login terlebih dahulu');
    redirect();
    exit;
}

$admin = $this->session->userdata('admin');
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

            <!-- JUDUL -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">

                <h1 class="h3 mb-0 text-gray-800">
                    <?= ($admin == 'Y') ? 'Halaman Admin SARPRAS' : 'List Semua Keluhan SARPRAS'; ?>
                </h1>
            </div>

            <!-- ALERT -->
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

            <!-- BUTTON TAMBAH -->
            <div class="form-group row">

                <div class="col-sm-2">

                    <a href="<?= base_url('tambah_tiket_sarpras') ?>" class="btn btn-primary">

                        <i class="fas fa-plus"></i> Tambah

                    </a>

                </div>

            </div>

            <!-- TABLE -->
            <div class="row">

                <div class="col">

                    <table id="tabel" class="display nowrap" style="width:100%">

                        <thead style="color: black;">

                            <tr>

                                <th>No</th>
                                <th>Tanggal</th>

                                <?php if ($admin == 'Y') { ?>
                                    <th>Penginput</th>
                                <?php } ?>

                                <th>Nama</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                                <th>Detail</th>

                                <?php if ($admin == 'Y') { ?>
                                    <th>Action</th>
                                <?php } ?>

                            </tr>

                        </thead>

                        <tbody id="tbody" style="color:black;">

                        </tbody>

                    </table>

                </div>

            </div>

            <br>

        </div>

    </div>

    <!-- FOOTER -->
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

<!-- JS -->
<script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('js/sb-admin-2.js') ?>"></script>

<!-- DATATABLE -->
<link rel="stylesheet" href="<?= base_url('vendor/datatables/jquery.dataTables.min.css') ?>">

<script src="<?= base_url('vendor/datatables/jquery.dataTables.min.js') ?>"></script>

<script>
    var table;

    var admin = "<?= $admin ?>";

    $(document).ready(function() {

        $('#menuTiket').trigger('click');

        table = $('#tabel').DataTable({

            "scrollX": true,
            "scrollY": "400px",
            "select": true,
            "bSort": false

        });

        getTiket_Sarpras();

    });

    // GET DATA TIKET
    function getTiket_Sarpras() {

        $.ajax({

            url: "<?php echo base_url('Select/tiket_sarpras'); ?>?status=<?= $this->input->get('status') ?>",

            method: "POST",

            dataType: "JSON",

            success: function(data) {

                console.log(data);

                table.clear();

                for (var i = 0; i < data.length; i++) {

                    var tanggal_db = new Date(data[i].layTanggal);

                    var options = {

                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'

                    };

                    var tanggal_benar = tanggal_db.toLocaleDateString('id-ID', options);

                    // STATUS
                    var statusBtn = '';

                    if (data[i].layStatus == 'N') {

                        statusBtn = '<span class="badge badge-success">OPEN</span>';

                    } else {

                        statusBtn = '<span class="badge badge-secondary">CLOSED</span>';

                    }

                    // DETAIL BUTTON
                    var detailBtn =
                        '<button class="btn btn-primary btn-sm detailBtn" data-id="' +
                        data[i].layID +
                        '" data-toggle="modal" data-target="#modalDetail">Detail</button>';

                    // ACTION BUTTON ADMIN
                    var actionBtn = '';

                    if (admin == 'Y') {

                        if (data[i].layStatus == 'N') {

                            actionBtn =
                                '<button class="btn btn-success btn-sm statusBtn" data-id="' +
                                data[i].layID +
                                '">Close</button>';

                        } else {

                            actionBtn =
                                '<button class="btn btn-secondary btn-sm" disabled>CLOSED</button>';

                        }

                    }

                    // ADMIN
                    if (admin == 'Y') {

                        table.row.add([

                            data[i].layID,
                            tanggal_benar,
                            data[i].layUserID,
                            data[i].userNama,
                            data[i].layHal,
                            statusBtn,
                            detailBtn,
                            actionBtn
                        ]);
                    }

                    // USER
                    else {

                        table.row.add([

                            data[i].layID,
                            tanggal_benar,
                            data[i].userNama,
                            data[i].layHal,
                            statusBtn,
                            detailBtn
                        ]);
                    }
                }
                table.draw();
            }
        });

    }

    // DETAIL
    $(document).on("click", ".detailBtn", function() {

        var tikNomor = $(this).data("id");

        $.ajax({

            url: "<?= base_url('Select/detail_tiket_sarpras/') ?>" + tikNomor,

            type: "GET",

            dataType: "JSON",

            success: function(data) {

                console.log(data);

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

                $("#m_layStatus").html(
                    data.layStatus == 'N' ?
                    '<span class="badge badge-success">OPEN</span>' :
                    '<span class="badge badge-secondary">CLOSED</span>'
                );

                $("#m_layDiskripsi").html(data.layDiskripsi);

                $("#m_penanggungJawab").html(
                    data.penanggungJawab ?
                    data.penanggungJawab :
                    '<span class="text-muted">Belum ditentukan</span>'
                );

                $("#m_layTarget").text(data.layTarget);

            }

        });

    });

    // CLOSE TIKET ADMIN
    $(document).on("click", ".statusBtn", function() {

        var layID = $(this).data("id");

        if (confirm("Apakah yakin ingin menutup tiket ini?")) {

            $.ajax({

                url: "<?= base_url('Insert/close_tiket_sarpras/') ?>" + layID,

                type: "POST",

                success: function() {

                    alert("Tiket berhasil ditutup!");

                    getTiket_Sarpras();

                }

            });

        }

    });
</script>
<!-- MODAL -->
<div class="modal fade" id="modalDetail" tabindex="-1">

    <!-- DIPERKECIL -->
    <div class="modal-dialog" style="max-width:65%; width:65%; margin:20px auto;">

        <div class="modal-content" style="border-radius:12px;">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white py-2">

                <h4 class="modal-title text-center w-100" style="font-size:20px;">

                    DATA LAYANAN SARPRAS

                </h4>

                <button type="button"
                    class="close text-white"
                    data-dismiss="modal"
                    style="font-size:24px;">

                    &times;

                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body" style="font-size:14px; padding:20px;">

                <div class="container-fluid">

                    <!-- NOMOR -->
                    <div class="form-group row mb-3">

                        <label class="col-md-3 font-weight-bold text-md-right">
                            Nomor Tiket
                        </label>

                        <div class="col-md-9">

                            <div id="m_layID"
                                class="form-control d-flex align-items-center"
                                style="height:38px;font-size:14px;">
                            </div>

                        </div>

                    </div>

                    <!-- TANGGAL -->
                    <div class="form-group row mb-3">

                        <label class="col-md-3 font-weight-bold text-md-right">
                            Tanggal
                        </label>

                        <div class="col-md-9">

                            <div id="m_layTanggal"
                                class="form-control d-flex align-items-center"
                                style="height:38px;font-size:14px;">
                            </div>

                        </div>

                    </div>

                    <!-- USER -->
                    <div class="form-group row mb-3">

                        <label class="col-md-3 font-weight-bold text-md-right">
                            Penginput
                        </label>

                        <div class="col-md-9">

                            <div id="m_userNama"
                                class="form-control d-flex align-items-center"
                                style="height:38px;font-size:14px;">
                            </div>

                        </div>

                    </div>

                    <!-- STATUS -->
                    <div class="form-group row mb-3">

                        <label class="col-md-3 font-weight-bold text-md-right">
                            Status
                        </label>

                        <div class="col-md-9">

                            <div id="m_layStatus"
                                class="d-flex align-items-center"
                                style="height:38px;font-size:16px;font-weight:bold;">
                            </div>

                        </div>

                    </div>

                    <!-- KELUHAN -->
                    <div class="form-group row mb-3">

                        <label class="col-md-3 font-weight-bold text-md-right pt-2">
                            Keluhan
                        </label>

                        <div class="col-md-9">

                            <div id="m_layHal"
                                class="form-control"
                                style="font-size:14px;padding:10px;min-height:80px;">
                            </div>

                        </div>

                    </div>

                    <!-- DESKRIPSI -->
                    <div class="form-group row mb-3">

                        <label class="col-md-3 font-weight-bold text-md-right pt-2">
                            Deskripsi
                        </label>

                        <div class="col-md-9">

                            <div id="m_layDiskripsi"
                                class="form-control"
                                style="font-size:14px;padding:10px;min-height:120px;">
                            </div>

                        </div>

                    </div>

                    <!-- PENANGGUNG JAWAB -->
                    <div class="form-group row mb-3">

                        <label class="col-md-3 font-weight-bold text-md-right pt-2">
                            Penanggung Jawab
                        </label>

                        <div class="col-md-9">

                            <div id="m_penanggungJawab"
                                class="form-control"
                                style="font-size:14px;padding:10px;min-height:80px;">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer justify-content-center py-2">

                <button type="button"
                    class="btn btn-danger"
                    style="font-size:14px;padding:6px 24px;"
                    data-dismiss="modal">

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>

</html>