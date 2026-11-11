<?php
require_once __DIR__ . '/../functions/auth.php';
enforcePageAccess();              // sudah termasuk requireLogin()
$role = currentRole();
$current_page = basename($_SERVER['PHP_SELF']);

// --- LOGIKA REDIRECT OTOMATIS ---
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

    <link rel="shortcut icon" href="assets/images/logo/logo.png">
    <link href="assets/css/vendor/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="assets/css/vendor/responsive.bootstrap5.css" rel="stylesheet">
    <link href="assets/css/icons.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet" id="light-style">
</head>

<body class="loading"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false,"leftSidebarCondensed":false,"leftSidebarScrollable":false,"darkMode":false}'>
    <div class="wrapper">

        <!-- ================= SIDEBAR ================= -->
        <div class="leftside-menu">

            <a href="index.php" class="logo text-center logo-light">
                <span class="logo-lg">
                    <img src="assets/images/logo/logo_it.png" height="55">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo/logo_it.png" height="18">
                </span>
            </a>

            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <ul class="side-nav">

                    <?php
                    /* ================= ROLE 1 : ADMIN ================= */
                    if ($role == 1) {

                        echo '<li class="side-nav-title">Navigation</li>';
                        echo '
<li class="side-nav-item">
    <a href="index.php" class="side-nav-link">
        <i class="uil-home-alt"></i> Dashboard
    </a>
</li>';

                        echo '<li class="side-nav-title">Master Data</li>';

                        /* USER */
                        echo '
<li class="side-nav-item">
    <a href="data_user.php" class="side-nav-link">
        <i class="uil-user"></i> User
    </a>
</li>';

                        /* === DEVICE IT === */
                        echo '
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarDeviceIT" class="side-nav-link">
        <i class="mdi mdi-laptop"></i>
        <span> Device IT </span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="sidebarDeviceIT">
        <ul class="side-nav-third-level">
            <li><a href="data_barang_it.php">Data Device</a></li>
            <li><a href="report_barang_it.php">Report Device</a></li>
        </ul>
    </div>
</li>';

                        /* === KOMPUTER === */
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

                        /* === PRINTER === */
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

                        /* === FINGERPRINT (BARU) === */
                        echo '
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarFingerprint" class="side-nav-link">
        <i class="mdi mdi-fingerprint"></i>
        <span> Fingerprint </span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="sidebarFingerprint">
        <ul class="side-nav-third-level">
            <li><a href="data_fingerprint.php">Data Fingerprint</a></li>
            <li><a href="mapping_fingerprint.php">Mapping Fingerprint</a></li>
            <li><a href="report_fingerprint.php">Report Fingerprint</a></li>
        </ul>
    </div>
</li>';

                        /* === PEMINJAMAN IT === */
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

                        echo '
<li class="side-nav-item">
    <a href="data_ip.php" class="side-nav-link">
        <i class="mdi mdi-web"></i> IP Address
    </a>
</li>';
                    }

                    /* ================= ROLE 2 : USER ================= */ elseif ($role == 2) {
                        echo '
<li class="side-nav-title">Main Menu</li>
<li class="side-nav-item">
    <a href="report_komputer.php" class="side-nav-link">
        <i class="mdi mdi-file-document-outline"></i>
        Report Komputer
    </a>
</li>';
                    }

                    /* ================= ROLE 3 : PETUGAS ================= */ elseif ($role == 3) {
                        echo '
<li class="side-nav-title">Main Menu</li>

<li class="side-nav-item">
    <a href="data_barang_it.php" class="side-nav-link">
        <i class="mdi mdi-laptop"></i> Device IT
    </a>
</li>

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
                    <li class="side-nav-item mt-3">
                        <a href="<?= BASE_URL ?>functions/logout.php" class="side-nav-link text-danger">
                            <i class="mdi mdi-logout"></i>
                            <span> Logout </span>
                        </a>
                    </li>

                </ul>

                </ul>
            </div>
        </div>

        <!-- ================= CONTENT ================= -->
        <div class="content-page">
            <div class="content">