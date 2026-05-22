<?php
$admin   = $this->session->userdata('admin');
$sim     = $this->session->userdata('sim');
$sarpras = $this->session->userdata('sarpras');
?>

<?php
if (
    $admin == 'Y' ||
    $sim == 'Y' ||
    ($sim != 'Y' && $sarpras != 'Y')
) {
?>
<?php } ?>

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

            <div class="mb-4">
                <h2 class="font-weight-bold text-dark">
                    Halo, <?= $nama; ?> 👋
                </h2>
                <h5 class="text-primary">
                    <?= $role; ?>
                </h5>
            </div>

            <div class="row">

                <!-- TOTAL SIM -->
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2"
                        onclick="window.location='<?= base_url('tiket_sim') ?>'"
                        style="cursor:pointer; border-left:4px solid #007bff;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="h6 font-weight-bold text-uppercase mb-1"
                                        style="color:#007bff;">
                                        Tiket SIM
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"
                                        id="tiket_sim_total">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIM OPEN -->
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2"
                        onclick="window.location='<?= base_url('tiket_sim?status=open') ?>'"
                        style="cursor:pointer; border-left:4px solid #28a745;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="h6 font-weight-bold text-uppercase mb-1"
                                        style="color:#28a745;">
                                        Tiket SIM OPEN
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"
                                        id="tiket_sim_open">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-folder-open fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIM CLOSE -->
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2"
                        onclick="window.location='<?= base_url('tiket_sim?status=close') ?>'"
                        style="border-left:4px solid #8B4513;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="h6 font-weight-bold text-uppercase mb-1"
                                        style="color:#8B4513;">
                                        Tiket SIM CLOSE
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"
                                        id="tiket_sim_close">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row">

                <!-- TOTAL SARPRAS -->
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2"
                        onclick="window.location='<?= base_url('tiket_sarpras') ?>'"
                        style="cursor:pointer; border-left:4px solid #007bff;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="h6 font-weight-bold text-uppercase mb-1"
                                        style="color:#007bff;">
                                        Tiket Sarpras
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"
                                        id="tiket_sarpras_total">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tools fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SARPRAS OPEN -->
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2"
                        onclick="window.location='<?= base_url('tiket_sarpras?status=open') ?>'"
                        style="border-left:4px solid #28a745;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="h6 font-weight-bold text-uppercase mb-1"
                                        style="color:#28a745;">
                                        Sarpras OPEN
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"
                                        id="tiket_sarpras_open">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-folder-open fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SARPRAS CLOSE -->
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2"
                        onclick="window.location='<?= base_url('tiket_sarpras?status=close') ?>'"
                        style="border-left:4px solid #8B4513;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="h6 font-weight-bold text-uppercase mb-1"
                                        style="color:#8B4513;">
                                        Sarpras CLOSE
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"
                                        id="tiket_sarpras_close">
                                        0
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>© <?= date("Y"); ?> PT Kariban.</span>
            </div>
        </div>
    </footer>

</div>
<div class="alert alert-info">

    User ID :
    <?= $this->session->userdata('helpdesk_userId') ?>

    <br>

    Nama :
    <?= $this->session->userdata('helpdesk_userNama') ?>

    <br>

    Admin :
    <?= $this->session->userdata('admin') ?>

    <br>

    SIM :
    <?= $this->session->userdata('sim') ?>

    <br>

    Sarpras :
    <?= $this->session->userdata('sarpras') ?>

</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Script Section -->
<script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('js/sb-admin-2.js') ?>"></script>

<script>
    $(document).ready(function() {
        loadDashboard();
    });

    function loadDashboard() {
        $.ajax({
            url: "<?= base_url('Select/dashboard') ?>",
            method: "POST",
            dataType: "JSON",
            success: function(data) {

                $('#tiket_sim_total').text(data.tiket_sim_total);
                $('#tiket_sim_open').text(data.tiket_sim_open);
                $('#tiket_sim_close').text(data.tiket_sim_close);

                $('#tiket_sarpras_total').text(data.tiket_sarpras_total);
                $('#tiket_sarpras_open').text(data.tiket_sarpras_open);
                $('#tiket_sarpras_close').text(data.tiket_sarpras_close);

            }
        });
    }
</script>