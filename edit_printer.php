<?php 
    // Nyalakan laporan error supaya kelihatan salahnya di mana
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include_once 'layouts/header_detail.php';
    require 'functions/function_printer.php';

    // 1. Cek apakah ID ada di URL
    if (!isset($_GET['id_printer']) || empty($_GET['id_printer'])) {
        die("Error: ID Printer tidak ditemukan di URL.");
    }

    $id_printer = $_GET['id_printer'];
    
    // 2. Ambil data
    $data = getPrinterById($id_printer);
    $jenisList = getAllJenisPrinter();

    // 3. Cek apakah data berhasil ditarik dari database
    if (!$data) {
        die("Error: Data dengan ID $id_printer tidak ditemukan di database.");
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Printer</li>
                        <li class="breadcrumb-item"><a href="data_printer.php">Data Printer</a></li>
                        <li class="breadcrumb-item active">Edit Printer</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Printer: <?= htmlspecialchars($data['ip_printer']) ?></h4>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <form action="functions/function_printer.php" method="POST">
                        <input type="hidden" name="id_printer" value="<?= $data['id_printer'] ?>">
                        <input type="hidden" name="tanggal_printer" value="<?= $data['tanggal_printer'] ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Printer</label>
                                <select name="jenis_printer" class="form-select">
                                    <?php foreach ($jenisList as $j) : ?>
                                        <option value="<?= $j['id_jenis'] ?>" <?= ($j['id_jenis'] == $data['id_jenis']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($j['nama_jenis']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">IP Address</label>
                                <input type="text" name="ip_printer" class="form-control" value="<?= htmlspecialchars($data['ip_printer']) ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="lokasi_printer" class="form-control" value="<?= htmlspecialchars($data['lokasi_printer']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status_printer" class="form-select">
                                    <option value="1" <?= ($data['status_printer'] == 1) ? 'selected' : '' ?>>Aktif</option>
                                    <option value="0" <?= ($data['status_printer'] == 0) ? 'selected' : '' ?>>Idle</option>
                                    <option value="2" <?= ($data['status_printer'] == 2) ? 'selected' : '' ?>>Off</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-end">
                            <input type="hidden" name="edit" value="1">
                            <button type="submit" class="btn btn-primary">Update Data</button>
                            <a href="data_printer.php" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layouts/footer_detail.php'; ?>