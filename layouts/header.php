<?php
session_start();
// NOT LOGGED IN
if ($_SESSION['login'] != "yes") {
    header("location:login.php");
    exit();
}

$role = $_SESSION['role'];
$current_page = basename($_SERVER['PHP_SELF']);

// --- LOGIKA REDIRECT OTOMATIS (Mencegah Akses Dashboard manual) ---
if ($current_page == "index.php") {
    if ($role == 2) {
        header("location:report_komputer.php");
        exit();
    } elseif ($role == 3) {
        header("location:data_peminjaman_it.php");
        exit();
    }
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
    <link rel="shortcut icon" href="assets/images/logo/logo.png">

    <link href="assets/css/vendor/fullcalendar.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="assets/css/vendor/responsive.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="assets/css/vendor/buttons.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="assets/css/vendor/select.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/app.css" rel="stylesheet" type="text/css" id="light-style">
    <link rel="stylesheet" href="assets/css/toast.css">
</head>

<body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <div class="wrapper">
        <div class="leftside-menu">

            <a href="index.php" class="logo text-center logo-light">
                <span class="logo-lg">
                    <img src="assets/images/logo/logo_it.png" alt="" height="55">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo/logo_it.png" alt="" height="18">
                </span>
            </a>

            <div class="h-100" id="leftside-menu-container" data-simplebar="">
                <ul class="side-nav">
                    
                    <?php
                    // --- MENU KHUSUS ROLE 1 (ADMIN) ---
                    if ($role == 1) {
                        echo '<li class="side-nav-title side-nav-item">Navigation</li>';
                        echo '
                        <li class="side-nav-item">
                            <a href="index.php" class="side-nav-link">
                                <i class="uil-home-alt"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>';

                        echo '<li class="side-nav-title side-nav-item">Apps</li>';
                        echo '<li class="side-nav-item"><a href="data_user.php" class="side-nav-link"><i class="uil-user"></i>User</a></li>';

                        // Komputer
                        echo '
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarKomputer" class="side-nav-link">
                                <i class="mdi mdi-desktop-tower-monitor"></i>
                                <span> Komputer </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarKomputer">
                                <ul class="side-nav-third-level">
                                    <li><a href="data_komputer.php">Data Komputer</a></li>
                                    <li><a href="data_perbaikan.php">Data Perbaikan</a></li>
                                    <li><a href="report_komputer.php">Report Komputer</a></li>
                                </ul>
                            </div>
                        </li>';

                        // Printer
                        echo '
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarPrinter" class="side-nav-link">
                                <i class="mdi mdi-printer"></i>
                                <span> Printer </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarPrinter">
                                <ul class="side-nav-third-level">
                                    <li><a href="data_printer.php">Data Printer</a></li>
                                    <li><a href="data_catrige.php">Data Catrige</a></li>
                                    <li><a href="form_catrige.php">Form Pengambilan</a></li>
                                    <li><a href="report_printer.php">Report Printer</a></li>
                                </ul>
                            </div>
                        </li>';

                        // Peminjaman
                        echo '
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarPeminjamanIT" class="side-nav-link">
                                <i class="mdi mdi-clipboard-list-outline"></i>
                                <span> Peminjaman IT </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarPeminjamanIT">
                                <ul class="side-nav-third-level">
                                    <li><a href="data_peminjaman_it.php">Data Peminjaman</a></li>
                                    <li><a href="report_peminjaman_it.php">Report Peminjaman</a></li>
                                </ul>
                            </div>
                        </li>';

                        echo '<li class="side-nav-item"><a href="data_ip.php" class="side-nav-link"><i class="mdi mdi-web"></i>IP Address</a></li>';
                    } 
                    
                    // --- MENU KHUSUS ROLE 2 (LANGSUNG MENU REPORT) ---
                    elseif ($role == 2) {
                        echo '<li class="side-nav-title side-nav-item">Main Menu</li>';
                        echo '
                        <li class="side-nav-item">
                            <a href="report_komputer.php" class="side-nav-link">
                                <i class="mdi mdi-file-document-outline"></i>
                                <span> Report Komputer </span>
                            </a>
                        </li>';
                    }

                    // --- MENU KHUSUS ROLE 3 (LANGSUNG MENU PEMINJAMAN) ---
                    elseif ($role == 3) {
                        echo '<li class="side-nav-title side-nav-item">Main Menu</li>';
                        echo '
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarPeminjamanIT" class="side-nav-link">
                                <i class="mdi mdi-clipboard-list-outline"></i>
                                <span> Peminjaman IT </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarPeminjamanIT">
                                <ul class="side-nav-third-level">
                                    <li><a href="data_peminjaman_it.php">Data Peminjaman</a></li>
                                    <li><a href="report_peminjaman_it.php">Report Peminjaman</a></li>
                                </ul>
                            </div>
                        </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="content-page">
            <div class="content">
                <div class="navbar-custom">
                    <ul class="list-unstyled topbar-menu float-end mb-0">
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button">
                                <span class="account-user-avatar">
                                    <img src="assets/images/users/avatar_default.jpg" alt="user-image" class="rounded-circle">
                                </span>
                                <span>
                                    <span class="account-user-name"><?= $_SESSION['username']; ?></span>
                                    <span class="account-position">
                                        <?php 
                                            if($role == 1) echo "Super Admin";
                                            elseif($role == 2) echo "User";
                                            elseif($role == 3) echo "Petugas Peminjaman";
                                        ?>
                                    </span>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome !</h6>
                                </div>
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