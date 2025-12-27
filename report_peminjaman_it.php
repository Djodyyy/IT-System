<?php
include_once 'layouts/header.php';
require 'functions/function_peminjaman_it.php';

// ambil semua data peminjaman
$data = getAllPeminjaman();

// summary
$total = count($data);
$dipinjam = 0;
$dikembalikan = 0;

foreach ($data as $d) {
    if ($d['status_peminjaman'] === 'Dipinjam') {
        $dipinjam++;
    } else {
        $dikembalikan++;
    }
}
?>

<div class="container-fluid">

    <!-- PAGE TITLE -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item">Peminjaman</li>
                        <li class="breadcrumb-item active">Report Peminjaman IT</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="uil-chart-line text-info"></i> Dashboard Report Peminjaman IT
                </h4>
            </div>
        </div>
    </div>

    <!-- DASHBOARD SUMMARY -->
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5>Total Peminjaman</h5>
                    <h2><?= $total ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h5>Sedang Dipinjam</h5>
                    <h2><?= $dipinjam ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5>Sudah Dikembalikan</h5>
                    <h2><?= $dikembalikan ?></h2>
                </div>
            </div>
        </div>

    </div>

    <!-- TABLE REPORT -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title text-center">
                        Report Data Peminjaman Alat IT
                    </h4>
                    <hr>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peminjam</th>
                                    <th>Nama Barang</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (count($data) > 0): ?>
                                    <?php $no = 1; foreach ($data as $row): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                                            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['tgl_pinjam'])) ?></td>
                                            <td>
                                                <?= $row['tgl_kembali']
                                                    ? date('d/m/Y', strtotime($row['tgl_kembali']))
                                                    : '-' ?>
                                            </td>
                                            <td>
                                                <?php if ($row['status_peminjaman'] === 'Dipinjam'): ?>
                                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">Belum ada data peminjaman</td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<?php include_once 'layouts/footer.php'; ?>
