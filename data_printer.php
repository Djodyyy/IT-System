<?php
include_once 'layouts/header.php';
require_once 'functions/function_printer.php';

$allPrinter = getAllPrinter();
$jenisList = getAllJenisPrinter();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Printer</li>
                        <li class="breadcrumb-item active">Data Printer</li>
                    </ol>
                </div>
                <h4 class="page-title"><i class="uil-printer text-info"></i> Data Printer</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-center mb-3">TABEL DATA PRINTER</h4>
                    <hr>

                    <div class="row mb-2">
                        <div class="col-sm-12 text-sm-end">
                            <button class="btn btn-sm btn-info mb-2" data-bs-toggle="modal" data-bs-target="#add-jenis-printer">
                                <i class="mdi mdi-tag-plus"></i> Tambah Jenis Printer
                            </button>
                            
                            <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-printer">
                                <i class="mdi mdi-plus-circle-outline"></i> Tambah Printer
                            </button>
                            <a href="export_printer.php" class="btn btn-sm btn-dark mb-2">
                                <i class="mdi mdi-file-excel"></i> Export
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive-sm">
                        <table id="scroll-horizontal-datatable" class="table table-sm w-100 nowrap" style="font-size: 12px;">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Printer</th>
                                    <th>IP Address</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($allPrinter)): ?>
                                    <?php $no = 1; foreach ($allPrinter as $data): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($data['nama_jenis']) ?></td>
                                            <td><?= htmlspecialchars($data['ip_printer']) ?></td>
                                            <td><i class="mdi mdi-map-marker-outline text-info"></i> <?= htmlspecialchars($data['lokasi_printer']) ?></td>
                                            <td>
                                                <?php
                                                switch ($data['status_printer']) {
                                                    case 1: echo '<span class="badge bg-success">Aktif</span>'; break;
                                                    case 0: echo '<span class="badge bg-warning text-dark">Idle</span>'; break;
                                                    case 2: echo '<span class="badge bg-secondary">Off</span>'; break;
                                                    default: echo '<span class="badge bg-light text-dark">Tidak Diketahui</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($data['tanggal_printer']) ?></td>
                                            <td class="table-action">
                                                <a href="edit_printer.php?id_printer=<?= $data['id_printer'] ?>" class="action-icon"> <i class="mdi mdi-pencil text-primary"></i></a>
                                                <a href="functions/function_printer.php?hapus=<?= $data['id_printer'] ?>" class="action-icon" onclick="return confirm('Yakin ingin menghapus?')"> <i class="mdi mdi-delete text-danger"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center">Tidak ada data printer</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-jenis-printer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title text-white">Tambah Jenis Printer Baru</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="functions/function_printer.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jenis Printer</label>
                        <input type="text" name="nama_jenis" class="form-control" placeholder="Contoh: Brother T-Series, Epson L3110" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="add_jenis">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Simpan Jenis</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add-printer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title text-white">Tambah Data Printer</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="functions/function_printer.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Printer</label>
                            <select name="jenis_printer" class="form-select" required>
                                <option value="" disabled selected>Pilih Jenis Printer</option>
                                <?php foreach ($jenisList as $jenis): ?>
                                    <option value="<?= $jenis['id_jenis']; ?>"><?= htmlspecialchars($jenis['nama_jenis']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi Printer</label>
                            <input type="text" name="lokasi_printer" class="form-control" placeholder="Gedung A, Lt 2" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">IP Address</label>
                            <input type="text" name="ip_printer" class="form-control" placeholder="10.10.x.x" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_printer" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Idle</option>
                                <option value="2">Off</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="text" name="tanggal_printer" class="form-control" value="<?= date('d M Y'); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="add">
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once 'layouts/footer.php'; ?>