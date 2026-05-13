<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!$this->session->userdata('helpdesk_userId')) {
    $this->session->set_flashdata('type', 'alert-danger');
    $this->session->set_flashdata('pesan', '<strong>Error!</strong> Anda harus login terlebih dahulu');
    redirect();
    exit;
}

$role = $this->session->userdata('role');
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

                    <?= ($role == 'admin') ? 'Halaman Admin SIM' : 'List Semua Kerusakan SIM'; ?>

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

                    <a href="<?= base_url('Controller/tambah_tiket_sim') ?>" class="btn btn-primary">

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

                                <?php if ($role == 'admin') { ?>
                                    <th>Penginput</th>
                                <?php } ?>

                                <th>Ringkasan</th>
                                <th>Status</th>
                                <th>Detail</th>

                                <?php if ($role == 'admin') { ?>
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

    var role = "<?= $role ?>";

    $(document).ready(function() {

        $('#menuTiket').trigger('click');

        table = $('#tabel').DataTable({

            "scrollX": true,
            "scrollY": "400px",
            "select": true,
            "bSort": false

        });

        getTiket_SIM();

    });

    // GET DATA TIKET
    function getTiket_SIM() {

        $.ajax({

            url: "<?php echo base_url('Select/tiket_sim'); ?>?status=<?= $this->input->get('status') ?>",

            method: "POST",

            dataType: "JSON",

            success: function(data) {

                console.log(data);

                table.clear();

                for (var i = 0; i < data.length; i++) {

                    var tanggal_db = new Date(data[i].tikTanggal);

                    var options = {

                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'

                    };

                    var tanggal_benar = tanggal_db.toLocaleDateString('id-ID', options);

                    // STATUS
                    var statusBtn = '';

                    if (data[i].tikStatus == 'N') {

                        statusBtn = '<span class="badge badge-success">OPEN</span>';

                    } else {

                        statusBtn = '<span class="badge badge-secondary">CLOSED</span>';

                    }

                    // DETAIL BUTTON
                    var detailBtn =
                        '<button class="btn btn-primary btn-sm detailBtn" data-id="' +
                        data[i].tikNomor +
                        '" data-toggle="modal" data-target="#modalDetail">Detail</button>';

                    // ACTION BUTTON ADMIN
                    var actionBtn = '';

                    if (role == 'admin') {

                        if (data[i].tikStatus == 'N') {

                            actionBtn =
                                '<button class="btn btn-success btn-sm statusBtn" data-id="' +
                                data[i].tikNomor +
                                '">Close</button>';

                        } else {

                            actionBtn =
                                '<button class="btn btn-secondary btn-sm" disabled>CLOSED</button>';

                        }

                    }

                    // ADMIN
                    if (role == 'admin') {

                        table.row.add([

                            data[i].tikNomor,
                            tanggal_benar,
                            data[i].userNama,
                            data[i].tikRingkasan,
                            statusBtn,
                            detailBtn,
                            actionBtn

                        ]);

                    }

                    // USER
                    else {

                        table.row.add([

                            data[i].tikNomor,
                            tanggal_benar,
                            data[i].tikRingkasan,
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

            url: "<?= base_url('Select/detail_tiket/') ?>" + tikNomor,

            type: "GET",

            dataType: "JSON",

            success: function(data) {

                var tanggal_db = new Date(data.tikTanggal);

                var options = {

                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'

                };

                var tanggal_benar = tanggal_db.toLocaleDateString('id-ID', options);

                $("#m_tikNomor").text(data.tikNomor);

                $("#m_tikTanggal").text(tanggal_benar);

                $("#m_userNama").text(data.userNama);

                $("#m_tikRingkasan").text(data.tikRingkasan);

                $("#m_tikStatus").html(

                    data.tikStatus === 'OPEN'

                    ?

                    '<span class="badge badge-success">OPEN</span>'

                    :

                    '<span class="badge badge-secondary">CLOSED</span>'

                );

                $('#m_tikDeskripsi').html(data.tikDeskripsi);

                $('#m_tikOperator').html(data.operatorNama ?? '-');

                $('#m_tikTindakan').html(data.tikTindakan ?? '-');

            }

        });

    });

    // CLOSE TIKET ADMIN
    $(document).on("click", ".statusBtn", function() {

        var tikNomor = $(this).data("id");

        if (confirm("Apakah yakin ingin menutup tiket ini?")) {

            $.ajax({

                url: "<?= base_url('Insert/close_tiket/') ?>" + tikNomor,

                type: "POST",

                success: function() {

                    alert("Tiket berhasil ditutup!");

                    getTiket_SIM();

                }

            });

        }

    });
</script>

<!-- MODAL -->
<div class="modal fade" id="modalDetail" tabindex="-1">

    <!-- UKURAN DISAMAKAN DENGAN SARPRAS -->
    <div class="modal-dialog" style="max-width:65%; width:65%; margin:20px auto;">

        <div class="modal-content" style="border-radius:12px;">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white py-2">

                <h4 class="modal-title text-center w-100" style="font-size:20px;">

                    DATA LAYANAN SIM

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

                            <div id="m_tikNomor"
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

                            <div id="m_tikTanggal"
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

                            <div id="m_tikStatus"
                                class="d-flex align-items-center"
                                style="height:38px;font-size:16px;font-weight:bold;">
                            </div>

                        </div>

                    </div>

                    <!-- RINGKASAN -->
                    <div class="form-group row mb-3">

                        <label class="col-md-3 font-weight-bold text-md-right pt-2">
                            Ringkasan
                        </label>

                        <div class="col-md-9">

                            <div id="m_tikRingkasan"
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

                            <div id="m_tikDeskripsi"
                                class="form-control"
                                style="font-size:14px;padding:10px;min-height:120px;">
                            </div>

                        </div>

                    </div>

                    <!-- OPERATOR -->
                    <div class="form-group row mb-3">

                        <label class="col-md-3 font-weight-bold text-md-right">
                            Operator
                        </label>

                        <div class="col-md-9">

                            <div id="m_tikOperator"
                                class="form-control d-flex align-items-center"
                                style="height:38px;font-size:14px;">
                            </div>

                        </div>

                    </div>

                    <!-- TINDAKAN -->
                    <div class="form-group row mb-3">

                        <label class="col-md-3 font-weight-bold text-md-right pt-2">
                            Tindakan
                        </label>

                        <div class="col-md-9">

                            <div id="m_tikTindakan"
                                class="form-control"
                                style="font-size:14px;padding:10px;min-height:120px;">
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

</body>

</html>