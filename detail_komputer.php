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
    echo "<script>alert('Data tidak ditemukan di database'); window.location='data_komputer.php';</script>";
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-1">
            <div class="page-title-box">
                <a href="data_komputer.php"><h4 class="page-title"><i class="uil-arrow-circle-left text-info"></i> Back</h4></a>
            </div>
        </div>
        <div class="col-11">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item">Komputer</li>
                        <a href="data_komputer.php" class="breadcrumb-item">Data Komputer</a>
                        <li class="breadcrumb-item active">Detail Komputer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>     

    <div class="row">
        <!-- DETAIL KOMPUTER -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3 text-center">Detail Komputer</h4>
                    <hr>
                    <table class="table table-borderless">
                        <tbody>
                            <?php
                                $fields = [
                                    'Kode Assets' => 'kode_assets_kom',
                                    'Nama Assets' => 'nama_assets_kom',
                                    'Tanggal Pembelian' => 'tgl_pembelian_kom',
                                    'User' => 'user_kom',
                                    'IP' => 'ip_kom',
                                    'Spec' => 'spec_kom',
                                    'Lokasi' => 'lokasi_kom',
                                    'Qty' => 'qty_kom',
                                    'Desc' => 'desc_kom',
                                    'Keterangan' => 'keterangan_kom'
                                ];
                                foreach ($fields as $label => $key):
                            ?>
                            <tr>
                                <th><strong><?= htmlspecialchars($label) ?></strong></th>
                                <td>:</td>
                                <td><?= isset($data[$key]) ? htmlspecialchars($data[$key]) : '-' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIWAYAT PERBAIKAN -->
        <div class="col-lg-6" style="font-size: 11px;">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3 text-center">Riwayat Perbaikan</h4>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-perbaikan">
                                <i class="mdi mdi-plus-circle-outline"></i> Create New
                            </button>
                        </div>
                        <div class="col-sm-6 text-end">
                            <a href="#" class="btn btn-sm btn-dark mb-2">Export</a>
                        </div>
                    </div>

                    <table id="scroll-horizontal-datatable" class="table table-striped w-100 nowrap">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th width="80px">Tanggal</th>
                                <th width="50px">Status</th>
                                <th width="50px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $riwayat = getPerbaikan($kode_assets_kom); ?>
                            <?php foreach ($riwayat as $data_perbaikan): ?>
                            <tr>
                                <td><?= htmlspecialchars($data_perbaikan['deskripsi_perbaikan']) ?></td>
                                <td><?= htmlspecialchars($data_perbaikan['tanggal_perbaikan']) ?></td>
                                <td>
                                    <span class="badge <?= $data_perbaikan['status_perbaikan'] === 'perbaikan' ? 'bg-warning' : 'bg-danger' ?>">
                                        <?= htmlspecialchars($data_perbaikan['status_perbaikan']) ?>
                                    </span>
                                </td>
                                <td class="table-action">
                                    <a href="functions/function_komputer.php?hapus1=<?= urlencode($data_perbaikan['id_perbaikan_kom']) ?>&kode_assets_kom=<?= urlencode($kode_assets_kom) ?>" class="action-icon" onclick="return confirm('Yakin hapus?');">
                                        <i class="mdi mdi-delete text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Tambah Perbaikan -->
            <div class="modal fade" id="add-perbaikan" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Create New Data</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="functions/function_komputer.php" method="POST" class="p-2" autocomplete="off">
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <input type="text" name="deskripsi_perbaikan" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="text" name="tanggal_perbaikan" class="form-control" placeholder="Contoh: <?= date('d-m-Y') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status_perbaikan" class="form-select" required>
                                        <option selected disabled>Pilih status</option>
                                        <option value="perbaikan">Perbaikan</option>
                                        <option value="pergantian">Pergantian</option>
                                    </select>
                                </div>
                                <input type="hidden" name="kode_assets_kom" value="<?= htmlspecialchars($kode_assets_kom) ?>">
                                <input type="hidden" name="add1" value="1">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div> <!-- container -->

<?php include_once 'layouts/footer_detail.php'; ?>
