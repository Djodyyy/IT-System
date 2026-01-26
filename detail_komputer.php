<?php
include_once 'layouts/header_detail.php';
require 'functions/function_komputer.php';

if (!isset($_GET['kode_assets_kom'])) {
    echo "<script>alert('Kode aset tidak ditemukan'); window.location='data_komputer.php';</script>";
    exit;
}

$kode_assets_kom = $_GET['kode_assets_kom'];
$data = getDetailKomputer($kode_assets_kom);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='data_komputer.php';</script>";
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item">Komputer</li>
                        <li class="breadcrumb-item">
                            <a href="data_komputer.php">Data Komputer</a>
                        </li>
                        <li class="breadcrumb-item active">Detail Komputer</li>
                    </ol>
                </div>
                <a href="data_komputer.php">
                    <h4 class="page-title">
                        <i class="uil-arrow-circle-left text-info"></i> Back
                    </h4>
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- DETAIL KOMPUTER (READ ONLY) -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <!-- HEADER + EXPORT -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="header-title mb-0">Detail Data Komputer</h4>

                        <!-- GANTI FILE JIKA NAMA EXPORT LU BEDA -->
                        <a href="export_komputer.php?kode_assets_kom=<?= urlencode($kode_assets_kom) ?>"
                            class="btn btn-sm btn-success">
                            <i class="mdi mdi-file-excel-outline"></i> Export Excel
                        </a>
                    </div>

                    <hr>

                    <table class="table table-sm table-borderless">
                        <tbody>
                            <?php
                            $fields = [
                                'Kode Assets'        => 'kode_assets_kom',
                                'Kategori'           => 'kategori_kom',
                                'Department'         => 'dept_kom',
                                'Nama Assets'        => 'nama_assets_kom',
                                'Tanggal Pembelian'  => 'tgl_pembelian_kom',
                                'User'               => 'user_kom',
                                'IP'                 => 'ip_kom',
                                'Spec'               => 'spec_kom',
                                'Lokasi'             => 'lokasi_kom',
                                'Qty'                => 'qty_kom',
                                'Deskripsi'          => 'desc_kom',
                                'Keterangan'         => 'keterangan_kom'
                            ];

                            foreach ($fields as $label => $key):
                            ?>
                                <tr>
                                    <th width="160"><?= $label ?></th>
                                    <th width="10">:</th>
                                    <td>
                                        <?php
                                        if ($key === 'tgl_pembelian_kom') {
                                            echo !empty($data[$key])
                                                ? date('d/m/Y', strtotime($data[$key]))
                                                : '-';
                                        } else {
                                            echo !empty($data[$key])
                                                ? nl2br(htmlspecialchars($data[$key]))
                                                : '-';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- RIWAYAT PERBAIKAN (READ ONLY) -->
        <div class="col-lg-6" style="font-size:11px;">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3 text-center">Riwayat Perbaikan</h4>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-6 text-end">
                            <a href="export_perbaikan_komputer.php" class="btn btn-sm btn-dark mb-2">Export</a>
                        </div>
                    </div>

                    <table class="table table-striped w-100">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th width="90">Tanggal</th>
                                <th width="90">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $riwayat = getPerbaikan($kode_assets_kom);
                            if (empty($riwayat)):
                            ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Tidak ada riwayat perbaikan
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($riwayat as $r): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($r['deskripsi_perbaikan']) ?></td>
                                        <td>
                                            <?= !empty($r['tanggal_perbaikan'])
                                                ? date('d/m/Y', strtotime($r['tanggal_perbaikan']))
                                                : '-' ?>
                                        </td>
                                        <td>
                                            <span class="badge <?= $r['status_perbaikan'] === 'perbaikan' ? 'bg-warning' : 'bg-danger' ?>">
                                                <?= htmlspecialchars(ucfirst($r['status_perbaikan'])) ?>
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

<?php include_once 'layouts/footer_detail.php'; ?>