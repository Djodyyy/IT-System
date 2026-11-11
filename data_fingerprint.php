<?php
include_once 'layouts/header.php';
require 'functions/function_fingerprint.php';

$data    = getAllFingerprint();
$gedung  = getAllGedung();

/* ================= SUMMARY & GROUPING LOGIC ================= */
$summary = [];
$grouped_data = []; // Untuk menampung data per gedung
$total_aktif = 0;

foreach ($data as $d) {
    // Gunakan nama_gedung sebagai key utama
    $g_name = $d['nama_gedung'] ?? $d['kode_gedung'];

    // Logic untuk Summary Cards (Hanya yang aktif)
    if (!isset($summary[$g_name])) {
        $summary[$g_name] = ['IN' => 0, 'OUT' => 0, 'total' => 0];
    }

    // Logic untuk grouping Accordion (Semua data masuk sini)
    if (!isset($grouped_data[$g_name])) {
        $grouped_data[$g_name] = [];
    }
    $grouped_data[$g_name][] = $d;

    if ($d['status'] == 'aktif') {
        $summary[$g_name][$d['fungsi']]++;
        $summary[$g_name]['total']++;
        $total_aktif++;
    }
}
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="page-title-box d-flex align-items-center justify-content-between py-3">
        <h4 class="page-title mb-0">
            <i class="mdi mdi-fingerprint text-primary me-2"></i>Manajemen Perangkat Fingerprint
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item active">Total Aktif: <strong><?= $total_aktif ?> Unit</strong></li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <?php foreach ($summary as $nama_gedung => $s): ?>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm-wrap bg-soft-info text-info p-2 rounded-circle me-3">
                                <i class="mdi mdi-office-building mdi-24px"></i>
                            </div>
                            <h6 class="card-title mb-0 text-truncate" title="<?= $nama_gedung ?>"><?= $nama_gedung ?></h6>
                        </div>
                        <div class="row text-center mt-3">
                            <div class="col-6 border-end">
                                <p class="text-muted mb-1 small uppercase">Mesin IN</p>
                                <h4 class="mb-0 text-primary"><?= $s['IN'] ?></h4>
                            </div>
                            <div class="col-6">
                                <p class="text-muted mb-1 small">Mesin OUT</p>
                                <h4 class="mb-0 text-secondary"><?= $s['OUT'] ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">Daftar Inventaris (Klik Nama Gedung)</h5>
            <button class="btn btn-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addFingerprint">
                <i class="mdi mdi-plus-circle me-1"></i> Tambah Perangkat
            </button>
        </div>
        <div class="card-body p-0">
            <div class="accordion accordion-flush" id="accordionGedung">
                <?php $i = 0;
                foreach ($grouped_data as $nama_gedung => $list_perangkat): $i++; ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading<?= $i ?>">
                            <button class="accordion-button collapsed fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $i ?>">
                                <i class="mdi mdi-map-marker-radius text-primary me-2"></i>
                                <?= $nama_gedung ?>
                                <span class="badge bg-soft-primary text-primary ms-2 rounded-pill font-size-11">
                                    <?= count($list_perangkat) ?> Unit
                                </span>
                            </button>
                        </h2>
                        <div id="flush-collapse<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#accordionGedung">
                            <div class="accordion-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center ps-3" width="80">Kode</th>
                                                <th>Nama Perangkat</th>
                                                <th>IP Address</th>
                                                <th class="text-center">Fungsi</th>
                                                <th class="text-center">Status</th>
                                                <th>Keterangan</th>
                                                <th class="text-center" width="120">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($list_perangkat as $d): ?>
                                                <tr>
                                                    <td class="text-center fw-bold text-muted ps-3"><?= $d['kode_fingerprint'] ?></td>
                                                    <td class="fw-medium"><?= $d['nama_fingerprint'] ?></td>
                                                    <td><code><?= $d['ip_address'] ?: '-' ?></code></td>
                                                    <td class="text-center">
                                                        <span class="badge <?= $d['fungsi'] == 'IN' ? 'bg-soft-primary text-primary' : 'bg-soft-secondary text-secondary' ?> px-2 py-1">
                                                            <i class="mdi <?= $d['fungsi'] == 'IN' ? 'mdi-login' : 'mdi-logout' ?> me-1"></i><?= $d['fungsi'] ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $statusClass = 'bg-success';
                                                        if ($d['status'] == 'rusak') $statusClass = 'bg-danger';
                                                        if ($d['status'] == 'nonaktif') $statusClass = 'bg-warning text-dark';
                                                        ?>
                                                        <span class="badge rounded-pill <?= $statusClass ?> shadow-sm" style="min-width: 70px;">
                                                            <?= ucfirst($d['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-muted small"><?= $d['keterangan'] ?: '-' ?></td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-outline-info"
                                                                title="Edit"
                                                                onclick='setEdit(<?= json_encode($d) ?>)'
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editFingerprint">
                                                                <i class="mdi mdi-pencil"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger"
                                                                title="Hapus"
                                                                onclick="hapus('<?= $d['kode_fingerprint'] ?>')">
                                                                <i class="mdi mdi-trash-can-outline"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addFingerprint" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="functions/function_fingerprint.php" method="POST">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle me-2"></i>Registrasi Fingerprint Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Nama Perangkat</label>
                            <input type="text" name="nama_fingerprint" class="form-control" placeholder="Contoh: Gedung" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">IP Address</label>
                            <input type="text" name="ip_address" class="form-control" placeholder="192.168.x.x" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Gedung</label>
                            <select name="id_gedung" class="form-select" required>
                                <option value="">Pilih Gedung...</option>
                                <?php foreach ($gedung as $g): ?>
                                    <option value="<?= $g['id_gedung'] ?>"><?= $g['nama_gedung'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Fungsi</label>
                            <select name="fungsi" class="form-select">
                                <option value="IN">IN (Masuk)</option>
                                <option value="OUT">OUT (Keluar)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status Awal</label>
                            <select name="status" class="form-select">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Keterangan Lokasi / Catatan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan detail spesifik lokasi..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <input type="hidden" name="tambah">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4">Simpan Perangkat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editFingerprint" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="functions/function_fingerprint.php" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-pencil-box-multiple me-2"></i>Update Data Fingerprint</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="kode_fingerprint" id="e_kode">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Nama Fingerprint</label>
                            <input type="text" name="nama_fingerprint" id="e_nama" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">IP Address</label>
                            <input type="text" name="ip_address" id="e_ip" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Gedung</label>
                            <select name="id_gedung" id="e_gedung" class="form-select" required>
                                <?php foreach ($gedung as $g): ?>
                                    <option value="<?= $g['id_gedung'] ?>"><?= $g['nama_gedung'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Fungsi</label>
                            <select name="fungsi" id="e_fungsi" class="form-select">
                                <option value="IN">IN</option>
                                <option value="OUT">OUT</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" id="e_status" class="form-select">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Keterangan</label>
                            <textarea name="keterangan" id="e_ket" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <input type="hidden" name="edit">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
    .bg-soft-secondary { background-color: rgba(108, 117, 125, 0.1); }
    .avatar-sm-wrap { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
    .table thead th { font-weight: 600; text-transform: uppercase; font-size: 0.70rem; letter-spacing: 0.5px; border-top: none; }
    .card { border-radius: 12px; }
    .btn-group .btn { border-radius: 8px !important; margin: 0 2px; }
    .accordion-button:not(.collapsed) { background-color: #f8f9fa; color: #0d6efd; box-shadow: none; }
    .accordion-button:focus { box-shadow: none; }
</style>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script>
    function setEdit(d) {
        document.getElementById('e_kode').value = d.kode_fingerprint;
        document.getElementById('e_nama').value = d.nama_fingerprint;
        document.getElementById('e_ip').value = d.ip_address; // Tambahan IP
        document.getElementById('e_gedung').value = d.id_gedung;
        document.getElementById('e_fungsi').value = d.fungsi;
        document.getElementById('e_status').value = d.status;
        document.getElementById('e_ket').value = d.keterangan ?? '';
    }

    function hapus(kode) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus fingerprint ${kode}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(res => {
            if (res.isConfirmed) {
                window.location.href = `functions/function_fingerprint.php?hapus=${kode}`;
            }
        });
    }
</script>

<?php include_once 'layouts/footer.php'; ?>