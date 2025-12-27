<?php
// Buffer output untuk mencegah error header
ob_start();

// Error reporting untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load Layout Header
include_once 'layouts/header.php';

// Load File Fungsi
$files = [
    'functions/function_komputer.php',
    'functions/function_ip.php',
    'functions/function_printer.php',
    'functions/function_catrige.php',
    'functions/function_peminjaman_it.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        require_once $file;
    }
}

// --- AMBIL DATA ---
$totalKomputer      = function_exists('getTotalKomputer') ? getTotalKomputer() : 0;
$totalPerbaikan     = function_exists('getTotalPerbaikan') ? getTotalPerbaikan() : 0;
$totalIpTerpakai    = function_exists('getTotalIpTerpakai') ? getTotalIpTerpakai() : 0;
$totalPrinterAktif  = function_exists('getTotalPrinterAktif') ? getTotalPrinterAktif() : 0;

$dataCatridge       = function_exists('getAllCatrige') ? getAllCatrige() : [];
$peminjamanAktif    = function_exists('getPeminjamanAktif') ? getPeminjamanAktif() : [];
?>

<style>
    .dashboard-container {
        animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* Link Styling untuk Card */
    .card-link {
        text-decoration: none !important;
        display: block;
        color: inherit;
    }
    .stat-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border-radius: 15px;
        overflow: hidden;
        cursor: pointer;
    }
    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
    }
    .card-icon {
        font-size: 2.5rem;
        opacity: 0.2;
        position: absolute;
        right: 15px;
        bottom: 10px;
    }
    .badge-soft-warning {
        background-color: rgba(255, 152, 0, 0.15);
        color: #f57c00;
        padding: 5px 10px;
        border-radius: 6px;
    }
    .avatar-xs {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-white p-3 rounded shadow-sm">
                <div>
                    <h4 class="page-title mb-0 fw-bold text-primary">IT ASSET MANAGEMENT</h4>
                    <p class="text-muted mb-0">Overview status sistem dan aset terkini</p>
                </div>
                <div class="mt-2 mt-md-0">
                    <a href="index.php" class="btn btn-primary px-4 shadow-sm">
                        <i class="mdi mdi-refresh me-1"></i> Refresh Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-4">
            <a href="data_komputer.php" class="card-link">
                <div class="card stat-card shadow-sm border-0 border-start border-primary border-4 h-100">
                    <div class="card-body p-4 position-relative">
                        <h6 class="text-muted text-uppercase fw-semibold mt-0">Total Komputer</h6>
                        <h2 class="fw-bold mb-0 text-dark"><?= number_format($totalKomputer) ?> <span class="fs-6 fw-normal text-muted">Unit</span></h2>
                        <div class="card-icon text-primary"><i class="mdi mdi-laptop"></i></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <a href="data_perbaikan.php" class="card-link">
                <div class="card stat-card shadow-sm border-0 border-start border-warning border-4 h-100">
                    <div class="card-body p-4 position-relative">
                        <h6 class="text-muted text-uppercase fw-semibold mt-0">Perbaikan Komputer</h6>
                        <h2 class="fw-bold mb-0 text-dark"><?= number_format($totalPerbaikan) ?> <span class="fs-6 fw-normal text-muted">Perbaikan</span></h2>
                        <div class="card-icon text-warning"><i class="mdi mdi-tools"></i></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <a href="data_ip.php" class="card-link">
                <div class="card stat-card shadow-sm border-0 border-start border-info border-4 h-100">
                    <div class="card-body p-4 position-relative">
                        <h6 class="text-muted text-uppercase fw-semibold mt-0">IP Terpakai</h6>
                        <h2 class="fw-bold mb-0 text-dark"><?= number_format($totalIpTerpakai) ?> <span class="fs-6 fw-normal text-muted">Active</span></h2>
                        <div class="card-icon text-info"><i class="mdi mdi-access-point-network"></i></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <a href="data_printer.php" class="card-link">
                <div class="card stat-card shadow-sm border-0 border-start border-success border-4 h-100">
                    <div class="card-body p-4 position-relative">
                        <h6 class="text-muted text-uppercase fw-semibold mt-0">Printer Aktif</h6>
                        <h2 class="fw-bold mb-0 text-dark"><?= number_format($totalPrinterAktif) ?> <span class="fs-6 fw-normal text-muted">Ready</span></h2>
                        <div class="card-icon text-success"><i class="mdi mdi-printer"></i></div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="mdi mdi-water-outline me-2 text-danger"></i>Stok Catridge
                    </h5>
                    <a href="data_catrige.php" class="btn btn-sm btn-light text-primary fw-bold">Detail</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="ps-4">Printer</th>
                                    <th>Type</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($dataCatridge)): ?>
                                    <?php foreach (array_slice($dataCatridge, 0, 5) as $c) : ?>
                                    <tr>
                                        <td class="ps-4 fw-medium"><?= htmlspecialchars($c['nama_printer']) ?></td>
                                        <td><span class="badge bg-soft-primary text-primary"><?= htmlspecialchars($c['type_catrige']) ?></span></td>
                                        <td class="text-center">
                                            <span class="fw-bold text-<?= ($c['jml_catrige'] <= 2) ? 'danger' : 'dark' ?>"><?= $c['jml_catrige'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="data_catrige.php" class="btn btn-sm btn-outline-secondary p-1 px-2"><i class="mdi mdi-eye"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center py-4 text-muted">Stok aman / Tidak ada data</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="mdi mdi-hand-pointing-right me-2 text-primary"></i>Peminjaman Aktif
                    </h5>
                    <a href="data_peminjaman_it.php" class="btn btn-sm btn-light text-primary fw-bold">Kelola</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="ps-4">User</th>
                                    <th>Barang</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($peminjamanAktif)) : ?>
                                    <tr><td colspan="3" class="text-center py-4 text-muted">Tidak ada peminjaman aktif</td></tr>
                                <?php else : ?>
                                    <?php foreach (array_slice($peminjamanAktif, 0, 5) as $p) : ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2 rounded-circle" style="width:32px; height:32px; background: #e3f2fd; color:#1976d2; font-size: 12px;">
                                                    <?= strtoupper(substr($p['nama_peminjam'], 0, 1)) ?>
                                                </div>
                                                <span class="fw-medium text-dark"><?= htmlspecialchars($p['nama_peminjam']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($p['nama_barang']) ?></td>
                                        <td class="text-center">
                                            <span class="badge-soft-warning fw-bold">
                                                <i class="mdi mdi-clock-outline me-1"></i><?= $p['status_peminjaman'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include_once 'layouts/footer.php'; 
ob_end_flush();
?>