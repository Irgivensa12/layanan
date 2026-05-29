<?php
if (
    !$this->session->userdata('helpdesk_userId')
) {
    redirect();
    exit;
}

$admin   = $this->session->userdata('admin');

if ($admin != 'Y') {
    redirect('dashboard');
    exit;
}
?>

<div id="content-wrapper" class="d-flex flex-column">

    <div id="content">

        <!-- TOPBAR -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <button id="sidebarToggleTop"
                class="btn btn-link d-md-none rounded-circle mr-3">

                <i class="fa fa-bars"></i>

            </button>

            <ul class="navbar-nav ml-auto">
                <img src="<?= base_url('img/kanisius.png') ?>">
            </ul>

        </nav>

        <!-- CONTENT -->
        <div class="container-fluid">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">

                <h1 class="h3 mb-0 text-gray-800">
                    Menu Hak Akses User
                </h1>

            </div>

            <!-- ALERT -->
            <?php if (isset($_SESSION['pesan'])) { ?>

                <div class="alert <?= $_SESSION['type'] ?> alert-dismissible fade show">

                    <?= $_SESSION['pesan'] ?>

                    <button type="button"
                        class="close"
                        data-dismiss="alert">

                        &times;

                    </button>

                </div>

            <?php unset($_SESSION['pesan']);
            } ?>

            <!-- TABLE -->
            <div class="card shadow mb-4">

                <div class="card-header py-3">

                    <h6 class="m-0 font-weight-bold text-primary">
                        Data User
                    </h6>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabel"
                            class="table table-bordered"
                            width="100%">

                            <thead class="bg-primary text-white">

                                <tr>
                                    <th>No</th>
                                    <th>User ID</th>
                                    <th>Nama User</th>
                                    <th>SIM</th>
                                    <th>Sarpras</th>
                                    <th>Edit Hak Akses</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                $no = 1;

                                foreach ($user as $u) {
                                ?>

                                    <tr>

                                        <td><?= $no++ ?></td>

                                        <td><?= $u->userId ?></td>

                                        <td><?= $u->userNama ?></td>

                                        <td>
                                            <?php if ($u->Tiket == 'Y') { ?>

                                                <span class="badge badge-success">
                                                    Y
                                                </span>

                                            <?php } else { ?>

                                                <span class="badge badge-secondary">
                                                    N
                                                </span>

                                            <?php } ?>
                                        </td>

                                        <td>

                                            <?php if ($u->Sarpras == 'Y') { ?>

                                                <span class="badge badge-success">
                                                    Y
                                                </span>

                                            <?php } else { ?>

                                                <span class="badge badge-secondary">
                                                    N
                                                </span>

                                            <?php } ?>

                                        </td>

                                        <td>

                                            <button
                                                class="btn btn-warning btn-sm btnEdit"

                                                data-userid="<?= $u->userId ?>"
                                                data-usernama="<?= $u->userNama ?>"

                                                data-tiket="<?= $u->Tiket ?>"
                                                data-sarpras="<?= $u->Sarpras ?>"
                                                data-admin="<?= $u->Admin ?>">

                                                <i class="fas fa-edit"></i>
                                                Edit

                                            </button>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- FOOTER -->
    <footer class="sticky-footer bg-white">

        <div class="container my-auto">

            <div class="copyright text-center my-auto">

                <span>
                    © <?= date("Y"); ?> PT Kanisius.
                </span>

            </div>

        </div>

    </footer>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalEdit">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                id="formHakAkses"
                action="<?= base_url('Insert/updateHakAkses') ?>"
                method="POST">

                <!-- HEADER -->
                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">
                        Edit Hak Akses
                    </h5>

                    <button type="button"
                        class="close text-white"
                        data-dismiss="modal">

                        &times;

                    </button>

                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="form-group">

                        <label>User ID</label>

                        <input type="text"
                            name="userId"
                            id="userId"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="form-group">

                        <label>Nama User</label>

                        <input type="text"
                            id="userNama"
                            class="form-control"
                            readonly>

                    </div>

                    <hr>

                    <div class="form-group">

                        <label>
                            <b>Pilih Hak Akses</b>
                        </label>

                        <!-- USER BIASA -->
                        <div class="mt-2">

                            <input type="radio"
                                name="role"
                                value="user"
                                id="role_user">

                            <label for="role_user">
                                User 
                            </label>

                        </div>

                        <!-- ADMIN SIM -->
                        <div>

                            <input type="radio"
                                name="role"
                                value="sim"
                                id="role_sim">

                            <label for="role_sim">
                                Admin SIM
                            </label>

                        </div>

                        <!-- ADMIN SARPRAS -->
                        <div>

                            <input type="radio"
                                name="role"
                                value="sarpras"
                                id="role_sarpras">

                            <label for="role_sarpras">
                                Admin Sarpras
                            </label>

                        </div>

                    </div>
                </div>

        <!-- FOOTER -->
        <div class="modal-footer">

            <button type="submit"
                class="btn btn-success">

                Simpan

            </button>

            <button type="button"
                class="btn btn-secondary"
                data-dismiss="modal">

                Batal

            </button>

        </div>

        </form>

    </div>

</div>

</div>

<!-- JS -->
<script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>

<script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

<script src="<?= base_url('js/sb-admin-2.js') ?>"></script>

<link rel="stylesheet"
    href="<?= base_url('vendor/datatables/jquery.dataTables.min.css') ?>">

<script src="<?= base_url('vendor/datatables/jquery.dataTables.min.js') ?>"></script>

<script>
    $(document).ready(function() {

        // DATATABLE
        $('#tabel').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100]
        });

        // TOMBOL EDIT
        $(document).on('click', '.btnEdit', function() {

            let userId = $(this).data('userid');
            let userNama = $(this).data('usernama');

            $('#userId').val(userId);
            $('#userNama').val(userNama);

            // RESET RADIO
            $('input[name="role"]').prop('checked', false);

            // CEK ROLE DARI DATABASE
            if ($(this).data('tiket') == 'Y') {

                $('#role_sim').prop('checked', true);

            } else if ($(this).data('sarpras') == 'Y') {

                $('#role_sarpras').prop('checked', true);

            } else {

                $('#role_user').prop('checked', true);

            }

            $('#modalEdit').modal('show');

        });

        // VALIDASI FORM
        $('#formHakAkses').submit(function(e) {

            let role = $('input[name="role"]:checked').val();

            if (role == 'user') {

                let konfirmasiUser = confirm(
                    'User akan dijadikan user biasa. Lanjutkan?'
                );

                if (!konfirmasiUser) {

                    e.preventDefault();
                    return false;

                }

            }

            let konfirmasi = confirm(
                'Apakah Anda yakin ingin menyimpan perubahan hak akses ini?'
            );

            if (!konfirmasi) {

                e.preventDefault();
                return false;

            }

        });

    });
</script>
</body>

</html>