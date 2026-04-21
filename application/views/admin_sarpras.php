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
                                <th style="width:100px">Form Sarpras</th>
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

<!-- library html2pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

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
        getAdmin_Sarpras(); // Memanggil function get
    });

    // menggunakan AJAX untuk membuat tabel dari data tabel
    // AJAX (View) -> Controller -> Model -> dapat hasil
    function getAdmin_Sarpras() {

        $.ajax({
            url: "<?php echo base_url('Select/tiket_sarpras'); ?>",
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                //var html = '';
                table.clear();

                for (var i = 0; i < data.length; i++) {
                    // Membuat tanggal format Indonesia
                    var tanggal_db = new Date(data[i].layTanggal); // Mengubah string tanggal dari database menjadi objek Date

                    var options = { // Opsi untuk format tanggal Indonesia
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };

                    var tanggal_benar = tanggal_db.toLocaleDateString('id-ID', options); // Mengubah tanggal menjadi format Indonesia

                    // Membuat tanggal format Indonesia
                    var tanggal_db = new Date(data[i].layTanggal); // Mengubah string tanggal dari database menjadi objek Date

                    var options = { // Opsi untuk format tanggal Indonesia
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

    $(document).on("click", ".statusBtn", function() {

        var nomor = $(this).data("id");

        if (confirm("Apakah Anda yakin ingin menutup keluhan ini?")) {

            $.ajax({
                url: "<?= base_url('Controller/close_sarpras/') ?>" + nomor,
                type: "POST",
                success: function() {

                    alert("Tiket berhasil ditutup!");

                    getAdmin_Sarpras();
                }
            });
        }
    });

    $(document).on("click", ".detailBtn", function() { // function untuk menampilkan isi detail layanan

        var layID = $(this).data("id");

        loadPetugas(() => {
            loadDetailLay(layID);
        });

    });
    $(document).on("click", ".formSarprasBtn", function() { // function untuk menampilkan isi form sarpras dari database

        var layID = $(this).data("id");
        loadFormSarpras(layID); // ambil modal form sarpras

    });

    function simpanPetugas() { // function untuk menyimpan petugas yang dipilih di modal detail

        var petugas = []; // array untuk menyimpan petugas yang dipilih

        $('select[name="petugas[]"]').each(function() { // loop untuk setiap select petugas
            if ($(this).val() != '') { // jika petugas dipilih (tidak kosong), simpan ke array
                petugas.push($(this).val());
            }
        });

        $.ajax({
            url: "<?= base_url('Controller/simpan_petugas') ?>",
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

                if (callback) callback(); // 🔥 jalankan setelah selesai load
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
                    data.layStatus === 'OPEN' ?
                    '<span class="badge badge-success">OPEN</span>' :
                    '<span class="badge badge-secondary">CLOSED</span>'
                );

                $('#m_layDiskripsi').html(data.layDiskripsi);

                // ===============================
                // 🔥 BAGIAN PENTING (SET PETUGAS)
                // ===============================

                let petugasDB = data.petugas || []; // pastikan controller kirim array

                $('select[name="petugas[]"]').each(function(index) {

                    let select = $(this);

                    if (petugasDB[index]) {
                        select.val(petugasDB[index]);

                        // 🔒 lock hanya yang sudah dipilih
                        select.attr('disabled', true);
                        select.append('<input type="hidden" name="petugas[]" value="' + select.val() + '">');
                    } else {
                        select.val('');
                        select.prop('disabled', false);
                    }

                });

                preventDuplicatePetugas();

                // disable tombol simpan jika sudah ada petugas
                let jumlahPetugas = petugasDB.filter(p => p && p !== "").length;

                if (jumlahPetugas >= 3) {
                    $('#btnSimpan').hide();
                } else {
                    $('#btnSimpan').show();
                }

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

                $("#m_kepada").text(data.penanggungJawab);
                $("#m_dari").text(data.userNama);
                $("#m_tanggal").text(tanggal_benar);
                $("#m_hal").text(data.layHal);
                $("#m_isi").text(data.layDiskripsi);
                $("#m_target").text(data.layTarget);
                $("#m_pic").text(data.penanggungJawab);
            }
        });
    }

    function printFormSarpras() {

        // clone modal biar aman
        var element = document.querySelector("#modalFormSarpras .modal-content").cloneNode(true);

        // menghapus tombol print
        element.querySelectorAll("button").forEach(btn => btn.remove());

        // menghilangkan background abu-abu
        element.querySelectorAll("*").forEach(el => {
            el.style.background = "white";
            el.style.color = "black";
        });

        element.querySelectorAll('.modal-content').forEach(el => {
            el.style.boxShadow = 'none';
            el.style.border = 'none';
        });

        // Mengatur opsi untuk html2pdf
        var opt = {
            margin: 0.5,
            filename: 'SPU-ADM-36 FORM PERMOHONAN LAYANAN SARPRAS',
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'a4',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save();
    }
</script>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog" style="max-width:75%; width:75%; margin:40px auto;">
        <div class="modal-content" style="border-radius:15px;">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white justify-content-center">
                <h4 class="modal-title text-center w-100" style="font-size:30px;">
                    DATA LAYANAN SARPRAS
                </h4>
                <button type="button" class="close text-white position-absolute"
                    style="right:20px; font-size:30px;" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <!-- BODY (SCROLLABLE) -->
            <div class="modal-body"
                style="max-height:75vh; overflow-y:auto; padding:40px; font-size:22px;">

                <!-- Nomor Tiket -->
                <div class="mb-4">
                    <label class="font-weight-bold">Nomor Tiket</label>
                    <div id="m_layID" class="form-control text-center"
                        style="width:200px; font-size:22px; height:50px;">
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
                    <label class="font-weight-bold">User</label>
                    <div id="m_userNama" class="form-control"
                        style="font-size:22px; height:50px;">
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="font-weight-bold">Status</label>
                    <div id="m_layStatus" class="form-control text-center font-weight-bold"
                        style="width:150px; font-size:22px; height:50px;">
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
    <div class="modal-dialog" style="max-width:800px; margin:30px auto;">
        <div class="modal-content" style="border-radius:10px;">

            <!-- HEADER -->
            <div class="modal-header" style="border-bottom:3px solid black;">
                <h4 class="modal-title text-center w-100"
                    style="font-size:28px; font-weight:800; color:black;">
                    FORM PERMOHONAN LAYANAN SARPRAS
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- BODY -->
            <div class="modal-body" style="font-size:20px; padding:35px; line-height:1.8; color:black; font-weight:500;">

                <!-- NO DOK -->
                <div class="text-center mb-4" style="font-size:18px; font-weight:700;">
                    No. Dok. KNS/FORM/SPU/ADM/36/01/00
                </div>

                <!-- KEPADA -->
                <div class="d-flex mb-3">
                    <div style="width:150px; font-weight:700;">Kepada</div>
                    <div style="width:20px;">:</div>
                    <div id="m_kepada" style="border-bottom:2px solid black; width:100%; min-height:30px;"></div>
                </div>

                <!-- DARI -->
                <div class="d-flex mb-3">
                    <div style="width:150px; font-weight:700;">Dari</div>
                    <div style="width:20px;">:</div>
                    <div id="m_dari" style="border-bottom:2px solid black; width:100%; min-height:30px;"></div>
                </div>

                <!-- TANGGAL -->
                <div class="d-flex mb-3">
                    <div style="width:150px; font-weight:700;">Tanggal</div>
                    <div style="width:20px;">:</div>
                    <div id="m_tanggal" style="border-bottom:2px solid black; width:100%; min-height:30px;"></div>
                </div>

                <!-- HAL -->
                <div class="d-flex mb-4">
                    <div style="width:150px; font-weight:700;">Hal</div>
                    <div style="width:20px;">:</div>
                    <div id="m_hal" style="border-bottom:2px solid black; width:100%; min-height:30px;"></div>
                </div>

                <!-- ISI -->
                <div style="font-weight:700; margin-bottom:10px;">
                    I. Isi Permohonan <i>(diisi oleh pemohon)</i>
                </div>

                <div id="m_isi" style="border:2px solid black; padding:15px; min-height:140px; margin-bottom:20px;"></div>

                <!-- TARGET -->
                <div class="d-flex mb-3">
                    <div style="width:150px; font-weight:700;">Target Waktu</div>
                    <div style="width:20px;">:</div>
                    <div id="m_target" style="border-bottom:2px solid black; width:60%; min-height:30px;"></div>
                </div>

                <!-- PIC -->
                <div class="d-flex mb-5">
                    <div style="width:150px; font-weight:700;">PIC</div>
                    <div style="width:20px;">:</div>
                    <div id="m_pic" style="border-bottom:2px solid black; width:60%; min-height:30px;"></div>
                </div>

                <!-- TTD -->
                <div class="text-center" style="margin-top:50px;">
                    <div class="row text-center" style="font-size:18px; font-weight:700;">
                        <div class="col-3">Pemohon</div>
                        <div class="col-3">Diketahui</div>
                        <div class="col-3">Disetujui</div>
                        <div class="col-3">Diterima</div>
                    </div>

                    <div class="row text-center" style="margin-top:70px; font-size:18px;">
                        <div class="col-3">(Kanit)</div>
                        <div class="col-3">(Kabag)</div>
                        <div class="col-3">(Kadep/Manajer)</div>
                        <div class="col-3">(Sarpras)</div>
                    </div>
                </div>

                <div style="page-break-before: always;">

                    <hr style="border-top:2px solid black; margin:30px 0;">

                    <!-- RENCANA TINDAK LANJUT-->
                    <h4 style="font-size:26px; font-weight:800;">
                        II. Rencana Tindak Lanjut <i>(diisi oleh Sarpras)</i>
                    </h4>

                    <!-- Petugas -->
                    <div style="display:flex; align-items:center; margin-bottom:15px;">
                        <div style="width:200px; font-size:20px; font-weight:700;">
                            Petugas
                        </div>
                        <div class="col-md-9">
                            <input type="text" readonly
                                style="width:100%; height:45px; border:2px solid black; font-size:18px;">
                        </div>
                    </div>

                    <!-- Observasi -->
                    <div style="display:flex; align-items:center; margin-bottom:15px;">
                        <div style="width:200px; font-size:20px; font-weight:700;">
                            Observasi
                        </div>
                        <div style="width:200px;">
                            <input type="text" value=" Sudah / Belum*" readonly
                                style="width:100%; height:45px; border:2px solid black; font-size:18px;">
                        </div>
                        <div style="width:180px; text-align:right; padding-right:10px; font-size:20px;">
                            Tgl. Observasi
                        </div>

                        <div style="width:200px;">
                            <input type="text" readonly
                                style="width:100%; height:45px; border:2px solid black;">
                        </div>
                    </div>
                    <!-- Status Eksekusi -->
                    <div style="display:flex; align-items:center; margin-bottom:15px;">
                        <div style="width:200px; font-size:20px; font-weight:700;">
                            Status Eksekusi
                        </div>
                        <div style="width:200px;">
                            <input type="text" value=" Sudah / Belum*" readonly
                                style="width:100%; height:45px; border:2px solid black; font-size:18px;">
                        </div>
                        <div style="width:180px; text-align:right; padding-right:10px; font-size:20px;">
                            Tgl. Eksekusi
                        </div>

                        <div style="width:200px;">
                            <input type="text" readonly
                                style="width:100%; height:45px; border:2px solid black;">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div style="display:flex; align-items:center; margin-bottom:15px;">
                        <div style="width:200px; font-size:20px; font-weight:700;">
                            Status Eksekusi
                        </div>
                        <div class="col-md-9">
                            <textarea readonly
                                style="width:100%; min-height:120px; border:2px solid black; font-size:20px; background:#f5f5f5;"></textarea>
                        </div>
                    </div>

                    <!-- PRINT BUTTON -->
                    <div class="text-center mt-4">
                        <button onclick="printFormSarpras()"
                            style="background:black; color:white; font-size:18px; padding:10px 30px; border:none;">
                            Print / Save PDF
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </body>

    </html>