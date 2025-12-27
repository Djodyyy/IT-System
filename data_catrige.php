<?php
include_once 'layouts/header.php';
require 'functions/function_catrige.php';


$printerJenisList = getAllJenisPrinter();


$all = getAllCatrige();
?>
<div class="container-fluid">
    <!-- page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item active">Data Catrige</li>
                    </ol>
                </div>
                <h4 class="page-title"><i class="uil-arrow-circle-right text-info"></i> Catrige</h4>
            </div>
        </div>
    </div>
    <!-- end title -->

    <div class="card">
        <div class="card-body">
            <h4 class="header-title text-center">TABEL CATRIGE</h4>
            <hr>
            <div class="text-end mb-3">
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#add-catrige">
                    <i class="mdi mdi-plus-circle-outline"></i> Create New
                </button>
            </div>

            <div class="table-responsive-sm">
                <table id="scroll-horizontal-datatable" class="table table-sm nowrap w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Printer</th>
                            <th>Tipe</th>
                            <th>Warna</th>
                            <th>Stock</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($all)): ?>
                            <?php foreach ($all as $data): ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['nama_printer'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($data['type_catrige'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($data['color_catrige'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($data['jml_catrige'] ?? '0') ?></td>
                                    <td class="text-center">
                                        <a href="edit_catrige.php?id_catrige=<?= urlencode($data['id_catrige']) ?>" class="action-icon">
                                            <i class="mdi mdi-pencil text-primary"></i>
                                        </a>
                                        <a href="functions/function_catrige.php?hapus=<?= urlencode($data['id_catrige']) ?>" class="action-icon" onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="mdi mdi-delete text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data cartridge</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="add-catrige" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="functions/function_catrige.php" method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Data Catrige</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Jenis Printer</label>
                            <select name="id_jenis" class="form-select" required>
                                <option value="">-- Pilih Jenis Printer --</option>
                                <?php foreach ($printerJenisList as $p): ?>
                                    <option value="<?= htmlspecialchars($p['id_jenis']) ?>">
                                        <?= htmlspecialchars($p['nama_jenis']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Type Catrige</label>
                            <input type="text" name="type_catrige" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Warna</label>
                            <input type="text" name="color_catrige" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Jumlah</label>
                            <input type="number" name="jml_catrige" class="form-control" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="add">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once 'layouts/footer.php'; ?>
