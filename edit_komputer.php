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
                        <li class="breadcrumb-item active">Edit Komputer</li>
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
        <!-- FORM EDIT KOMPUTER -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="functions/function_komputer.php" method="POST">
                        <h4 class="header-title mb-3 text-center">Edit Data Komputer</h4>
                        <hr>

                        <table class="w-100">
                            <tbody>
                                <?php
                                $fields = [
                                    'Kode Assets'        => 'kode_assets_kom',
                                    'Nama Assets'        => 'nama_assets_kom',
                                    'Tanggal Pembelian'  => 'tgl_pembelian_kom',
                                    'User'               => 'user_kom',
                                    'IP'                 => 'ip_kom',
                                    'Spec'               => 'spec_kom',
                                    'Lokasi'             => 'lokasi_kom',
                                    'Qty'                => 'qty_kom',
                                    'Desc'               => 'desc_kom',
                                    'Keterangan'         => 'keterangan_kom'
                                ];

                                foreach ($fields as $label => $key):
                                ?>
                                    <tr class="align-top">
                                        <th width="150"><?= $label ?></th>
                                        <th width="10">:</th>
                                        <td class="pb-2">
                                            <?php if (in_array($key, ['spec_kom', 'desc_kom', 'keterangan_kom'])): ?>
                                                <textarea name="<?= $key ?>" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($data[$key]) ?></textarea>

                                            <?php elseif ($key === 'tgl_pembelian_kom'): ?>
                                                <input type="date" name="<?= $key ?>" class="form-control form-control-sm"
                                                    value="<?= !empty($data[$key]) ? htmlspecialchars($data[$key]) : '' ?>">

                                            <?php elseif ($key === 'kode_assets_kom'): ?>
                                                <input type="text" name="<?= $key ?>" class="form-control form-control-sm"
                                                    value="<?= htmlspecialchars($data[$key]) ?>" readonly>

                                            <?php else: ?>
                                                <input type="text" name="<?= $key ?>" class="form-control form-control-sm"
                                                    value="<?= htmlspecialchars($data[$key]) ?>">
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="text-end mt-3">
                            <button type="submit" name="edit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIWAYAT PERBAIKAN -->
        <div class="col-lg-6" style="font-size:11px;">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3 text-center">Riwayat Perbaikan</h4>
                    <hr>

                    <button class="btn btn-sm btn-success mb-2"
                        data-bs-toggle="modal" data-bs-target="#add-perbaikan">
                        <i class="mdi mdi-plus-circle-outline"></i> Create New
                    </button>

                    <table class="table table-striped w-100">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th width="80">Tanggal</th>
                                <th width="80">Status</th>
                                <th width="50">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (getPerbaikan($kode_assets_kom) as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['deskripsi_perbaikan']) ?></td>
                                    <td>
                                        <?= !empty($r['tanggal_perbaikan'])
                                            ? date('d/m/Y', strtotime($r['tanggal_perbaikan']))
                                            : '-' ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $r['status_perbaikan'] === 'perbaikan' ? 'bg-warning' : 'bg-danger' ?>">
                                            <?= htmlspecialchars($r['status_perbaikan']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" onclick="hapusPerbaikan('<?= $r['id_perbaikan_kom'] ?>')">
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

<!-- MODAL TAMBAH PERBAIKAN -->
<div class="modal fade" id="add-perbaikan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="functions/function_komputer.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Perbaikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <input type="text" name="deskripsi_perbaikan" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal_perbaikan" class="form-control" value="<?= date('Y-m-d') ?>">
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

<!-- SCRIPT -->
<script>
    const kodeAssetsKom = "<?= htmlspecialchars($kode_assets_kom) ?>";

    function hapusPerbaikan(id) {
        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Data tidak bisa dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href =
                    `functions/function_komputer.php?hapus1=${id}&kode_assets_kom=${kodeAssetsKom}`;
            }
        });
    }
</script>
<?php if (isset($_GET['success'])): ?>
    <?php
    if ($_GET['success'] === 'edit') {
        $msg = 'Data berhasil diperbarui';
    } elseif ($_GET['success'] === 'add') {
        $msg = 'Perbaikan berhasil ditambahkan';
    } elseif ($_GET['success'] === 'delete') {
        $msg = 'Perbaikan berhasil dihapus';
    } else {
        $msg = 'Berhasil';
    }
    ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: <?= json_encode($msg) ?>,
            timer: 2000,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>


<?php include_once 'layouts/footer_detail.php'; ?>