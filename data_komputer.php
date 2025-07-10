<?php
include_once 'layouts/header.php';
require 'functions/function_komputer.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item">Komputer</li>
                        <li class="breadcrumb-item active">Data Komputer</li>
                    </ol>
                </div>
                <h4 class="page-title"><i class="uil-arrow-circle-right text-info"></i> Komputer</h4>
            </div>
        </div>
    </div>

    <?php
    // Notifikasi berhasil import
    if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> Data komputer berhasil diimport.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php
    // Notifikasi gagal import
    if (isset($_GET['success']) && $_GET['success'] == 0): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> Import Excel gagal diproses.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php
    $all = getData();
    $prefixes = [];

    // Ambil prefix dari kode_assets_kom
    foreach ($all as $item) {
        $parts = explode('-', $item['kode_assets_kom']);
        if (isset($parts[0]) && !in_array($parts[0], $prefixes)) {
            $prefixes[] = $parts[0];
        }
    }

    sort($prefixes);

    $selectedPrefix = isset($_GET['prefix']) ? $_GET['prefix'] : '';

    if ($selectedPrefix !== '') {
        $all = array_filter($all, function ($item) use ($selectedPrefix) {
            return strpos($item['kode_assets_kom'], $selectedPrefix . '-') === 0;
        });
    }

    $total_qty = array_sum(array_column($all, 'qty_kom'));
    ?>

    <div class="row">
        <div>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-center">TABEL KOMPUTER</h4>
                    <hr>

                    <!-- Filter berdasarkan prefix kode_assets_kom -->
                    <form method="GET" class="mb-3 row">
                        <div class="col-md-3">
                            <select name="prefix" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">-- Filter by Kode Prefix --</option>
                                <?php foreach ($prefixes as $p): ?>
                                    <option value="<?= $p ?>" <?= ($selectedPrefix === $p) ? 'selected' : '' ?>><?= $p ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="functions/upload_excel_komputer.php" method="POST" enctype="multipart/form-data">
                                <div class="input-group">
                                    <input type="file" name="file_excel" class="form-control form-control-sm" required>
                                    <button type="submit" name="upload" class="btn btn-sm btn-info">Import Excel</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-komputer"><i class="mdi mdi-plus-circle-outline"></i> Create New</button>
                            <a href="export_komputer.php" class="btn btn-sm btn-dark mb-2">Export</a>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        Total Komputer dengan prefix <?= $selectedPrefix ? "<strong>$selectedPrefix</strong>" : "<em>(semua)</em>" ?>:
                        <strong><?= $total_qty ?></strong> unit
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane show active" id="small-table-preview">
                            <div class="table-responsive-sm">
                                <table style="font-size: 12px;" id="scroll-horizontal-datatable" class="table table-sm w-100 nowrap">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Kode Assets</th>
                                            <th>Nama Assets</th>
                                            <th>Tanggal Pembelian</th>
                                            <th>User</th>
                                            <th>IP</th>
                                            <th>Spec</th>
                                            <th>Lokasi</th>
                                            <th>Qty</th>
                                            <th>Desc</th>
                                            <th>Detail</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($all as $data): ?>
                                            <tr>
                                                <td><?= $data['kode_assets_kom'] ?></td>
                                                <td><?= $data['nama_assets_kom'] ?></td>
                                                <td><?= $data['tgl_pembelian_kom'] ?></td>
                                                <td><?= $data['user_kom'] ?></td>
                                                <td><?= $data['ip_kom'] ?></td>
                                                <td><?= $data['spec_kom'] ?></td>
                                                <td><i class="mdi mdi-map-marker-outline text-info"></i> <?= $data['lokasi_kom'] ?></td>
                                                <td><?= $data['qty_kom'] ?></td>
                                                <td><?= $data['desc_kom'] ?></td>
                                                <td>
                                                    <a href="detail_komputer.php?kode_assets_kom=<?= $data['kode_assets_kom'] ?>" class="action-icon">
                                                        <i class="dripicons-preview text-warning"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="edit_komputer.php?kode_assets_kom=<?= $data['kode_assets_kom'] ?>" class="action-icon">
                                                        <i class="mdi mdi-pencil text-primary"></i>
                                                    </a>
                                                    <a href="functions/function_komputer.php?hapus=<?= $data['kode_assets_kom'] ?>" onclick="return confirm('Apa anda yakin ingin menghapus ?')" class="action-icon">
                                                        <i class="mdi mdi-delete text-danger"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Modal Tambah Data Komputer -->
            <div class="modal fade" id="add-komputer" tabindex="-1" role="dialog" aria-labelledby="NewTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Create New Data</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="functions/function_komputer.php" method="POST" class="p-2">
                                <div class="row">
                                    <?php
                                    $fields = [
                                        ['kode_assets_kom', 'Kode Assets'],
                                        ['nama_assets_kom', 'Nama Assets'],
                                        ['tgl_pembelian_kom', 'Tanggal Perakitan'],
                                        ['user_kom', 'User'],
                                        ['ip_kom', 'IP'],
                                        ['spec_kom', 'Spec'],
                                        ['lokasi_kom', 'Lokasi'],
                                        ['qty_kom', 'Qty'],
                                        ['desc_kom', 'Desc']
                                    ];
                                    foreach ($fields as $field):
                                    ?>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label"><?= $field[1] ?></label>
                                            <input type="text" name="<?= $field[0] ?>" class="form-control">
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan_kom" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <input type="hidden" name="add">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </div>
</div>

<?php
include_once 'layouts/footer.php';
?>