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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="<?= base_url('css/sb-admin-2.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/modal-login.css') ?>">

    <!-- CSS jika diperlukan -->
    <style>
        textarea {
            resize: none;
        }

        .form-control {
            color: black !important;
            border-color: gray !important;
        }

        .form-group {
            color: black !important;
        }

        thead,
        tbody {
            color: black;
        }

        table,
        th,
        td {
            border: 1px solid gray !important;
            border-collapse: collapse !important;
        }

        /* Efek hover pada card  */
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

<div id="wrapper">

    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center">
            <div class="sidebar-brand-icon">
                <i class="fas fa-wrench"></i> <!-- cari ikon di: https://fontawesome.com/v5/search?q=laptop-medical&o=r -->
            </div>
            <div class="sidebar-brand-text mx-3">Layanan Sarpras SIM</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                data-target="#collapseUser"
                aria-expanded="true" aria-controls="collapseUser"
                id="menuUser">

                <i class="fas fa-fw fa-user"></i>
                <span>

                    <?php
                    $admin   = $this->session->userdata('admin');
                    $user    = $this->session->userdata('helpdesk_userId');

                    if ($admin == 'Y') {
                        $role = 'Admin';
                    } else {
                        $role = 'User';
                    }
                    ?>

                    Login sebagai <?= $role ?> (<?= $user ?>)

                </span>
            </a>

            <div id="collapseUser" class="collapse"
                data-parent="#accordionSidebar">

                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Menu:</h6>
                    <a class="collapse-item" href="<?= base_url('Controller/logout') ?>">
                        Logout
                    </a>

                </div>
            </div>
        </li>

        <hr class="sidebar-divider my-0">

        <!-- Dashboard -->
        <?php if ($this->session->userdata('admin') == 'Y') { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('dashboard') ?>">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        <?php } else { ?>
            <!-- Jika USER BIASA -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('dashboard') ?>">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        <?php } ?> 
        <!-- MENU TIKET -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                data-target="#collapseTiket"
                aria-expanded="true" aria-controls="collapseTiket">

                <i class="fas fa-fw fa-ticket-alt"></i>
                <span>Menu Tiket</span>
            </a>

            <div id="collapseTiket" class="collapse"
                data-parent="#accordionSidebar">

                <div class="bg-white py-2 collapse-inner rounded">

                    <a class="collapse-item" href="<?= base_url('tiket_sim') ?>">
                        Tiket SIM
                    </a>

                    <a class="collapse-item" href="<?= base_url('tiket_sarpras') ?>">
                        Tiket Sarpras
                    </a>

                </div>
            </div>
        </li>


        <?php
        $admin   = $this->session->userdata('admin');
        $sarpras = $this->session->userdata('sarpras');

        if ($admin == 'Y' || $sarpras == 'Y') {
        ?>

            <!-- MENU LAPORAN -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                    data-target="#collapseLaporan"
                    aria-expanded="true" aria-controls="collapseLaporan">

                    <i class="fas fa-fw fa-file"></i>
                    <span>Laporan</span>
                </a>

                <div id="collapseLaporan" class="collapse"
                    data-parent="#accordionSidebar">

                    <div class="bg-white py-2 collapse-inner rounded">

                        <a class="collapse-item" href="<?= base_url('laporan_sim') ?>">
                            Laporan SIM
                        </a>

                        <a class="collapse-item" href="<?= base_url('laporan_sarpras') ?>">
                            Laporan Sarpras
                        </a>

                    </div>
                </div>
            </li>

        <?php
        } else {
            // jika login sebagai USER biasa, menu laporan tidak ditampilkan
        }
        ?>

        
        <hr class="sidebar-divider">


        </li>
    </ul>