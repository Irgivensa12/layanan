<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!$this->session->userdata('helpdesk_userId')) {
    $this->session->set_flashdata('type', 'alert-danger');
    $this->session->set_flashdata(
        'pesan',
        '<strong>Error!</strong> Anda harus login terlebih dahulu'
    );

    redirect();
    exit;
}

// SESSION
$admin = $this->session->userdata('admin');
$userId = $this->session->userdata('helpdesk_userId');
?>
<!-- untuk daftar menu dst, cek header.php-->

<div id="content-wrapper" class="d-flex flex-column">

    <div id="content">

        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <ul class="navbar-nav ml-auto">
                <img src="<?= base_url('img/kariban.png') ?>">
            </ul>
        </nav>

        <div class="container-fluid">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">List Semua Kerusakan SIM</h1>
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
                    <a href="<?= base_url('Controller/tambah_tiket_sim') ?>" class="btn btn-primary">
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
                                <th>Penginput</th>
                                <th>Ringkasan</th>
                                <th>Status</th>
                                <th>Detail</th>
                                <?php if ("<?= $admin ?>" == 'Y') { ?>
                                    <th>Action</th>
                                <?php } ?>
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
                <span>© <?php echo date("Y"); ?> PT Kariban.</span>
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
        $('#admin').trigger('onclick');
        table = $('#tabel').DataTable({ //https://datatables.net/reference/option/
            "scrollX": true,
            "scrollY": "400px", // Membuat tabel bisa discroll vertikal dengan tinggi 400px
            "select": true,
            "bSort": false
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

                    if (data[i].tikStatus == 'N') { // Jika status tiket 'N' (OPEN) maka tampilkan tombol OPEN, jika tidak tampilkan tombol CLOSED

                        statusBtn =
                            '<button class="btn btn-success btn-sm" disabled>OPEN</button>';

                    } else {

                        statusBtn =
                            '<button class="btn btn-secondary btn-sm" disabled>CLOSED</button>';
                    }

                    // DETAIL
                    var detailBtn =
                        '<button class="btn btn-primary btn-sm detailBtn" data-id="' +
                        data[i].tikNomor +
                        '" data-toggle="modal" data-target="#modalDetail">Detail</button>';

                    // ACTION USER
                    var actionBtn = '';

                    // HANYA USER BIASA
                    if ("<?= $admin ?>" != 'Y') {

                        // Tiket OPEN
                        if (data[i].tikStatus == 'N') {

                            // tiket milik sendiri
                            if (data[i].tikUserID == "<?= $userId ?>") {

                                // operator & tindakan SUDAH diisi admin
                                if (
                                    data[i].tikOperator &&
                                    data[i].tikOperator.trim() != '' &&
                                    data[i].tikTindakan &&
                                    data[i].tikTindakan.trim() != ''
                                ) {

                                    actionBtn =
                                        '<button class="btn btn-success btn-sm statusBtn" data-id="' +
                                        data[i].tikNomor +
                                        '">OPEN</button>';

                                } else {

                                    actionBtn =
                                        '<button class="btn btn-warning btn-sm" disabled>Menunggu Admin</button>';
                                }

                            } else {

                                actionBtn =
                                    '<button class="btn btn-secondary btn-sm" disabled>Bukan Tiket Anda</button>';
                            }

                        } else {

                            actionBtn =
                                '<button class="btn btn-secondary btn-sm" disabled>CLOSED</button>';
                        }
                    }

                    // ADMIN
                    if ("<?= $admin ?>" == 'Y') {

                        table.row.add([
                            data[i].tikNomor,
                            tanggal_benar,
                            data[i].tikUserID,
                            data[i].tikRingkasan,
                            statusBtn,
                            detailBtn
                        ]);

                    }

                    // USER
                    else {

                        table.row.add([
                            data[i].tikNomor,
                            tanggal_benar,
                            data[i].tikUserID,
                            data[i].tikRingkasan,
                            statusBtn,
                            detailBtn,
                            actionBtn
                        ]);

                    }

                }

                table.draw();

            }
        });
    }

    $(document).on("click", ".detailBtn", function() { // Event listener untuk tombol detail

        var tikNomor = $(this).data("id"); // mengambil data-id dari tombol yang diklik, yaitu tikNomor

        loadDetailTik(tikNomor); // ambil detail tiket

    });

    $(document).on("click", ".statusBtn", function() {

        var tikNomor = $(this).data("id");

        // ambil data tiket untuk validasi sebelum close
        $.ajax({
            url: "<?= base_url('Select/detail_tiket_sim/') ?>" + tikNomor,
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                let operator = data.tikOperator;
                let tindakan = data.tikTindakan;

                // validasi operator dan tindakan sebelum close tiket
                if (!operator || operator === "") {
                    alert("❌ Tidak bisa menutup tiket!\nOperator belum dipilih.");
                    return;
                }

                if (!tindakan || tindakan.trim() === "") {
                    alert("❌ Tidak bisa menutup tiket!\nTindakan masih kosong.");
                    return;
                }

                // konfirmasi sebelum close tiket
                if (confirm("Apakah Anda yakin ingin menutup tiket ini?")) {

                    $.ajax({
                        url: "<?= base_url('Insert/close_tiket/') ?>" + tikNomor,
                        type: "POST",
                        success: function() {

                            alert("Tiket berhasil ditutup!");
                            getTiket_SIM();

                        }
                    });

                }

            }
        });

    });

    function simpanTindakan() {

        var operator = $('#tikOperator').val();
        var tindakan = $('#tikTindakan').val();
        var id = $('#m_tikNomor').text(); // ambil ID tiket

        // Validasi input tindakan
        if (operator == '') { // Jika operator belum dipilih
            alert('Operator wajib dipilih!');
            return;
        }

        if (tindakan.trim() == '') { // Jika tindakan masih kosong atau hanya berisi spasi
            alert('Tindakan wajib diisi!');
            return;
        }

        // Kirim data tindakan ke Controller untuk disimpan ke database
        $.ajax({
            url: "<?= base_url('Insert/simpan_tindakan') ?>",
            method: "POST",
            data: {
                tikID: id,
                tikOperator: operator,
                tikTindakan: tindakan
            },
            success: function(res) {
                alert('Data Berhasil disimpan!');
                $('#modalDetail').modal('hide');
                getTiket_SIM(); // untuk refresh
            }
        });
    }

    function konfirmasiSimpan() {
        if (confirm("Apakah Anda yakin ingin menyimpan tindakan ini?")) {
            simpanTindakan();
        }
    }

    function loadOperator(selected = '', callback = null) { // Function untuk load data operator ke select option, dengan parameter selected untuk menandai option mana yang dipilih, dan callback untuk menjalankan fungsi setelah load selesai

        $.ajax({
            url: "<?= base_url('Select/get_operator_sim') ?>",
            method: "GET",
            dataType: "JSON",
            success: function(data) {

                let select = $('#tikOperator');

                select.empty();

                select.append('<option value="">-- Pilih Operator --</option>');

                $.each(data, function(i, op) {

                    let selectedAttr = (op.pegNoreg == selected) ? 'selected' : '';

                    select.append(
                        '<option value="' + op.pegNoreg + '" ' + selectedAttr + '>' +
                        op.pegNama +
                        '</option>'
                    );

                });

                // jalankan callback setelah selesai load
                if (callback) {
                    callback();
                }
            }
        });

    }

    function loadDetailTik(tikNomor) {

        $.ajax({
            url: "<?= base_url('Select/detail_tiket_sim/') ?>" + tikNomor,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                console.log("STATUS ASLI =", data.tikStatus);
                console.log("TYPE =", typeof data.tikStatus);

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

                $("#m_tikStatus").html( // Jika status tiket 'OPEN' maka tampilkan badge hijau (Open), jika tidak tampilkan badge abu-abu (CLOSED)
                    data.tikStatus === 'OPEN' ?
                    '<span class="badge badge-success">OPEN</span>' :
                    '<span class="badge badge-secondary">CLOSED</span>'
                );

                $('#m_tikDeskripsi').html(data.tikDeskripsi);

                // =======================
                // ADMIN
                // =======================
                if ("<?= $admin ?>" == 'Y') {

                    // Load operator & tindakan
                    loadOperator(data.tikOperator);

                    $('#tikTindakan').val(data.tikTindakan ?? '');

                    // Jika tiket masih OPEN
                    if (data.tikStatus == 'OPEN') { // Jika status tiket OPEN, maka tampilkan tombol OPEN, jika tidak tampilkan tombol CLOSED

                        $('#tikOperator').prop('disabled', false);

                        $('#tikTindakan').prop('readonly', false);

                        $('#btnSimpan').show();

                    }

                    // Jika tiket sudah CLOSED
                    else {

                        $('#tikOperator').prop('disabled', true);

                        $('#tikTindakan').prop('readonly', true);

                        $('#btnSimpan').hide();

                    }

                }
                // =======================
                // USER
                // =======================
                else {

                    loadOperator(data.tikOperator);

                    $('#tikOperator').prop('disabled', true);

                    $('#tikTindakan').prop('readonly', true);

                    $('#btnSimpan').hide();

                    $('#tikTindakan').val(data.tikTindakan ?? '');

                }

            }
        });

    }
</script>
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog" style="max-width:75%; width:75%; margin:40px auto;">
        <div class="modal-content" style="border-radius:15px;">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title text-center w-100" style="font-size:26px;">
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
                            <div id="m_tikStatus" class="d-flex align-items-center font-weight-bold"
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

                    <!-- Operator -->
                    <div class="form-group row align-items-center mb-4">
                        <label class="col-md-3 font-weight-bold text-md-right pr-4">
                            Operator
                        </label>

                        <div class="col-md-9">
                            <select name="tikOperator" id="tikOperator"
                                class="form-control"
                                style="height:55px;font-size:20px;">
                                <option value="">-- Pilih Operator --</option>
                            </select>

                        </div>
                    </div>
                    <!-- Tindakan -->
                    <div class="form-group row">
                        <label class="col-md-3 font-weight-bold text-md-right pr-4 pt-3">
                            Tindakan <span class="text-danger">*</span>
                        </label>

                        <div class="col-md-9">
                            <textarea name="tikTindakan" id="tikTindakan"
                                class="form-control"
                                style="font-size:20px;padding:20px;min-height:200px;"
                                placeholder="Masukkan tindakan yang ingin dilakukan"
                                required></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FOOTER BUTTON -->
            <div class="modal-footer px-4 py-3 d-flex justify-content-between">
                <!-- Tutup-->
                <button type="button"
                    class="btn btn-danger"
                    style="font-size:20px; padding:10px 40px;"
                    data-dismiss="modal">
                    Tutup
                </button>

                <!-- Simpan -->
                <button type="button"
                    id="btnSimpan"
                    class="btn btn-success"
                    style="font-size:20px; padding:10px 40px;"
                    onclick="konfirmasiSimpan()">
                    Simpan
                </button>

            </div>
        </div>
    </div>
</div>

</body>

</html>