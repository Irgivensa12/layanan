<?php
defined('BASEPATH') OR exit('No direct script access allowed');


//if (isset($_SESSION["id"])) { //jika ada id
//    redirect('menu');
//    exit;
//}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Layanan Sarpras & SIM</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link rel="icon" href="img/icon.png">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-6 col-lg-6 col-md-6">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Login Akun</h1>
                                    </div>
                                    
                                    <?php if(isset($_SESSION["pesan"])){ ?>
                                    <div class="alert <?= $_SESSION['type']?> alert-dismissible">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?= $_SESSION['pesan']?>

                                    </div>
                                    <?php unset($_SESSION['pesan']); } ?>
                                    
                                    <form class="user" action="<?= base_url('Controller/login')?>" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="id" name="id" placeholder="ID Karyawan" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password" name ="password" placeholder="Password" required>
                                        </div>
                                        
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Login">
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small">Layanan Sarpras & SIM</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <script src="<?= base_url('js/sb-admin-2.js') ?>"></script>

</body>
</html>