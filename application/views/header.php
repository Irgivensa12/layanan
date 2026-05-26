<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Layanan Sarpras & SIM</title>
    <link rel="icon" href="img/icon.png">

    <link href="<?= base_url('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="<?= base_url('css/sb-admin-2.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/modal-login.css') ?>">

    <style>
        textarea { resize: none; }
        .form-control { color: black !important; border-color: gray !important; }
        .form-group { color: black !important; }
        thead, tbody { color: black; }

        table, th, td {
            border: 1px solid gray !important;
            border-collapse: collapse !important;
        }

        .card:hover {
            transform: scale(1.03);
            transition: 0.2s;
            cursor: pointer;
        }

        .dt-button.btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: white !important;
            font-weight: bold;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .dt-button.btn-success:hover {
            background-color: #218838 !important;
        }
    </style>
</head>

<?php
$admin   = $this->session->userdata('admin');
$sim     = $this->session->userdata('sim');
$sarpras = $this->session->userdata('sarpras');
$user    = $this->session->userdata('helpdesk_userId');

$as_user_biasa = $this->session->userdata('as_user_biasa');

// ROLE
if ($as_user_biasa) {
    $role = 'User (Mode Admin)';
} elseif ($admin == 'Y') {
    $role = 'Admin Akses';
} elseif ($sim == 'Y') {
    $role = 'Admin SIM';
} elseif ($sarpras == 'Y') {
    $role = 'Admin Sarpras';
} else {
    $role = 'User';
}

$isUserMode = ($as_user_biasa == true);
?>

<div id="wrapper">

    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- BRAND -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center">
            <div class="sidebar-brand-icon">
                <i class="fas fa-wrench"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Layanan Sarpras SIM</div>
        </a>

        <hr class="sidebar-divider my-0">

        <!-- USER PANEL -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                data-target="#collapseUser">

                <i class="fas fa-fw fa-user"></i>
                <span>Login sebagai <?= $role ?> (<?= $user ?>)</span>
            </a>

            <div id="collapseUser" class="collapse" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">

                    <a class="collapse-item" href="<?= base_url('Controller/logout') ?>">
                        Logout
                    </a>

                    <?php if ($admin == 'Y' && !$isUserMode) { ?>
                        <a class="collapse-item" href="<?= base_url('Controller/switchToUser') ?>">
                            Login sebagai User Biasa
                        </a>
                    <?php } ?>

                    <?php if ($isUserMode) { ?>
                        <a class="collapse-item" href="<?= base_url('Controller/backToAdmin') ?>">
                            Kembali ke Admin
                        </a>
                    <?php } ?>

                </div>
            </div>
        </li>

        <hr class="sidebar-divider my-0">

        <!-- DASHBOARD -->
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('dashboard') ?>">
                <i class="fas fa-fw fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- TIKET -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                data-target="#collapseTiket">

                <i class="fas fa-fw fa-ticket-alt"></i>
                <span>Menu Tiket</span>
            </a>

            <div id="collapseTiket" class="collapse" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">

                    <?php if (!$isUserMode) { ?>

                        <?php if ($admin == 'Y' || $sim == 'Y' || $sarpras == 'Y') { ?>
                            <a class="collapse-item" href="<?= base_url('tiket_sim') ?>">Tiket SIM</a>
                            <a class="collapse-item" href="<?= base_url('tiket_sarpras') ?>">Tiket Sarpras</a>
                        <?php } ?>

                    <?php } else { ?>

                        <!-- USER MODE -->
                        <a class="collapse-item" href="<?= base_url('tiket_saya_sim') ?>">
                            Tiket SIM Saya
                        </a>

                        <a class="collapse-item" href="<?= base_url('tiket_saya_sarpras') ?>">
                            Tiket Sarpras Saya
                        </a>

                    <?php } ?>

                </div>
            </div>
        </li>

        <!-- MENU USER (ADMIN ONLY) -->
        <?php if ($admin == 'Y' && !$isUserMode) { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('menu_user') ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Menu User</span>
                </a>
            </li>
        <?php } ?>

        <!-- LAPORAN -->
        <?php if (($sim == 'Y' || $sarpras == 'Y') && !$isUserMode) { ?>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                    data-target="#collapseLaporan">

                    <i class="fas fa-fw fa-file"></i>
                    <span>Laporan</span>
                </a>

                <div id="collapseLaporan" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">

                        <?php if ($sim == 'Y') { ?>
                            <a class="collapse-item" href="<?= base_url('laporan_sim') ?>">Laporan SIM</a>
                        <?php } ?>

                        <?php if ($sarpras == 'Y') { ?>
                            <a class="collapse-item" href="<?= base_url('laporan_sarpras') ?>">Laporan Sarpras</a>
                        <?php } ?>

                    </div>
                </div>
            </li>

        <?php } ?>

        <hr class="sidebar-divider">

    </ul>