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
                <a href="data_komputer.php">
                    <h4 class="page-title">
                        <i class="uil-arrow-circle-left text-info"></i> Back
                    </h4>
                </a>
            </div>
        </div>
        <div class="col-11">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item">Komputer</li>
                        <a href="data_komputer.php" class="breadcrumb-item">Data Komputer</a>
                        <li class="breadcrumb-item active">Edit Komputer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- ================= EDIT DATA KOMPUTER ================= -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mb-3 text-center">Edit Data Komputer</h4>
                    <hr>

                    <form action="functions/function_komputer.php" method="POST">
                        <input type="hidden" name="kode_assets_kom" value="<?= htmlspecialchars($data['kode_assets_kom']) ?>">

                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <th width="160">Kode Assets</th>
                                    <td width="10">:</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                               value="<?= htmlspecialchars($data['kode_assets_kom']) ?>" readonly>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Nama Assets</th>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="nama_assets_kom"
                                               class="form-control form-control-sm"
                                               value="<?= htmlspecialchars($data['nama_assets_kom']) ?>" required>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Tanggal Pembelian</th>
                                    <td>:</td>
                                    <td>
                                        <input type="date" name="tgl_pembelian_kom"
                                               class="form-control form-control-sm"
                                               value="<?= htmlspecialchars($data['tgl_pembelian_kom']) ?>">
                                    </td>
                                </tr>

                                <tr>
                                    <th>User</th>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="user_kom"
                                               class="form-control form-control-sm"
                                               value="<?= htmlspecialchars($data['user_kom']) ?>">
                                    </td>
                                </tr>

                                <tr>
                                    <th>IP</th>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="ip_kom"
                                               class="form-control form-control-sm"
                                               value="<?= htmlspecialchars($data['ip_kom']) ?>">
                                    </td>
                                </tr>

                                <tr>
                                    <th>Spec</th>
                                    <td>:</td>
                                    <td>
                                        <textarea name="spec_kom"
                                                  class="form-control form-control-sm"
                                                  rows="2"><?= htmlspecialchars($data['spec_kom']) ?></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Lokasi</th>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="lokasi_kom"
                                               class="form-control form-control-sm"
                                               value="<?= htmlspecialchars($data['lokasi_kom']) ?>">
                                    </td>
                                </tr>

                                <tr>
                                    <th>Qty</th>
                                    <td>:</td>
                                    <td>
                                        <input type="number" name="qty_kom"
                                               class="form-control form-control-sm"
                                               value="<?= htmlspecialchars($data['qty_kom']) ?>" min="1">
                                    </td>
                                </tr>

                                <tr>
                                    <th>Deskripsi</th>
                                    <td>:</td>
                                    <td>
                                        <textarea name="desc_kom"
                                                  class="form-control form-control-sm"
                                                  rows="2"><?= htmlspecialchars($data['desc_kom']) ?></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Keterangan</th>
                                    <td>:</td>
                                    <td>
                                        <textarea name="keterangan_kom"
                                                  class="form-control form-control-sm"
                                                  rows="2"><?= htmlspecialchars($data['keterangan_kom']) ?></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="text-end">
                            <button type="submit" name="edit" class="btn btn-primary">
                                <i class="mdi mdi-content-save-outline"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- ================= RIWAYAT PERBAIKAN ================= -->
        <div class="col-lg-6" style="font-size:11px;">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mb-3 text-center">Riwayat Perbaikan</h4>
                    <hr>

                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <button class="btn btn-sm btn-success"
                                data-bs-toggle="modal"
                                data-bs-target="#add-perbaikan">
                                <i class="mdi mdi-plus-circle-outline"></i> Create New
                            </button>
                        </div>
                        <div class="col-sm-6 text-end">
                            <a href="export_perbaikan_komputer.php?kode_assets_kom=<?= urlencode($kode_assets_kom) ?>"
                               class="btn btn-sm btn-dark">
                                <i class="mdi mdi-file-excel-outline"></i> Export
                            </a>
                        </div>
                    </div>

                    <table class="table table-striped w-100 nowrap">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th width="80">Tanggal</th>
                                <th width="70">Status</th>
                                <th width="50">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (getPerbaikan($kode_assets_kom) as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['deskripsi_perbaikan']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($r['tanggal_perbaikan'])) ?></td>
                                    <td>
                                        <span class="badge <?= $r['status_perbaikan'] === 'perbaikan' ? 'bg-warning' : 'bg-danger' ?>">
                                            <?= htmlspecialchars($r['status_perbaikan']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="functions/function_komputer.php?hapus1=<?= $r['id_perbaikan_kom'] ?>&kode_assets_kom=<?= $kode_assets_kom ?>"
                                           onclick="return confirm('Yakin hapus?');">
                                            <i class="mdi mdi-delete text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>

            <!-- MODAL TAMBAH PERBAIKAN -->
            <div class="modal fade" id="add-perbaikan" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="functions/function_komputer.php" method="POST">
                            <div class="modal-header">
                                <h4 class="modal-title">Create New Perbaikan</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-2">
                                    <label>Deskripsi</label>
                                    <input type="text" name="deskripsi_perbaikan" class="form-control" required>
                                </div>

                                <div class="mb-2">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal_perbaikan"
                                           class="form-control"
                                           value="<?= date('Y-m-d') ?>">
                                </div>

                                <div class="mb-2">
                                    <label>Status</label>
                                    <select name="status_perbaikan" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="perbaikan">Perbaikan</option>
                                        <option value="pergantian">Pergantian</option>
                                    </select>
                                </div>

                                <input type="hidden" name="kode_assets_kom" value="<?= htmlspecialchars($kode_assets_kom) ?>">
                                <input type="hidden" name="add1" value="1">
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include_once 'layouts/footer_detail.php'; ?>
