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
                        <a href="data_komputer.php" class="breadcrumb-item">Data Komputer</a>
                        <li class="breadcrumb-item active">Edit Komputer</li>
                    </ol>
                </div>
                <a href="data_komputer.php"><h4 class="page-title"><i class="uil-arrow-circle-left text-info"></i> Back</h4></a>
            </div>
        </div>
    </div>     

    <form action="functions/function_komputer.php" method="POST">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3 text-center">Edit Data Komputer</h4>
                        <hr>
                        <table class="w-100">
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
                                <tr class="align-top">
                                    <th class="pb-2" width="150px"><label class="form-label"><?= $label ?></label></th>
                                    <th class="pb-2" width="10px">:</th>
                                    <td class="pb-2">
                                        <?php if ($key == 'keterangan_kom' || $key == 'spec_kom' || $key == 'desc_kom'): ?>
                                            <textarea name="<?= $key ?>" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($data[$key]) ?></textarea>
                                        <?php elseif ($key == 'tgl_pembelian_kom'): ?>
                                            <input type="date" name="<?= $key ?>" class="form-control form-control-sm" value="<?= htmlspecialchars($data[$key]) ?>">
                                        <?php elseif ($key == 'kode_assets_kom'): ?>
                                            <input type="text" name="<?= $key ?>" class="form-control form-control-sm" value="<?= htmlspecialchars($data[$key]) ?>" readonly>
                                        <?php else: ?>
                                            <input type="text" name="<?= $key ?>" class="form-control form-control-sm" value="<?= htmlspecialchars($data[$key]) ?>">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-end mt-3">
                            <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Perbaikan -->
            <div style="font-size: 11px;" class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3 text-center">Riwayat Perbaikan</h4>
                        <hr>
                        <div class="row mb-2">
                            <div class="col-sm-12 text-end">
                                <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-detail"><i class="mdi mdi-plus-circle-outline"></i> Create New</button>
                                <a href="#" class="btn btn-sm btn-dark mb-2">Export</a>
                            </div>
                        </div>

                        <table id="scroll-vertical-datatable" class="table table-sm dt-responsive nowrap">
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
                                    <td><span class="badge <?= $data_perbaikan['status_perbaikan'] == 'perbaikan' ? 'bg-warning' : 'bg-danger' ?>"><?= htmlspecialchars($data_perbaikan['status_perbaikan']) ?></span></td>
                                    <td class="table-action">
                                        <a href="javascript:void(0);" class="action-icon"><i class="mdi mdi-pencil text-primary"></i></a>
                                        <a href="functions/function_komputer.php?hapus1=<?= $data_perbaikan['id_perbaikan_kom'] ?>&kode_assets_kom=<?= urlencode($kode_assets_kom) ?>" class="action-icon"><i class="mdi mdi-delete text-danger"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include_once 'layouts/footer_detail.php'; ?>
