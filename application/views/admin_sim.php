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
                <h1 class="h3 mb-0 text-gray-800">Halaman Admin SIM</h1>
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
        $('#admin').trigger('onclick');
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
                    // Membuat tanggal format Indonesia
                    var tanggal_db = new Date(data[i].tikTanggal); // Mengubah string tanggal dari database menjadi objek Date

                    var options = { // Opsi untuk format tanggal Indonesia
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    var tanggal_benar = tanggal_db.toLocaleDateString('id-ID', options); // Mengubah tanggal menjadi format Indonesia 
                    table.row.add([
                        data[i].tikNomor,
                        tanggal_benar,
                        data[i].tikUserID,
                        data[i].tikRingkasan,
                        data[i].tikStatus == 'N' ? // jika tikStatus = N, maka tampilkan tombol OPEN, jika tidak tampilkan tombol CLOSED
                        '<button class="btn btn-success btn-sm statusBtn" data-id="' + data[i].tikNomor + '">OPEN</button>' :
                        '<button class="btn btn-secondary btn-sm" disabled>CLOSED</button>',

                        '<button class="btn btn-primary btn-sm detailBtn" data-id="' + data[i].tikNomor + '" data-toggle="modal" data-target="#modalDetail">Detail</button>'
                    ]);
                }

                table.draw();
            }
        });
    }

    $(document).on("click", ".detailBtn", function() { // Event listener untuk tombol detail

        var tikNomor = $(this).data("id"); // mengambil data-id dari tombol yang diklik, yaitu tikNomor

        loadDetailTik(tikNomor); // ambil detail tiket

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
            url: "<?= base_url('Controller/simpan_tindakan') ?>",
            method: "POST",
            data: {
                tikID: id,
                tikOperator: operator,
                tikTindakan: tindakan
            },
            success: function(res) {
                alert('Data Berhasil disimpan!');
                $('#modalDetail').modal('hide');
                getAdmin_SIM(); // untuk refresh
            }
        });
    }

    function konfirmasiSimpan() {
        if (confirm("Apakah Anda yakin ingin menyimpan tindakan ini?")) {
            simpanTindakan();
        }
    }

    function loadOperator(selected = '') {

        $.ajax({
            url: "<?= base_url('Select/get_operator_sim') ?>",
            method: "GET",
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                let select = $('#tikOperator');
                select.empty();
                select.append('<option value="">-- Pilih Operator --</option>');

                $.each(data, function(i, op) {
                    let selectedAttr = (op.pegNoreg == selected) ? 'selected' : '';
                    select.append(
                        '<option value="' + op.pegNoreg + '" ' + selectedAttr + '>' + op.pegNama + '</option>'
                    );
                });

            }
        });

    }

    function loadDetailTik(tikNomor) {

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
                    data.tikStatus === 'OPEN' ?
                    '<span class="badge badge-success">OPEN</span>' :
                    '<span class="badge badge-secondary">CLOSED</span>'
                );

                $('#m_tikDeskripsi').html(data.tikDeskripsi);

                // Menampilkan operator dan tindakan di modal
                $('#m_tikOperator').html(data.tikOperator);
                $('#m_tikTindakan').html(data.tikTindakan);

                // Muat dropdown operator dengan opsi dari database, dan set nilai yang dipilih sesuai data tiket
                loadOperator(data.tikOperator);
                $('#tikOperator').val(data.tikOperator);
                $('#tikTindakan').val(data.tikTindakan);

                // Jika status tiket "OPEN"
                if (data.tikStatus == 'OPEN') {
                    $('#tikOperator').prop('readonly', false); // operator bisa diubah jika tiket masih OPEN
                    $('#tikTindakan').prop('readonly', false); // tindakan bisa diubah jika tiket masih OPEN
                    $('#btnSimpan').show(); // tampilkan tombol simpan jika tiket masih OPEN
                } else {
                    $('#tikOperator').prop('readonly', true); // operator tidak bisa diubah jika tiket sudah CLOSED
                    $('#tikTindakan').prop('readonly', true); // tindakan tidak bisa diubah jika tiket sudah CLOSED
                    $('#btnSimpan').hide(); // sembunyikan tombol simpan jika tiket sudah CLOSED
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
                            <div id="m_tikStatus" class="form-control d-flex align-items-center"
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