<?php
include_once 'layouts/header.php';
require 'functions/function_peminjaman_it.php';
global $conn;

/*
|--------------------------------------------------------------------------
| FILTER TANGGAL (Logika Admin)
|--------------------------------------------------------------------------
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
        ORDER BY p.tgl_pinjam DESC
    ";
    $judul = "Dashboard Utama Laporan IT (Semua Data)";
}

$q = mysqli_query($conn, $sql);
$data = $q ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];

/*
|--------------------------------------------------------------------------
| DATA STOK & SUMMARY (Sama dengan Public)
|--------------------------------------------------------------------------
*/
$laptop = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT SUM(stok) total FROM tb_barang_it WHERE kategori_barang='Laptop'"
))['total'] ?? 0;

$other = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT SUM(stok) total FROM tb_barang_it WHERE kategori_barang!='Laptop'"
))['total'] ?? 0;

$dipinjam = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) total FROM tb_peminjaman_it WHERE status_peminjaman='Dipinjam'"
))['total'] ?? 0;

$totalStok = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT SUM(stok) total FROM tb_barang_it"
))['total'] ?? 0;

// Detail untuk dropdown klik
$laptopDetail = mysqli_fetch_all(mysqli_query($conn, 
    "SELECT nama_barang, stok FROM tb_barang_it WHERE kategori_barang='Laptop' AND stok>0"
), MYSQLI_ASSOC);

$otherDetail = mysqli_fetch_all(mysqli_query($conn, 
    "SELECT nama_barang, stok FROM tb_barang_it WHERE kategori_barang!='Laptop' AND stok>0"
), MYSQLI_ASSOC);
?>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        --info-gradient: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
        --warning-gradient: linear-gradient(135deg, #f83600 0%, #f9d423 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .glass-card {
        border-radius: 20px; padding: 25px; color: #fff; text-align: center;
        cursor: pointer; transition: all 0.3s ease; border: none;
        position: relative; overflow: hidden; height: 100%; min-height: 160px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .glass-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.2); }
    
    .card-laptop { background: var(--primary-gradient); }
    .card-other { background: var(--info-gradient); }
    .card-borrowed { background: var(--warning-gradient); }
    .card-total { background: var(--success-gradient); }

    .glass-title { font-size: 0.9rem; font-weight: 600; text-transform: uppercase; opacity: 0.9; }
    .glass-number { font-size: 3rem; font-weight: 800; margin: 10px 0; }
    .glass-sub { font-size: 0.75rem; background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; }

    .glass-detail {
        display: none; margin-top: 15px; background: #ffffff; color: #333;
        border-radius: 12px; padding: 15px; text-align: left;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
    }
    .detail-item { display: flex; justify-content: space-between; font-size: 0.85rem; padding: 8px 0; border-bottom: 1px solid #eee; }
    .detail-item:last-child { border-bottom: none; }

    .custom-table thead { background: #343a40; color: white; }
    .badge-status { padding: 6px 12px; border-radius: 8px; font-weight: 600; }
    
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-up { animation: fadeInUp 0.4s ease forwards; }
</style>

<div class="container-fluid py-4">

    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1">
                <i class="uil-desktop text-primary me-2"></i>Admin Inventory Report
            </h3>
            <p class="text-muted small"><?= $judul ?></p>
        </div>
        <div class="col-md-6 text-md-end">
            <form method="GET" class="d-inline-flex gap-2 bg-white p-2 rounded-3 shadow-sm border">
                <input type="date" name="tanggal" class="form-control form-control-sm border-0" 
                       value="<?= htmlspecialchars($tanggal ?? '') ?>">
                <button class="btn btn-sm btn-primary px-3">Filter</button>
                <a href="report_peminjaman_it.php" class="btn btn-sm btn-light border">Reset</a>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-card card-laptop" onclick="toggleDetail('laptopDetail')">
                <div class="glass-title">Laptop Tersedia</div>
                <div class="glass-number" data-count="<?= $laptop ?>">0</div>
                <div class="glass-sub"><i class="uil-mouse-alt"></i> Klik Detail</div>
                <div class="glass-detail animate-up" id="laptopDetail">
                    <?php foreach ($laptopDetail as $d): ?>
                        <div class="detail-item">
                            <span><?= htmlspecialchars($d['nama_barang']) ?></span>
                            <span class="fw-bold text-primary"><?= $d['stok'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="glass-card card-other" onclick="toggleDetail('otherDetail')">
                <div class="glass-title">Perangkat Lain</div>
                <div class="glass-number" data-count="<?= $other ?>">0</div>
                <div class="glass-sub"><i class="uil-layers"></i> Klik Detail</div>
                <div class="glass-detail animate-up" id="otherDetail">
                    <?php foreach ($otherDetail as $d): ?>
                        <div class="detail-item">
                            <span><?= htmlspecialchars($d['nama_barang']) ?></span>
                            <span class="fw-bold text-info"><?= $d['stok'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="glass-card card-borrowed">
                <div class="glass-title">Sedang Dipinjam</div>
                <div class="glass-number" data-count="<?= $dipinjam ?>">0</div>
                <div class="glass-sub"><i class="uil-sync"></i> Mode Realtime</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="glass-card card-total">
                <div class="glass-title">Total Stok</div>
                <div class="glass-number" data-count="<?= $totalStok ?>">0</div>
                <div class="glass-sub"><i class="uil-check-circle"></i> Aset Terdaftar</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0 text-dark">Log Aktivitas Peminjaman (Admin View)</h5>
                <span class="badge bg-soft-primary text-primary px-3">Updated Live</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center border-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="border-0">No</th>
                            <th class="border-0">Peminjam</th>
                            <th class="border-0">Nama Barang</th>
                            <th class="border-0">Waktu Pinjam</th>
                            <th class="border-0">Waktu Kembali</th>
                            <th class="border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($data): $no=1; foreach ($data as $r): ?>
                        <tr>
                            <td class="text-muted"><?= $no++ ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($r['nama_peminjam']) ?></td>
                            <td><?= htmlspecialchars($r['nama_barang']) ?></td>
                            <td><small><?= date('d M, H:i', strtotime($r['tgl_pinjam'])) ?></small></td>
                            <td><small><?= $r['tgl_kembali'] ? date('d M, H:i', strtotime($r['tgl_kembali'])) : '--:--' ?></small></td>
                            <td>
                                <?php if($r['status_peminjaman']=='Dipinjam'): ?>
                                    <span class="badge bg-warning text-dark badge-status">AKTIF</span>
                                <?php else: ?>
                                    <span class="badge bg-success text-white badge-status">SELESAI</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="py-5 text-muted">Tidak ada data peminjaman ditemukan.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-3 border-top text-center">
                <p class="text-muted mb-0" style="font-size:11px">Auto refresh setiap 30 detik • <strong>Mode Admin</strong></p>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // COUNT UP ANIMATION
    document.querySelectorAll('.glass-number').forEach(el=>{
        let target=+el.dataset.count, count=0, duration=1000;
        let step = target / (duration / 30);
        let i = setInterval(() => {
            count += step;
            if(count >= target) { el.textContent = target; clearInterval(i); }
            else el.textContent = Math.floor(count);
        }, 30);
    });
});

function toggleDetail(id){
    const el = document.getElementById(id);
    const isVisible = el.style.display === 'block';
    document.querySelectorAll('.glass-detail').forEach(d => d.style.display = 'none');
    if(!isVisible) el.style.display = 'block';
}

// AUTO REFRESH (30 DETIK)
setInterval(() => {
    // Hanya reload jika tidak sedang memfilter tanggal (agar input tidak hilang)
    if (!window.location.search.includes('tanggal')) {
        location.reload();
    }
}, 30000);
</script>

<?php include_once 'layouts/footer.php'; ?>