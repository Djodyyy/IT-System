<?php
include_once 'layouts/header.php';
require 'functions/function_komputer.php';

// 1. DATA AWAL
$all = getData();

// 2. AMBIL PARAMETER FILTER & SEARCH
$kategori = $_GET['kategori'] ?? '';
$dept     = $_GET['dept'] ?? '';
$tgl_awal = $_GET['tgl_awal'] ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';
$search    = $_GET['search'] ?? '';

// Data Department Mapping
$depts = [
    'OFC' => [
        'GMO' => 'General Manager Office', 'ACC' => 'Accounting', 'PAY' => 'Payroll',
        'EXIM' => 'Export Import', 'PUR' => 'Purchasing', 'BUS' => 'Business',
        'IT' => 'Information Technology', 'CR' => 'Customer Relation', 'HR' => 'Human Resource',
        'DEV' => 'Development', 'COM' => 'Commerce', 'TCOM' => 'Tech Commerce',
        'LEAN' => 'Lean Management', 'PPIC' => 'PPIC', 'BC' => 'Bea Cukai',
        'SEC' => 'Security', 'CLN' => 'Clinic', 'LAB' => 'Laboratory', 'MTN' => 'Maintenance'
    ],
    'PRD' => [
        'OS' => 'Outsole', 'A2' => 'Assembly 2', 'A3' => 'Assembly 3', 'A4' => 'Assembly 4',
        'A5' => 'Assembly 5', 'PRT' => 'Printing', 'STF' => 'Stockfit', 'B3' => 'B3',
        'QMS' => 'QMS', 'WH' => 'Warehouse', 'CHEM' => 'Chemicals'
    ]
];

// 3. PROSES FILTERING & SEARCHING
if ($kategori !== '' || $dept !== '' || ($tgl_awal !== '' && $tgl_akhir !== '') || $search !== '') {
    $all = array_filter($all, function($i) use ($kategori, $dept, $tgl_awal, $tgl_akhir, $search) {
        $check = true;

        // Filter Dropdown & Tanggal
        if ($kategori !== '' && $i['kategori_kom'] !== $kategori) $check = false;
        if ($dept !== '' && $i['dept_kom'] !== $dept) $check = false;
        if ($tgl_awal !== '' && $tgl_akhir !== '') {
            $tgl_beli = $i['tgl_pembelian_kom'];
            if ($tgl_beli < $tgl_awal || $tgl_beli > $tgl_akhir) $check = false;
        }

        // Filter Search (Cari di banyak kolom)
        if ($search !== '') {
            $keyword = strtolower($search);
            $matchSearch = (
                strpos(strtolower($i['nama_assets_kom']), $keyword) !== false ||
                strpos(strtolower($i['kode_assets_kom']), $keyword) !== false ||
                strpos(strtolower($i['user_kom']), $keyword) !== false ||
                strpos(strtolower($i['ip_kom']), $keyword) !== false ||
                strpos(strtolower($i['lokasi_kom']), $keyword) !== false
            );
            if (!$matchSearch) $check = false;
        }

        return $check;
    });
} else {
    // Tampilkan kosong jika belum ada interaksi
    $all = [];
}

$total_qty = array_sum(array_column($all, 'qty_kom'));
?>

<div class="container-fluid">
    <div class="row no-print">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 small">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item active">Report</li>
                    </ol>
                </div>
                <h4 class="page-title"><i class="uil-file-info-alt text-info"></i> Laporan Inventaris Komputer</h4>
            </div>
        </div>
    </div>

    <div class="card no-print shadow-sm border-info border">
        <div class="card-body">
            <h5 class="header-title mb-3"><i class="mdi mdi-filter-menu-outline"></i> Parameter Laporan</h5>
            <form method="GET" id="filterForm" class="row g-2">
                <div class="col-md-2">
                    <label class="small fw-bold">Kategori</label>
                    <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua --</option>
                        <option value="OFC" <?= $kategori == 'OFC' ? 'selected' : '' ?>>Office</option>
                        <option value="PRD" <?= $kategori == 'PRD' ? 'selected' : '' ?>>Produksi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">Department</label>
                    <select name="dept" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua --</option>
                        <?php
                        if ($kategori && isset($depts[$kategori])) {
                            foreach ($depts[$kategori] as $kode => $nama) {
                                echo "<option value='$kode' " . ($dept == $kode ? 'selected' : '') . ">$nama</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">Dari Tanggal</label>
                    <input type="date" name="tgl_awal" class="form-control form-control-sm" value="<?= $tgl_awal ?>">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="<?= $tgl_akhir ?>">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">Cari Data</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama / Kode / User..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary w-100 me-1"><i class="mdi mdi-magnify"></i> Filter</button>
                    <a href="report_komputer.php" class="btn btn-sm btn-outline-secondary"><i class="mdi mdi-refresh"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4 d-none d-print-block">
                <h2 class="mb-0">LAPORAN DATA INVENTARIS KOMPUTER</h2>
                <p class="mb-0">PT. METRO PEARL INDONESIA</p>
                <hr style="border: 2px solid #000;">
                <div class="row text-start small">
                    <div class="col-6">
                        Kategori: <?= $kategori ?: 'Semua' ?> | Dept: <?= $dept ?: 'Semua' ?> <br>
                        Search: <?= $search ?: '-' ?>
                    </div>
                    <div class="col-6 text-end">
                        Periode: <?= ($tgl_awal && $tgl_akhir) ? date('d/m/Y', strtotime($tgl_awal)).' - '.date('d/m/Y', strtotime($tgl_akhir)) : 'Semua Waktu' ?>
                    </div>
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <div class="alert alert-secondary py-1 px-2 mb-0 d-inline-block">
                        Total Quantity: <strong><?= number_format($total_qty) ?></strong> Unit
                    </div>
                </div>
                <div class="col-md-6 text-end no-print">
                    <?php if (!empty($all)): ?>
                        <button onclick="window.print()" class="btn btn-sm btn-danger"><i class="mdi mdi-printer"></i> Print PDF</button>
                        <a href="export_komputer.php?<?= http_build_query($_GET) ?>" class="btn btn-sm btn-success"><i class="mdi mdi-file-excel"></i> Export Excel</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-bordered table-centered mb-0" style="font-size: 11px; color: #000;">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Kode Asset</th>
                            <th>Nama Asset</th>
                            <th>Dept</th>
                            <th>User</th>
                            <th>Tgl Pembelian</th>
                            <th>Spesifikasi</th>
                            <th>IP Address</th>
                            <th>Lokasi</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($all)): ?>
                            <tr><td colspan="10" class="text-center py-4 text-muted">Data tidak ditemukan atau silakan gunakan filter.</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($all as $d): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="fw-bold"><?= $d['kode_assets_kom'] ?></td>
                                    <td><?= $d['nama_assets_kom'] ?></td>
                                    <td class="text-center"><?= $d['dept_kom'] ?></td>
                                    <td><?= $d['user_kom'] ?></td>
                                    <td class="text-center"><?= !empty($d['tgl_pembelian_kom']) ? date('d-m-Y', strtotime($d['tgl_pembelian_kom'])) : '-' ?></td>
                                    <td><small><?= $d['spec_kom'] ?></small></td>
                                    <td class="text-center"><?= $d['ip_kom'] ?></td>
                                    <td><?= $d['lokasi_kom'] ?></td>
                                    <td class="text-center fw-bold"><?= $d['qty_kom'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="row mt-5 d-none d-print-block">
                <div class="col-4 text-center">
                    <p>Dilaporkan Oleh,</p>
                    <br><br><br>
                    <p><strong>( ________________ )</strong><br>IT Dept.</p>
                </div>
                <div class="col-4 text-center">
                </div>
                <div class="col-4 text-center">
                    <p>Diketahui Oleh,</p>
                    <br><br><br>
                    <p><strong>( ________________ )</strong><br>Accounting Dept.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table-bordered th, .table-bordered td { border: 1px solid #000 !important; }
        body { background: #fff !important; }
        @page { size: landscape; margin: 1cm; }
    }
    .table-centered td { vertical-align: middle; }
</style>

<?php include_once 'layouts/footer.php'; ?>