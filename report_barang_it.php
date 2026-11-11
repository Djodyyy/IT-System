<?php
include_once 'layouts/header.php';
require 'functions/function_device_it.php';

$kategori = $_GET['kategori'] ?? '';
$status   = $_GET['status']   ?? '';
$search   = $_GET['search']   ?? '';

$data = getAllDeviceIT();

/* ===== FILTER ===== */
if ($kategori !== '') {
    $data = array_filter($data, fn($i) => $i['kategori_device'] === $kategori);
}

if ($status !== '') {
    $data = array_filter($data, fn($i) => $i['status_device'] === $status);
}

if ($search !== '') {
    $data = array_filter($data, function ($i) use ($search) {
        return stripos($i['kode_device_it'], $search) !== false ||
               stripos($i['nama_device'], $search) !== false ||
               stripos($i['lokasi_device'], $search) !== false;
    });
}

/* ===== SUMMARY ===== */
$total_qty     = array_sum(array_column($data, 'qty'));
$total_aktif   = array_sum(array_column(array_filter($data, fn($i) => $i['status_device']=='aktif'), 'qty'));
$total_rusak   = array_sum(array_column(array_filter($data, fn($i) => $i['status_device']=='rusak'), 'qty'));
$total_pinjam  = array_sum(array_column(array_filter($data, fn($i) => $i['status_device']=='dipinjam'), 'qty'));
?>

<div class="container-fluid">
    <div class="page-title-box">
        <h4 class="page-title">
            <i class="mdi mdi-file-chart-outline text-info"></i> Report Device IT
        </h4>
    </div>

    <div class="card">
        <div class="card-body">

            <!-- FILTER -->
            <form method="GET" class="row mb-3 g-2">
                <div class="col-md-3">
                    <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Kategori --</option>
                        <?php
                        $kat = array_unique(array_column(getAllDeviceIT(), 'kategori_device'));
                        foreach ($kat as $k) {
                            $sel = ($kategori == $k) ? 'selected' : '';
                            echo "<option value='$k' $sel>$k</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        <option value="aktif" <?= $status=='aktif'?'selected':'' ?>>Aktif</option>
                        <option value="rusak" <?= $status=='rusak'?'selected':'' ?>>Rusak</option>
                        <option value="dipinjam" <?= $status=='dipinjam'?'selected':'' ?>>Dipinjam</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari Kode / Nama / Lokasi"
                               value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-primary">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                        <?php if ($kategori || $status || $search): ?>
                            <a href="report_barang_it.php" class="btn btn-outline-secondary">Reset</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <!-- SUMMARY -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="alert alert-primary py-1 mb-0">
                        Total Device : <strong><?= $total_qty ?></strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-success py-1 mb-0">
                        Aktif : <strong><?= $total_aktif ?></strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-danger py-1 mb-0">
                        Rusak : <strong><?= $total_rusak ?></strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-warning py-1 mb-0">
                        Dipinjam : <strong><?= $total_pinjam ?></strong>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped" style="font-size:12px">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Nama</th>
                            <th>Lokasi</th>
                            <th>Tgl Pembelian</th>
                            <th>Qty</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($data)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Data tidak ditemukan</td>
                        </tr>
                    <?php else:
                        $no = 1;
                        foreach ($data as $d): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $d['kode_device_it'] ?></td>
                            <td><?= $d['kategori_device'] ?></td>
                            <td><?= $d['nama_device'] ?></td>
                            <td><?= $d['lokasi_device'] ?></td>
                            <td><?= $d['tgl_pembelian'] ?: '-' ?></td>
                            <td class="text-end"><?= $d['qty'] ?></td>
                            <td>
                                <span class="badge
                                    <?= $d['status_device']=='aktif'?'bg-success':
                                        ($d['status_device']=='rusak'?'bg-danger':'bg-warning') ?>">
                                    <?= ucfirst($d['status_device']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include_once 'layouts/footer.php'; ?>
