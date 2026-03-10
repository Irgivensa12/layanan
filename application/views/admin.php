<?php
defined('BASEPATH') or exit('No direct script access allowed');

//if (!isset($_SESSION["id_crm"])) { //jika tidak ada id
//    $this->session->set_flashdata('type', 'alert-danger');
//    $this->session->set_flashdata('pesan', '<strong>Error!</strong> Anda harus login terlebih dahulu');
//    redirect();
//    exit;
//}

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
                <div class="row">
                    <div class="col">

                        <table id="tabel" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>User</th>
                                    <th>Ringkasan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody></tbody>

                        </table>

                    </div>
                </div>
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
        </div>
        <div class="row">
            <div class="col">
                <div class="mb-4">
                    <div class="mb-4">
                        <p>Fitur ini masih dalam pengembangan, silakan hubungi Penpen dan Chandra untuk informasi lebih lanjut :)</p>
                    </div>
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
    <script type="text/javascript" src="<?= base_url('vendor/xlsx/xlsx.fix.js') ?>">
        var table;

        $(document).ready(function() {

            table = $('#tb_tiket').DataTable({
                "scrollX": true,
                "bSort": false
            });

            getTiket();
        });


        function getTiket() {

            $.ajax({
                url: "<?= base_url('Select/tiket_sim') ?>",
                method: "POST",
                dataType: "JSON",

                success: function(data) {

                    table.clear();

                    for (var i = 0; i < data.length; i++) {

                        var statusBtn;

                        if (data[i].tikStatus == 'Y') {
                            statusBtn = '<button class="btn btn-warning btn-sm openBtn" data-id="' + data[i].tikNomor + '">OPEN</button>';
                        } else {
                            statusBtn = '<button class="btn btn-success btn-sm closeBtn" data-id="' + data[i].tikNomor + '">CLOSE</button>';
                        }

                        table.row.add([
                            data[i].tikNomor,
                            data[i].tikTanggal,
                            data[i].tikUserID,
                            data[i].tikRingkasan,
                            data[i].tikStatus,
                            statusBtn
                        ]);

                    }

                    table.draw();

                }

            });

        }

        $(document).on("click", ".closeBtn", function() {

            var tikNomor = $(this).data("id");

            $.ajax({

                url: "<?= base_url('Controller/update_status') ?>",
                method: "POST",

                data: {
                    tikNomor: tikNomor,
                    status: 'Y'
                },

                success: function() {
                    getTiket();
                }

            });

        });


        $(document).on("click", ".openBtn", function() {

            var tikNomor = $(this).data("id");

            $.ajax({

                url: "<?= base_url('Controller/update_status') ?>",
                method: "POST",

                data: {
                    tikNomor: tikNomor,
                    status: 'N'
                },

                success: function() {
                    getTiket();
                }

            });

        });
    </script>

    <script>
        // gunakan Javascript dan jQuery
        // bisa cek untuk contoh membuat form: https://getbootstrap.com/docs/4.0/components/forms/

        // var table;

        // $(document).ready(function() {

        //     $('#menuMenu').trigger('click');
        //     table = $('#tabel').DataTable({ //https://datatables.net/reference/option/
        //         "scrollX": true,
        //         "select": true,
        //         "bSort": false
        //     });
        //     getTiket_Sarpras(); // Memanggil function get
        // });
    </script>


    </body>

    </html>