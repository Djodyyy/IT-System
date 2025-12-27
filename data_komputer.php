<?php
include_once 'layouts/header.php';
require 'functions/function_komputer.php';


$kategori = $_GET['kategori'] ?? '';
$dept     = $_GET['dept']     ?? '';
$search   = $_GET['search']   ?? '';


$all = [];


if ($kategori !== '' || $dept !== '' || $search !== '') {
    $all = getData();

    
    if ($kategori !== '') {
        $all = array_filter($all, fn($i) => $i['kategori_kom'] === $kategori);
    }

    
    if ($dept !== '') {
        $all = array_filter($all, fn($i) => $i['dept_kom'] === $dept);
    }


    if ($search !== '') {
        $all = array_filter($all, function($i) use ($search) {
            return stripos($i['kode_assets_kom'], $search) !== false || 
                   stripos($i['nama_assets_kom'], $search) !== false || 
                   stripos($i['user_kom'], $search) !== false || 
                   stripos($i['ip_kom'], $search) !== false;
        });
    }
}

$total_qty = array_sum(array_column($all, 'qty_kom'));


$depts = [
    'OFC' => [
        'GMO' => 'General Manager Office', 'ACC' => 'Accounting', 'PAY' => 'Payroll',
        'EXIM' => 'Export Import', 'PUR' => 'Purchasing', 'BUS' => 'Business',
        'IT' => 'Information Technology', 'CR' => 'Customer Relation', 'HR' => 'Human Resource',
        'DEV' => 'Development', 'COM' => 'Commerce', 'TCOM' => 'Tech Commerce',
        'LEAN' => 'Lean Management', 'PPIC' => 'PPIC', 'BC' => 'Bea Cukai',
        'SEC' => 'Security', 'CLN' => 'Clinic', 'LAB' => 'Laboratory', 'MTN' => 'Maintenance'
    ],
    'PRD' => [
        'OS' => 'Outsole', 'A2' => 'Assembly 2', 'A3' => 'Assembly 3', 'A4' => 'Assembly 4',
        'A5' => 'Assembly 5', 'PRT' => 'Printing', 'STF' => 'Stockfit', 'B3' => 'B3',
        'QMS' => 'QMS', 'WH' => 'Warehouse', 'CHEM' => 'Chemicals'
    ]
];
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="page-title-box">
        <h4 class="page-title"><i class="uil-arrow-circle-right text-info"></i> Data Komputer</h4>
    </div>

    <div class="card">
        <div class="card-body">

            <form method="GET" class="row mb-3 g-2">
                <div class="col-md-2">
                    <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Kategori --</option>
                        <option value="OFC" <?= $kategori == 'OFC' ? 'selected' : '' ?>>Office</option>
                        <option value="PRD" <?= $kategori == 'PRD' ? 'selected' : '' ?>>Produksi</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="dept" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Department --</option>
                        <?php
                        if ($kategori && isset($depts[$kategori])) {
                            foreach ($depts[$kategori] as $kode => $nama) {
                                $sel = ($dept == $kode) ? 'selected' : '';
                                echo "<option value='$kode' $sel>$nama</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari Kode, Nama, User, atau IP..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i> Cari</button>
                        <?php if($search || $kategori || $dept): ?>
                            <a href="data_komputer.php" class="btn btn-outline-secondary">Reset</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="alert alert-info mb-0 py-1">
                        Total Komputer : <strong><?= $total_qty ?></strong> unit
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#add-komputer">
                        <i class="mdi mdi-plus-circle-outline"></i> Create New
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped" id="table-komputer" style="font-size:12px">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Dept</th>
                            <th>Nama</th>
                            <th>User</th>
                            <th>IP</th>
                            <th>Lokasi</th>
                            <th>Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($all)): ?>
                            <tr>
                                <td colspan="9" class="text-center">
                                    <?php if($kategori || $dept || $search): ?>
                                        Data tidak ditemukan.
                                    <?php else: ?>
                                        Silahkan pilih <strong>Kategori</strong> atau gunakan <strong>Pencarian</strong> untuk menampilkan data.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($all as $d): ?>
                                <tr>
                                    <td><?= $d['kode_assets_kom'] ?></td>
                                    <td><?= $d['kategori_kom'] ?></td>
                                    <td><?= $d['dept_kom'] ?></td>
                                    <td><?= $d['nama_assets_kom'] ?></td>
                                    <td><?= $d['user_kom'] ?></td>
                                    <td><?= $d['ip_kom'] ?></td>
                                    <td><?= $d['lokasi_kom'] ?></td>
                                    <td><?= $d['qty_kom'] ?></td>
                                    <td>
                                        <a href="detail_komputer.php?kode_assets_kom=<?= $d['kode_assets_kom'] ?>" class="action-icon">
                                            <i class="mdi mdi-eye text-warning"></i>
                                        </a>
                                        <a href="#" class="action-icon" onclick="editData('<?= $d['kode_assets_kom'] ?>')">
                                            <i class="mdi mdi-pencil text-primary"></i>
                                        </a>
                                        <a href="#" class="action-icon" onclick="hapusData('<?= $d['kode_assets_kom'] ?>')">
                                            <i class="mdi mdi-delete text-danger"></i>
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

<div class="modal fade" id="add-komputer">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="formAddKomputer" action="functions/function_komputer.php" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Komputer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-4 mb-3">
                        <label>Kategori</label>
                        <select name="kategori_kom" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="OFC">Office</option>
                            <option value="PRD">Produksi</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Department</label>
                        <select name="dept_kom" class="form-select" required>
                            <option value="">-- Pilih --</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Nama Assets</label>
                        <input type="text" name="nama_assets_kom" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Tanggal Pembelian</label>
                        <input type="date" name="tgl_pembelian_kom" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>User</label>
                        <input type="text" name="user_kom" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>IP</label>
                        <input type="text" name="ip_kom" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Spec</label>
                        <input type="text" name="spec_kom" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi_kom" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Qty</label>
                        <input type="number" name="qty_kom" class="form-control" value="1">
                    </div>
                    <div class="col-md-10 mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan_kom" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="add">
                    <button type="button" class="btn btn-primary" onclick="simpanData()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function simpanData() {
    Swal.fire({
        title: 'Simpan data?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal'
    }).then((res) => {
        if (res.isConfirmed) { document.getElementById('formAddKomputer').submit(); }
    });
}

function hapusData(kode) {
    Swal.fire({
        title: 'Hapus data?',
        text: 'Data akan dihapus permanen',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Hapus'
    }).then((res) => {
        if (res.isConfirmed) { window.location.href = `functions/function_komputer.php?hapus=${kode}`; }
    });
}

function editData(kode) {
    window.location.href = `edit_komputer.php?kode_assets_kom=${kode}`;
}

const deptMap = <?= json_encode($depts) ?>;
const kategoriSelect = document.querySelector('[name="kategori_kom"]');
const deptSelect     = document.querySelector('[name="dept_kom"]');

kategoriSelect.addEventListener('change', function () {
    deptSelect.innerHTML = '<option value="">-- Pilih --</option>';
    if (deptMap[this.value]) {
        Object.entries(deptMap[this.value]).forEach(([kode, nama]) => {
            const opt = document.createElement('option');
            opt.value = kode;
            opt.textContent = `${kode} - ${nama}`;
            deptSelect.appendChild(opt);
        });
    }
});
</script>

<?php include_once 'layouts/footer.php'; ?>