<?php
include_once 'layouts/header_detail.php';
require 'functions/function_komputer.php';

// 1. Logika Filter Periode
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

$where_clause = "";
if (!empty($filter_bulan) && !empty($filter_tahun)) {
    // Filter berdasarkan bulan dan tahun
    $where_clause = " WHERE MONTH(p.tanggal_perbaikan) = '$filter_bulan' AND YEAR(p.tanggal_perbaikan) = '$filter_tahun'";
}

// 2. Query Data dengan Filter
$sql = "SELECT p.*, k.nama_assets_kom 
        FROM tb_perbaikan p 
        JOIN tb_komputer k ON p.kode_assets_kom = k.kode_assets_kom 
        $where_clause
        ORDER BY p.tanggal_perbaikan DESC";
$result = mysqli_query($conn, $sql);
$data_perbaikan = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Mendapatkan daftar tahun dari database untuk pilihan filter
$list_tahun = mysqli_query($conn, "SELECT DISTINCT YEAR(tanggal_perbaikan) as tahun FROM tb_perbaikan ORDER BY tahun DESC");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item">Komputer</li>
                        <li class="breadcrumb-item active">Data Perbaikan</li>
                    </ol>
                </div>
                <a href="index.php">
                    <h4 class="page-title">
                        <i class="uil-arrow-circle-left text-info"></i> Back to Dashboard
                    </h4>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3 text-center">Seluruh Riwayat Perbaikan Asset</h4>
                    <hr>

                    <form method="GET" action="" class="row g-2 mb-4 align-items-end justify-content-center">
                        <div class="col-auto">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select form-select-sm">
                                <option value="">-- Pilih Bulan --</option>
                                <?php
                                $bulan_indo = [
                                    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                    7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                ];
                                foreach ($bulan_indo as $num => $nama): ?>
                                    <option value="<?= $num ?>" <?= $filter_bulan == $num ? 'selected' : '' ?>><?= $nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select form-select-sm">
                                <option value="">-- Pilih Tahun --</option>
                                <?php while ($t = mysqli_fetch_assoc($list_tahun)): ?>
                                    <option value="<?= $t['tahun'] ?>" <?= $filter_tahun == $t['tahun'] ? 'selected' : '' ?>><?= $t['tahun'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary px-3">Filter</button>
                            <a href="data_perbaikan.php" class="btn btn-sm btn-secondary px-3">Reset</a>
                        </div>
                    </form>

                    <div class="table-responsive" style="font-size:13px;">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="basic-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Kode Asset</th>
                                    <th>Nama Asset</th>
                                    <th>Deskripsi Perbaikan</th>
                                    <th width="120">Tanggal</th>
                                    <th width="120">Status</th>
                                    <th width="100" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data_perbaikan)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data perbaikan pada periode ini.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; foreach ($data_perbaikan as $r): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><strong><?= htmlspecialchars($r['kode_assets_kom']); ?></strong></td>
                                            <td><?= htmlspecialchars($r['nama_assets_kom']); ?></td>
                                            <td><?= htmlspecialchars($r['deskripsi_perbaikan']); ?></td>
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
                                            <td class="text-center">
                                                <a href="edit_komputer.php?kode_assets_kom=<?= $r['kode_assets_kom']; ?>" class="action-icon text-primary me-2"> 
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="hapusRiwayat('<?= $r['id_perbaikan_kom'] ?>')" class="action-icon text-danger"> 
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
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
</div>

<script>
    function hapusRiwayat(id) {
        Swal.fire({
            title: 'Yakin hapus data ini?',
            text: 'Data perbaikan akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fa5c7c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = `functions/function_komputer.php?hapus1=${id}&redirect=data_perbaikan`;
            }
        });
    }
</script>

<?php if (isset($_GET['status'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data perbaikan telah dihapus',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>

<?php include_once 'layouts/footer_detail.php'; ?>