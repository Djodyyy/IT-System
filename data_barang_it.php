    <?php
    include_once 'layouts/header.php';
    require 'functions/function_device_it.php';

    $kategori = $_GET['kategori'] ?? '';
    $status   = $_GET['status']   ?? '';
    $search   = $_GET['search']   ?? '';

    $all = getAllDeviceIT();

    /* ===== FILTER ===== */
    if ($kategori !== '') {
        $all = array_filter($all, fn($i) => $i['kategori_device'] === $kategori);
    }

    if ($status !== '') {
        $all = array_filter($all, fn($i) => $i['status_device'] === $status);
    }

    if ($search !== '') {
        $all = array_filter($all, function ($i) use ($search) {
            return stripos($i['kode_device_it'], $search) !== false ||
                stripos($i['nama_device'], $search) !== false ||
                stripos($i['lokasi_device'], $search) !== false;
        });
    }

    $total_qty = array_sum(array_column($all, 'qty'));
    ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container-fluid">
        <div class="page-title-box">
            <h4 class="page-title">
                <i class="uil-arrow-circle-right text-info"></i> Data Device IT
            </h4>
        </div>

        <div class="card">
            <div class="card-body">

                <!-- FILTER -->
                <form method="GET" class="row mb-3 g-2">
                    <div class="col-md-3">
                        <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Kategori Device --</option>
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
                            <option value="">-- Status --</option>
                            <option value="aktif" <?= $status == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="rusak" <?= $status == 'rusak' ? 'selected' : '' ?>>Rusak</option>
                            <option value="dipinjam" <?= $status == 'dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari Kode, Nama, Lokasi"
                                value="<?= htmlspecialchars($search) ?>">
                            <button class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                            <?php if ($kategori || $status || $search): ?>
                                <a href="data_barang_it.php" class="btn btn-outline-secondary">Reset</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>

                <!-- INFO -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="alert alert-info py-1 mb-0">
                            Total Device : <strong><?= $total_qty ?></strong> unit
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addDevice">
                            <i class="mdi mdi-plus-circle-outline"></i> Create New
                        </button>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-sm table-striped" style="font-size:12px">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Kategori</th>
                                <th>Nama</th>
                                <th>Lokasi</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th width="90">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($all)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Data tidak ditemukan</td>
                                </tr>
                                <?php else: foreach ($all as $d): ?>
                                    <tr>
                                        <td><?= $d['kode_device_it'] ?></td>
                                        <td><?= $d['kategori_device'] ?></td>
                                        <td><?= $d['nama_device'] ?></td>
                                        <td><?= $d['lokasi_device'] ?></td>
                                        <td><?= $d['qty'] ?></td>
                                        <td>
                                            <span class="badge
                                        <?= $d['status_device'] == 'aktif' ? 'bg-success' : ($d['status_device'] == 'rusak' ? 'bg-danger' : 'bg-warning') ?>">
                                                <?= ucfirst($d['status_device']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="action-icon text-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editDevice"
                                                onclick='setEdit(<?= json_encode($d) ?>)'>
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <a href="#" class="action-icon text-danger"
                                                onclick="hapusData('<?= $d['kode_device_it'] ?>')">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                            <?php endforeach;
                            endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- ================= MODAL ADD ================= -->
    <div class="modal fade" id="addDevice">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="functions/function_device_it.php" method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Device IT</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row">
                        <div class="col-md-4 mb-3">
                            <label>Kategori Device</label>
                            <input type="text" name="kategori_device" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Nama Device</label>
                            <input type="text" name="nama_device" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Tanggal Pembelian</label>
                            <input type="date" name="tgl_pembelian" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi_device" class="form-control">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Qty</label>
                            <input type="number" name="qty" class="form-control" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Status</label>
                            <select name="status_device" class="form-select">
                                <option value="aktif">Aktif</option>
                                <option value="rusak">Rusak</option>
                                <option value="dipinjam">Dipinjam</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="tambah">
                        <button class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL EDIT ================= -->
    <div class="modal fade" id="editDevice">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="functions/function_device_it.php" method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Device IT</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row">
                        <input type="hidden" name="kode_device_it" id="e_kode">

                        <div class="col-md-4 mb-3">
                            <label>Nama Device</label>
                            <input type="text" name="nama_device" id="e_nama" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Tanggal Pembelian</label>
                            <input type="date"
                                name="tgl_pembelian"
                                id="e_tgl"
                                class="form-control bg-light"
                                readonly>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi_device" id="e_lokasi" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Qty</label>
                            <input type="number" name="qty" id="e_qty" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Status</label>
                            <select name="status_device" id="e_status" class="form-select">
                                <option value="aktif">Aktif</option>
                                <option value="rusak">Rusak</option>
                                <option value="dipinjam">Dipinjam</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Keterangan</label>
                            <textarea name="keterangan" id="e_ket" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="edit">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setEdit(data) {
            document.getElementById('e_kode').value = data.kode_device_it;
            document.getElementById('e_nama').value = data.nama_device;
            document.getElementById('e_tgl').value = data.tgl_pembelian ?? '';
            document.getElementById('e_lokasi').value = data.lokasi_device;
            document.getElementById('e_qty').value = data.qty;
            document.getElementById('e_status').value = data.status_device;
            document.getElementById('e_ket').value = data.keterangan ?? '';
        }

        function hapusData(kode) {
            Swal.fire({
                title: 'Hapus data?',
                text: 'Data akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Hapus'
            }).then(res => {
                if (res.isConfirmed) {
                    window.location.href = `functions/function_device_it.php?hapus=${kode}`;
                }
            });
        }
    </script>

    <?php include_once 'layouts/footer.php'; ?>