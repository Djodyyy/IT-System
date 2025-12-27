<?php
require 'functions/function_komputer.php';

// Ambil semua data asli
$all = getData();

// Ambil parameter filter dari URL
$kategori = $_GET['kategori'] ?? '';
$dept     = $_GET['dept'] ?? '';
$tgl_awal = $_GET['tgl_awal'] ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';

// Jalankan logika filter yang sama dengan report_komputer.php
if ($kategori !== '' || $dept !== '' || ($tgl_awal !== '' && $tgl_akhir !== '')) {
    $all = array_filter($all, function($i) use ($kategori, $dept, $tgl_awal, $tgl_akhir) {
        $check = true;
        if ($kategori !== '' && $i['kategori_kom'] !== $kategori) $check = false;
        if ($dept !== '' && $i['dept_kom'] !== $dept) $check = false;
        if ($tgl_awal !== '' && $tgl_akhir !== '') {
            $tgl_beli = $i['tgl_pembelian_kom'];
            if ($tgl_beli < $tgl_awal || $tgl_beli > $tgl_akhir) $check = false;
        }
        return $check;
    });
}

// Set Header untuk Download Excel
$filename = "Report_Komputer_" . date('Ymd_His') . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
?>

<table border="1">
    <thead>
        <tr>
            <th colspan="10" style="font-size: 16px; font-weight: bold;">LAPORAN DATA INVENTARIS KOMPUTER</th>
        </tr>
        <tr>
            <th colspan="10">PT. METRO PEARL INDONESIA</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Kode Asset</th>
            <th>Nama Asset</th>
            <th>Dept</th>
            <th>User</th>
            <th>Tgl Pembelian</th>
            <th>Spesifikasi</th>
            <th>IP Address</th>
            <th>Lokasi</th>
            <th>Qty</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($all as $d): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $d['kode_assets_kom'] ?></td>
                <td><?= $d['nama_assets_kom'] ?></td>
                <td><?= $d['dept_kom'] ?></td>
                <td><?= $d['user_kom'] ?></td>
                <td><?= $d['tgl_pembelian_kom'] ?></td>
                <td><?= $d['spec_kom'] ?></td>
                <td><?= $d['ip_kom'] ?></td>
                <td><?= $d['lokasi_kom'] ?></td>
                <td><?= $d['qty_kom'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>