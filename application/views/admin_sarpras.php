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
                        data[i].layID + '" data-toggle="modal" data-target="#modalDetail">Detail</button>'
                    ]);
                }

                table.draw();
            }
        });
    }

    $(document).on("click", ".detailBtn", function() { // Event listener untuk tombol detail

        var tikNomor = $(this).data("id");

        $.ajax({
            url: "<?= base_url('Select/detail_tiket_sarpras/') ?>" + tikNomor,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                // Format tanggal Indonesia
                var tanggal_db = new Date(data.layTanggal);

                var options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                var tanggal_benar = tanggal_db.toLocaleDateString('id-ID', options);

                // Format tanggal Indonesia
                var tanggal_db = new Date(data.layTanggal);

                var options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };

                var tanggal_benar = tanggal_db.toLocaleDateString('id-ID', options);

                // Menampilkan data ke dalam modal
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
            }
        });

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
</script>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width:700px;">
        <div class="modal-content" style="border-radius:15px;">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white justify-content-center">
                <h4 class="modal-title text-center w-100" style="font-size:26px;">
                    DATA LAYANAN SARPRAS
                </h4>
                <button type="button" class="close text-white position-absolute"
                    style="right:20px; font-size:30px;" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <!-- BODY (SCROLLABLE) -->
            <div class="modal-body"
                style="max-height:75vh; overflow-y:auto; padding:30px; font-size:20px;">

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
                            <option value="1">Petugas A</option>
                            <option value="2">Petugas B</option>
                            <option value="3">Petugas C</option>
                        </select>
                    </div>

                </div>
                <button class="btn btn-success" onclick="simpanPetugas()">Simpan</button>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer justify-content-center">
                <button type="button"
                    class="btn btn-danger"
                    style="font-size:20px; padding:12px 40px;"
                    data-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

</body>

</html>