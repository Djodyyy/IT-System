<?php
include_once 'layouts/header_public.php';
require 'functions/function_peminjaman_it.php';

global $conn;

/*
|--------------------------------------------------------------------------
| FILTER TANGGAL (OPSIONAL)
|--------------------------------------------------------------------------
| - Jika pilih tanggal -> tampilkan tanggal tersebut
| - Jika tidak -> default 7 hari terakhir
*/
$tanggal = $_GET['tanggal'] ?? null;

if ($tanggal) {
    $sql = "
        SELECT p.*, b.nama_barang
        FROM tb_peminjaman_it p
        JOIN tb_barang_it b ON p.id_barang = b.id_barang
        WHERE DATE(p.tgl_pinjam) = '$tanggal'
        ORDER BY p.tgl_pinjam DESC
    ";
    $judul = "Riwayat Peminjaman Tanggal " . date('d/m/Y', strtotime($tanggal));
} else {
    $sql = "
        SELECT p.*, b.nama_barang
        FROM tb_peminjaman_it p
        JOIN tb_barang_it b ON p.id_barang = b.id_barang
        WHERE p.tgl_pinjam >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ORDER BY p.tgl_pinjam DESC
    ";
    $judul = "Dashboard Report Peminjaman (7 Hari Terakhir)";
}

$q = mysqli_query($conn, $sql);
$data = $q ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];

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
                <h4 class="page-title">
                    <i class="uil-chart-line text-info"></i>
                    <?= $judul ?>
                </h4>
            </div>
        </div>
    </div>

    <!-- FILTER TANGGAL -->
    <div class="row mb-3">
        <div class="col-12">
            <form method="GET" class="d-flex justify-content-center gap-2">
                <input type="date" name="tanggal"
                       class="form-control form-control-sm"
                       style="max-width:200px"
                       value="<?= htmlspecialchars($tanggal ?? '') ?>">
                <button class="btn btn-sm btn-primary">Cari</button>
                <a href="report_peminjaman_publik.php" class="btn btn-sm btn-secondary">Reset</a>
            </form>
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
                        REPORT DATA PEMINJAMAN ALAT IT
                    </h4>
                    <hr>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peminjam</th>
                                    <th>Nama Barang</th>
                                    <th>Tanggal & Jam Pinjam</th>
                                    <th>Tanggal & Jam Kembali</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php if ($data): ?>
                                <?php $no = 1; foreach ($data as $row): ?>
                                    <tr class="<?= $row['status_peminjaman'] === 'Dipinjam' ? 'table-warning' : '' ?>">
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['tgl_pinjam'])) ?></td>
                                        <td>
                                            <?= $row['tgl_kembali']
                                                ? date('d/m/Y H:i', strtotime($row['tgl_kembali']))
                                                : '-' ?>
                                        </td>
                                        <td>
                                            <?php if ($row['status_peminjaman'] === 'Dipinjam'): ?>
                                                <span class="badge bg-danger">Masih Dipinjam</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Dikembalikan</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Tidak ada data peminjaman</td>
                                </tr>
                            <?php endif; ?>

                            </tbody>
                        </table>
                    </div>

                    <p class="text-center text-muted mt-3" style="font-size:12px">
                        Auto refresh setiap 30 detik • Mode publik
                    </p>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- ================= AUTO RELOAD ================= -->
<script>
    setInterval(function () {
        window.location.reload();
    }, 30000); // 30 detik
</script>

<?php include_once 'layouts/footer.php'; ?>
