<?php
    session_start();
    // Jika sudah login, lempar ke index.php
    if(isset($_SESSION['login']) && $_SESSION['login'] == "yes"){
        header("location:index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>LOGIN | SYSTEM MANAGEMENT IT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <link rel="shortcut icon" href="assets/images/logo/logo.png">
        
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/app.css" rel="stylesheet" type="text/css" id="light-style">
        <link rel="stylesheet" href="assets/css/toast.css">
    </head>
    <body class="loading authentication-bg" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
        <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-lg-5">
                        <div class="card">
                            <div class="card-header pt-2 pb-2 text-center bg-dark">
                                <a href="#">
                                    <span><img src="assets/images/logo/logo_it.png" alt="" height="100"></span>
                                </a>
                            </div>

                            <div class="card-body p-4">                                 
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center pb-0 fw-bold">SIGN IN</h4>
                                </div>

                                <form action="functions/login_check.php" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input class="form-control" name="username" type="text" placeholder="Enter Username" required>
                                    </div>

                                    <div class="mb-3">                                         
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group input-group-merge">     
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 mb-0 text-center">
                                        <button class="btn btn-dark w-100" type="submit"> LOGIN </button>
                                    </div>

                                    <div class="text-center mt-3">
                                        <p class="text-muted mb-0" style="font-size: 12px;">Version 1.0.2</p>
                                    </div>
                                </form>
                            </div> </div> </div> </div> </div> </div>

        <footer class="footer footer-alt">
            2025 POWERED BY IT © MPI
        </footer>

        <script src="assets/js/vendor.min.js"></script>
        <script src="assets/js/app.min.js"></script>
        <script src="assets/js/vendor/toast.js"></script>

        <?php 
            if (isset($_SESSION['message']) && $_SESSION['message'] == 'logfail') { 
                echo "
                    <script>
                        $.toast({
                            heading: 'Login Gagal!',
                            text: 'Username / Password Salah!!',
                            position: 'top-right',
                            hideAfter: 3500,
                            textAlign: 'center',
                            icon: 'error'
                        });
                    </script>
                ";
                unset($_SESSION['message']);
            }
        ?>
    </body>