<?php
    session_start();
    // NOT LOGGED IN
    if($_SESSION['login']!="yes"){
        header("location:login.php");
    }
?>
<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>SYSTEM MANAGEMENT IT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
        <meta content="Coderthemes" name="author">
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/logo/logo.png">

        <!-- third party css -->
        <link href="assets/css/vendor/fullcalendar.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css">
        <link href="assets/css/vendor/responsive.bootstrap5.css" rel="stylesheet" type="text/css">
        <link href="assets/css/vendor/buttons.bootstrap5.css" rel="stylesheet" type="text/css">
        <link href="assets/css/vendor/select.bootstrap5.css" rel="stylesheet" type="text/css">
        <link href="assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css">
        <!-- third party css end -->

        <!-- App css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/app.css" rel="stylesheet" type="text/css" id="light-style">
        <link rel="stylesheet" href="assets/css/toast.css">

    </head>

    <body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
        <!-- Begin page -->
        <div class="wrapper">
            <!-- ========== Left Sidebar Start ========== -->
            <div class="leftside-menu">
    
                <!-- LOGO -->
                <a href="index.html" class="logo text-center logo-light">
                    <span class="logo-lg">
                        <img src="assets/images/logo/logo_it.png" alt="" height="55">
                    </span>
                    <span class="logo-sm">
                        <img src="assets/images/logo/logo_it.png" alt="" height="18">
                    </span>
                </a>                
                <div class="h-100" id="leftside-menu-container" data-simplebar="">    
                    <!--- Sidemenu -->
                    <ul class="side-nav">
                        <li class="side-nav-title side-nav-item">Navigation</li>
                        <li class="side-nav-item">
                            <a href="index.php" class="side-nav-link">
                                <i class="uil-home-alt"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>
                        <li class="side-nav-title side-nav-item">Apps</li>

                        <?php

                        $role = $_SESSION['role'];

                        $menuUser = '<a href="data_user.php" class="side-nav-link"><i class="uil-user"></i>User</a>';
                        $menuKomputer = '<a href="data_komputer.php">Data Komputer</a>';
                        $menuMonitor = '<a href="data_monitor.php">Data Monitor</a>';
                        $reportKomputer = '<a href="report_komputer.php">Report</a>';
                        $menuPrinter = '<a href="data_printer.php">Data Printer</a>';
                        $menuCatrige = '<a href="data_catrige.php">Data Catrige</a>';
                        $menuForm = '<a href="form_catrige.php">Form Pengambilan</a>';
                        $reportPrinter = '<a href="report_printer.php">Report</a>';
                        $menuIp = '<a href="data_ip.php" class="side-nav-link"><i class="mdi mdi-web"></i>IP Address</a>';

                        if ($role == 1){
                            echo    '<li class="side-nav-item">
                                        '.$menuUser.'
                                    </li>';
                            
                            echo    '<li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarKomputer" aria-expanded="false" aria-controls="sidebarSpearpart" class="side-nav-link">
                                            <i class="mdi mdi-desktop-tower-monitor"></i>
                                            <span> Komputer </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarKomputer">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    '.$menuKomputer.'
                                                </li>
                                                <li>
                                                    '.$menuMonitor.'
                                                </li>
                                                <li>
                                                    '.$reportKomputer.'
                                                </li>
                                            </ul>
                                        </div>                                        
                                    </li>';

                            echo    '<li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarPrinter" aria-expanded="false" aria-controls="sidebarSpearpart" class="side-nav-link">
                                            <i class="mdi mdi-printer"></i>
                                            <span> Printer </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarPrinter">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    '.$menuPrinter.'
                                                </li>
                                                <li>
                                                    '.$menuCatrige.'
                                                </li>
                                                <li>
                                                    '.$menuForm.'
                                                </li>
                                                <li>
                                                    '.$reportPrinter.'
                                                </li>
                                            </ul>
                                        </div>
                                    </li>';

                            echo    '<li class="side-nav-item">
                                        '.$menuIp.'
                                    </li>';
                        } ?>                        
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarequipment" aria-expanded="false" aria-controls="sidebarSpearpart" class="side-nav-link">
                                <i class="mdi mdi-dip-switch"></i>
                                <span> Equipment </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarequipment">
                                <ul class="side-nav-third-level">
                                    <li>
                                        <a href="#">Switch dan Hub</a>
                                    </li>
                                    <li>
                                        <a href="#">Data IP Address</a>
                                    </li>
                                    <li>
                                        <a href="#">Report</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Sidebar -left -->
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">
                    <!-- Topbar Start -->
                    <div class="navbar-custom">
                        <ul class="list-unstyled topbar-menu float-end mb-0">
                            <li class="dropdown notification-list d-lg-none">
                                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="dripicons-search noti-icon"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                                    <form class="p-3">
                                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                    </form>
                                </div>
                            </li>
                            <!-- <li class="dropdown notification-list topbar-dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <img src="assets/images/flags/us.png" alt="user-image" class="me-0 me-sm-1" height="25"> 
                                    <span class="align-middle d-none d-sm-inline-block">English</span> <i class="mdi mdi-chevron-down d-none d-sm-inline-block align-middle"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu">

                                    <!-- item-->
                                    <!-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <img src="assets/images/flags/ind.png" alt="user-image" class="me-1" height="25"> <span class="align-middle">Indonesia</span>
                                    </a>
                                </div>
                            </li> -->
                            <li class="dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="dripicons-bell noti-icon"></i><span class="noti-icon-badge"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg">

                                    <!-- item-->
                                    <div class="dropdown-item noti-title">
                                        <h5 class="m-0">Notification</h5>
                                    </div>
                                </div>
                            </li>

                            <li class="dropdown notification-list">
                                <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <span class="account-user-avatar"> 
                                        <img src="assets/images/users/avatar_default.jpg" alt="user-image" class="rounded-circle">
                                    </span>
                                    <span>
                                        <span class="account-user-name"><?= $_SESSION['username']; ?></span>
                                        <span class="account-position">Founder</span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                                    <!-- item-->
                                    <div class=" dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Welcome !</h6>
                                    </div>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="mdi mdi-account-circle me-1"></i>
                                        <span>Profile</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="mdi mdi-account-edit me-1"></i>
                                        <span>Settings</span>
                                    </a>

                                    <!-- item-->
                                    <a href="functions/logout.php" class="dropdown-item notify-item">
                                        <i class="mdi mdi-logout me-1"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </li>

                        </ul>
                        <button class="button-menu-mobile open-left">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </div>
                    <!-- end Topbar -->