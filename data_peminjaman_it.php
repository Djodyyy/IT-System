<?php
include_once 'layouts/header.php';
require 'functions/function_peminjaman_it.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item">Peminjaman</li>
                        <li class="breadcrumb-item active">Data Peminjaman IT</li>
                    </ol>
                </div>
                <h4 class="page-title"><i class="uil-arrow-circle-right text-info"></i> Peminjaman Alat IT</h4>
            </div>
        </div>
    </div>

    <?php
    $all = getAllPeminjaman();

    $totalDipinjam = array_sum(array_map(function($item) {
        return $item['status_peminjaman'] === 'Dipinjam' ? 1 : 0;
    }, $all));

    $totalDikembalikan = array_sum(array_map(function($item) {
        return $item['status_peminjaman'] === 'Dikembalikan' ? 1 : 0;
    }, $all));
    ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-center">TABEL PEMINJAMAN ALAT IT</h4>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <strong>Total Dipinjam:</strong> <?= $totalDipinjam ?> | 
                                <strong>Total Dikembalikan:</strong> <?= $totalDikembalikan ?>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-peminjaman">
                                <i class="mdi mdi-plus-circle-outline"></i> Tambah Peminjaman
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive-sm">
                        <table style="font-size: 12px;" id="scroll-horizontal-datatable" class="table table-sm table-bordered nowrap w-100">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peminjam</th>
                                    <th>Nama Barang</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php $no=1; foreach ($all as $data): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($data['nama_peminjam']) ?></td>
                                        <td><?= htmlspecialchars($data['nama_barang']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($data['tgl_pinjam'])) ?></td>
                                        <td><?= $data['tgl_kembali'] ? date('d/m/Y', strtotime($data['tgl_kembali'])) : '-' ?></td>
                                        <td>
                                            <?php if ($data['status_peminjaman'] === 'Dipinjam'): ?>
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Dikembalikan</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($data['keterangan'] ?? '-') ?></td>
                                        <td>
                                            <?php if ($data['status_peminjaman'] === 'Dipinjam'): ?>
                                                <a href="functions/function_peminjaman_it.php?kembali=<?= $data['id_peminjaman'] ?>" class="action-icon" title="Kembalikan">
                                                    <i class="mdi mdi-keyboard-return text-success"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="functions/function_peminjaman_it.php?hapus=<?= $data['id_peminjaman'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="action-icon" title="Hapus">
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

            <!-- Modal Tambah Peminjaman -->
            <div class="modal fade" id="add-peminjaman" tabindex="-1" role="dialog" aria-labelledby="NewTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Peminjaman Alat IT</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="functions/function_peminjaman_it.php" method="POST" class="p-2">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Peminjam</label>
                                        <input type="text" name="nama_peminjam" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pilih Barang</label>
                                        <select name="id_barang" class="form-select" required>
                                            <option value="">-- Pilih Barang --</option>
                                            <?php
                                            $barang = mysqli_query($conn, "SELECT * FROM tb_barang_it WHERE stok > 0 ORDER BY nama_barang ASC");
                                            while ($b = mysqli_fetch_assoc($barang)) {
                                                echo "<option value='{$b['id_barang']}'>{$b['nama_barang']} (Stok: {$b['stok']})</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Pinjam</label>
                                        <input type="date" name="tgl_pinjam" class="form-control" required>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <input type="hidden" name="add">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'success_add'): ?>
                Swal.fire({ title: 'Berhasil!', text: 'Peminjaman berhasil ditambahkan', icon: 'success', confirmButtonText: 'OK' });
            <?php elseif ($_GET['status'] == 'success_return'): ?>
                Swal.fire({ title: 'Barang Dikembalikan!', text: 'Status telah diperbarui', icon: 'success', confirmButtonText: 'OK' });
            <?php elseif ($_GET['status'] == 'success_delete'): ?>
                Swal.fire({ title: 'Dihapus!', text: 'Data peminjaman berhasil dihapus', icon: 'success', confirmButtonText: 'OK' });
            <?php elseif ($_GET['status'] == 'error'): ?>
                Swal.fire({ title: 'Gagal!', text: 'Terjadi kesalahan pada proses', icon: 'error', confirmButtonText: 'OK' });
            <?php endif; ?>
        <?php endif; ?>
    });
</script>

<?php include_once 'layouts/footer.php'; ?>
