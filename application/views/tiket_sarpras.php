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

<style id="stylePrintSarpras">
    /* Style global (font, ukuran, warna, garis) */
    .sarpras-form {
        width: 100%;
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: black;
        line-height: 1.5;
    }

    /* WRAPPER 2 KOLOM */
    .print-wrapper {
        display: flex;
        gap: 20px;
        align-items: stretch;
    }

    /* KOLOM KIRI & KANAN */
    .print-col {
        width: 50%;
        padding: 20px;
        border: 1px solid black;
        /* kotak tipis muncul */
        box-sizing: border-box;
        display: flex;
    }

    /* FIELD */
    .sarpras-row {
        display: flex;
        align-items: center;
        /* biar sejajar isi form kanan dan kiri */
        margin-bottom: 8px;
    }

    .sarpras-title {
        font-size: 18px;
        font-weight: 700;
        text-align: center;
    }

    .sarpras-subtitle {
        text-align: center;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .sarpras-label {
        width: 130px;
        font-weight: bold;
    }

    /* GARIS INPUT */
    .sarpras-line {
        flex: 1;
        border-bottom: 1px solid black;
        min-height: 20px;
        padding-left: 5px;
        /* Jarak Teks */
    }

    /* BOX */
    .sarpras-box {
        border: 1px solid black;
        min-height: 90px;
        padding: 10px 12px;
        /* agar teks tidak nempel kiri */
        margin-top: 5px;
        margin-bottom: 12px;
    }

    /* TTD */
    .sarpras-ttd {
        display: flex;
        justify-content: space-between;
        text-align: center;
        font-size: 12px;
        margin-top: 15px;
    }

    .sarpras-form hr {
        border: none;
        border-top: 2px dashed black;
        margin: 15px 0;
    }

    /* Rencana Tindak Lanjut */
    .sarpras-rtl {
        margin-top: 15px;
    }

    .sarpras-rtl-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .caKaki {
        margin-top: 10px;
        font-size: 12px;
        font-weight: bold;
    }

    .modal-content {
        position: relative;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        width: 35px;
        height: 35px;
        font-size: 22px;
        font-weight: bold;

        color: black;
        background: transparent;
        border: none;
        border-radius: 50%;

        cursor: pointer;
        z-index: 999;

        display: flex;
        align-items: center;
        justify-content: center;

        transition: all 0.2s ease;
    }

    .close:hover {
        background: #e81123;
        /* merah khas tombol close */
        color: white;
    }

    /* PRINT */
    @media print {
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        html,
        body {
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }

        .print-wrapper {
            width: 297mm;
            height: 200mm;
            /* jangan 210, kasih margin aman */
            display: flex;
            gap: 10px;
            margin: auto;
        }

        .print-col {
            width: 50%;
            height: 100%;
            padding: 12px;
            border: 1px solid black;
            box-sizing: border-box;
            overflow: hidden;
        }

        .sarpras-form {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        button,
        .modal-header,
        .close {
            display: none !important;
        }

        .close-btn {
            display: none !important;
        }

        .modal,
        .modal-dialog,
        .modal-content {
            all: unset;
        }
    }
</style>

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
                <h1 class="h3 mb-0 text-gray-800">Keluhan SARPRAS</h1>
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
                                <th>User ID</th>
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

<!-- library html2pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

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

    // Menggunakan AJAX untuk membuat tabel dari data tabel
    // AJAX (View) -> Controller -> Model -> dapat hasil
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

                    table.row.add([
                        data[i].layID,
                        tanggal_benar,
                        data[i].layUserID,
                        data[i].userNama,
                        data[i].layHal,
                        data[i].layDiskripsi,
                        data[i].layStatus == 'N' ?
                        '<button class="btn btn-success btn-sm statusBtn" data-id="' + data[i].layID + '">OPEN</button>' :
                        '<button class="btn btn-secondary btn-sm" disabled>CLOSED</button>',
                        '<button class="btn btn-primary btn-sm detailBtn" data-id="' +
                        data[i].layID +
                        '" data-toggle="modal" data-target="#modalDetail">Detail</button>';
                    ]);
                    
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

    $(document).on("click", ".statusBtn", function() {

        var nomor = $(this).data("id");

        // ambil detail dulu untuk cek petugas
        $.ajax({
            url: "<?= base_url('Select/detail_tiket_sarpras/') ?>" + nomor,
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                let petugas = data.petugas || [];

                // filter petugas yang tidak kosong
                let petugasValid = petugas.filter(p => p && p !== "");

                // jika belum ada petugas
                if (petugasValid.length === 0) {
                    alert("❌ Tidak bisa menutup layanan keluhan!\nSilakan pilih penanggung jawab terlebih dahulu.");
                    return;
                }

                // kalau sudah ada petugas
                if (confirm("Apakah Anda yakin ingin menutup keluhan ini?")) {

                    $.ajax({
                        url: "<?= base_url('Insert/close_sarpras/') ?>" + nomor,
                        type: "POST",
                        success: function() {

                            alert("Tiket berhasil ditutup!");
                            getTiket_Sarpras();
                        }
                    });

                }
            }
        });

    });

    $(document).on("click", ".detailBtn", function() { // function untuk menampilkan isi detail layanan

        var layID = $(this).data("id");

        loadPetugas(() => {
            loadDetailLay(layID);
        });

    });
    $(document).on("click", ".formSarprasBtn", function() { // function untuk menampilkan isi form sarpras dari database

        var layID = $(this).data("id");
        loadFormSarpras(layID); // Mengambil modal form sarpras

    });

    function simpanPetugas() { // Function untuk menyimpan petugas yang dipilih di modal detail

        var petugas = []; // Array untuk menyimpan petugas yang dipilih

        $('select[name="petugas[]"]').each(function() { // loop untuk setiap select petugas
            if ($(this).val() != '') { // jika petugas dipilih (tidak kosong), simpan ke array
                petugas.push($(this).val());
            }
        });

        $.ajax({
            url: "<?= base_url('Insert/simpan_petugas') ?>",
            method: "POST", // kirim data menggunakan POST karena mengirim data yang lebih kompleks (array petugas)
            data: {
                layID: $('#m_layID').text(), // ambil ID layanan dari modal
                petugas: petugas // kirim array petugas yang dipilih ke controller
            },
            success: function(res) { // jika berhasil, tampilkan alert dan tutup modal
                alert("Pilihan Petugas Berhasil disimpan!");
                $('#modalDetail').modal('hide');
            }
        });
    }

    function loadPetugas(callback) {

        $.ajax({
            url: "<?= base_url('Select/get_petugas') ?>",
            method: "GET",
            dataType: "JSON",
            success: function(data) {

                $('select[name="petugas[]"]').each(function() {

                    var select = $(this);
                    select.empty();
                    select.append('<option value="">-- Pilih Petugas --</option>');

                    $.each(data, function(i, p) {
                        select.append(
                            '<option value="' + p.pegNoreg + '">' + p.pegNama + '</option>'
                        );
                    });

                });

                if (callback) callback(); // panggil callback setelah data petugas berhasil dimuat
            }
        });
    }

    function preventDuplicatePetugas() {

        let selectedValues = [];

        // ambil semua value yang dipilih
        $('select[name="petugas[]"]').each(function() {
            let val = $(this).val();
            if (val !== "") {
                selectedValues.push(val);
            }
        });

        // reset dulu semua option
        $('select[name="petugas[]"] option').prop('disabled', false);

        // disable yang sudah dipilih di dropdown lain
        $('select[name="petugas[]"]').each(function() {

            let currentSelect = $(this);
            let currentVal = currentSelect.val();

            currentSelect.find('option').each(function() {
                let optionVal = $(this).val();

                if (selectedValues.includes(optionVal) && optionVal !== currentVal) {
                    $(this).prop('disabled', true);
                }
            });

        });
    }

    $(document).on('change', 'select[name="petugas[]"]', function() {
        preventDuplicatePetugas();
    });

    function loadDetailLay(layID) {

        $.ajax({
            url: "<?= base_url('Select/detail_tiket_sarpras/') ?>" + layID,
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

                $("#m_layStatus").html(

                    data.layStatus === 'OPEN'

                    ?

                    '<span class="badge badge-success">OPEN</span>'

                    :

                    '<span class="badge badge-secondary">CLOSED</span>'

                );

                $('#m_layDiskripsi').html(data.layDiskripsi);

                $("#m_penanggungJawab").html(
                    data.penanggungJawab ?
                    data.penanggungJawab :
                    '<span class="text-muted">Belum ditentukan</span>'
                );

            }

        });
    }

    konfirmasiSimpan = () => {
        if (confirm("Apakah Anda yakin ingin menyimpan perubahan ini?")) {
            simpanPetugas();
        }
    }

    function loadFormSarpras(layID) {

        $.ajax({
            url: "<?= base_url('Select/form_sarpras/') ?>" + layID,
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

                var raw = data.layTarget.split(' ')[0]; // ambil bagian tanggal saja (sebelum spasi)
                var tanggal_db = new Date(raw);

                var tgl_targetIdn = tanggal_db.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                $("#m_kepada, #m_kepada2").text(data.penanggungJawab);
                $("#m_dari, #m_dari2").text(data.userNama);
                $("#m_tanggal, #m_tanggal2").text(tanggal_benar);
                $("#m_hal, #m_hal2").text(data.layHal);
                $("#m_isi, #m_isi2").text(data.layDiskripsi);
                $("#m_target, #m_target2").text(tgl_targetIdn);
                $("#m_pic, #m_pic2").text(data.penanggungJawab);
            }
        });
    }

    function printFormSarpras() {
        // ambil elemen modal form sarpras yang sudah diisi datanya
        var element = document.querySelector("#modalFormSarpras .print-wrapper").cloneNode(true);

        // hapus tombol
        element.querySelectorAll("button").forEach(btn => btn.remove());

        // hapus style modal bootstrap
        element.querySelectorAll('.modal-content').forEach(el => {
            el.style.boxShadow = 'none';
            el.style.border = 'none';
        });

        var no_layanan = document.getElementById("m_layID").textContent.trim();
        var opt = {
            margin: 0.3,
            filename: 'SPU-ADM-36 FORM PERMOHONAN LAYANAN SARPRAS - ' + no_layanan + '.pdf',
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'landscape' // gunakan landscape untuk kertas A4 horizontal
            }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>

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

                <!-- Tanggal -->
                <div class="mb-4">
                    <label class="font-weight-bold">Tanggal</label>
                    <div id="m_layTanggal" class="form-control"
                        style="width:250px; font-size:22px; height:50px;">
                    </div>
                </div>

                <!-- User -->
                <div class="mb-4">
                    <label class="font-weight-bold">Penginput</label>
                    <div id="m_userNama" class="form-control"
                        style="font-size:22px; height:50px;">
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="font-weight-bold">Status</label>
                    <div id="m_layStatus" class="d-flex align-items-center font-weight-bold"
                        style="font-size:28px;">
                    </div>
                </div>

                <hr>

                <!-- Ringkasan -->
                <div class="mb-4">
                    <label class="font-weight-bold">Ringkasan</label>
                    <div id="m_layHal" class="form-control"
                        style="min-height:120px; font-size:20px; padding:15px;">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="font-weight-bold">Deskripsi Keluhan</label>
                    <div id="m_layDiskripsi" class="form-control"
                        style="min-height:200px; font-size:20px; padding:15px;">
                    </div>
                </div>

                <hr>

                <!-- PENANGGUNG JAWAB -->
                <div class="mb-4">
                    <h5 class="text-primary font-weight-bold mb-3">
                        Penanggung Jawab
                    </h5>

                    <div class="form-group">
                        <label>Petugas 1</label>
                        <select name="petugas[]" class="form-control" style="height:50px; font-size:20px;">
                            <option value="">-- Pilih Petugas --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Petugas 2</label>
                        <select name="petugas[]" class="form-control" style="height:50px; font-size:20px;">
                            <option value="">-- Pilih Petugas --</option>
                        </select>
                    </div>

                    <div class=" form-group">
                        <label>Petugas 3</label>
                        <select name="petugas[]" class="form-control" style="height:50px; font-size:20px;">
                            <option value="">-- Pilih Petugas --</option>
                        </select>
                    </div>

                    <div class=" form-group">
                        <label>Petugas 4</label>
                        <select name="petugas[]" class="form-control" style="height:50px; font-size:20px;">
                            <option value="">-- Pilih Petugas --</option>
                        </select>
                    </div>

                    <div class=" form-group">
                        <label>Petugas 5</label>
                        <select name="petugas[]" class="form-control" style="height:50px; font-size:20px;">
                            <option value="">-- Pilih Petugas --</option>
                        </select>
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

    </div>

</div>

<div class="modal fade" id="modalFormSarpras" tabindex="-1">
    <div class="modal-dialog" style="max-width:1000px;">
        <div class="modal-content">

            <!-- tombol close -->
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <!-- BODY -->
            <div class="modal-body">

                <div class="print-wrapper">

                    <!-- KOLOM KIRI -->
                    <div class="print-col">
                        <div class="sarpras-form">

                            <div class="sarpras-title">
                                FORM PERMOHONAN LAYANAN SARPRAS
                            </div>

                            <div class="sarpras-subtitle">
                                No. Dok. KNS/FORM/SPU/ADM/36/01/00
                            </div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">Kepada</div>
                                <div class="sarpras-line" id="m_kepada"></div>
                            </div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">Dari</div>
                                <div class="sarpras-line" id="m_dari"></div>
                            </div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">Tanggal</div>
                                <div class="sarpras-line" id="m_tanggal"></div>
                            </div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">Hal</div>
                                <div class="sarpras-line" id="m_hal"></div>
                            </div>

                            <b>I. Isi Permohonan</b>
                            <div class="sarpras-box" id="m_isi"></div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">Target</div>
                                <div class="sarpras-line" id="m_target"></div>
                            </div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">PIC</div>
                                <div class="sarpras-line" id="m_pic"></div>
                            </div>

                            <!-- TTD -->
                            <div class="sarpras-ttd">
                                <div>Pemohon<br><br>(Kanit)</div>
                                <div>Diketahui<br><br>(Kabag)</div>
                                <div>Disetujui<br><br>(Manajer)</div>
                                <div>Diterima<br><br>(Sarpras)</div>
                            </div>

                            <hr> <!-- Garis Pemisah -->

                            <!-- Rencana Tindak Lanjut -->
                            <div class="sarpras-rtl">
                                <b>II. Rencana Tindak Lanjut</b>

                                <div>Petugas : ____________</div>

                                <div class="sarpras-rtl-row">
                                    <div>Observasi : Sudah / Belum*</div>
                                    <div>Tgl. Observasi : ____________</div>
                                </div>

                                <div class="sarpras-rtl-row">
                                    <div>Status Eksekusi: Sudah / Belum*</div>
                                    <div>Tgl. Eksekusi : ____________</div>
                                </div>

                                <div>
                                    Catatan: **
                                    <div class="sarpras-box" style="height:60px;"></div>
                                </div>
                                <div class="caKaki">
                                    <div>(*) Coret yang tidak perlu</div>
                                    <div>(**) Diisi oleh petugas setelah eksekusi</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- KOLOM KANAN (DUPLIKAT) -->
                    <div class="print-col">
                        <!-- DUPLIKAT SAMA PERSIS -->
                        <div class="sarpras-form">

                            <div class="sarpras-title">
                                FORM PERMOHONAN LAYANAN SARPRAS
                            </div>

                            <div class="sarpras-subtitle">
                                No. Dok. KNS/FORM/SPU/ADM/36/01/00
                            </div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">Kepada</div>
                                <div class="sarpras-line" id="m_kepada2"></div>
                            </div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">Dari</div>
                                <div class="sarpras-line" id="m_dari2"></div>
                            </div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">Tanggal</div>
                                <div class="sarpras-line" id="m_tanggal2"></div>
                            </div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">Hal</div>
                                <div class="sarpras-line" id="m_hal"></div>
                            </div>

                            <b>I. Isi Permohonan</b>
                            <div class="sarpras-box" id="m_isi"></div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">Target</div>
                                <div class="sarpras-line" id="m_target2"></div>
                            </div>

                            <div class="sarpras-row">
                                <div class="sarpras-label">PIC</div>
                                <div class="sarpras-line" id="m_pic2"></div>
                            </div>

                            <!-- TTD -->
                            <div class="sarpras-ttd">
                                <div>Pemohon<br><br>(Kanit)</div>
                                <div>Diketahui<br><br>(Kabag)</div>
                                <div>Disetujui<br><br>(Manajer)</div>
                                <div>Diterima<br><br>(Sarpras)</div>
                            </div>

                            <hr> <!-- Garis Pemisah -->

                            <!-- Rencana Tindak Lanjut -->
                            <div class="sarpras-rtl">
                                <b>II. Rencana Tindak Lanjut</b>

                                <div>Petugas : ____________</div>

                                <div class="sarpras-rtl-row">
                                    <div>Observasi : Sudah / Belum*</div>
                                    <div>Tgl. Observasi : ____________</div>
                                </div>

                                <div class="sarpras-rtl-row">
                                    <div>Status Eksekusi : Sudah / Belum*</div>
                                    <div>Tgl. Eksekusi : ____________</div>
                                </div>

                                <div>
                                    Catatan: **
                                    <div class="sarpras-box" style="height:60px;"></div>
                                </div>
                                <div class="caKaki">
                                    <div>(*) Coret yang tidak perlu</div>
                                    <div>(**) Diisi oleh petugas setelah eksekusi</div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="text-center mt-3">
                    <button onclick="printFormSarpras()" class="btn btn-dark">
                        Print / Save PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
    </body>

    </html>
