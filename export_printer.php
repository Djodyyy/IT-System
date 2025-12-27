<?php
require_once 'functions/function_printer.php';

// Memberitahu browser bahwa ini adalah file Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Data_Printer_" . date('Y-m-d') . ".xls");

$allPrinter = getAllPrinter();
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        .str { mso-number-format:\@; } /* Format text agar IP Address tidak berubah jadi angka/tanggal */
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #f2f2f2; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        .text-center { text-align: center; }
        .title { text-align: center; font-weight: bold; font-size: 16px; }
    </style>
</head>
<body>

    <div class="title">LAPORAN DATA INVENTARIS PRINTER</div>
    <div class="title">PT. METRO PEARL INDONESIA</div>
    <div style="text-align: center; margin-bottom: 20px;">Tanggal Export: <?= date('d-m-Y H:i') ?></div>

    <table>
        <thead>
            <tr>
                <th style="background-color: #333; color: #fff;">No</th>
                <th style="background-color: #333; color: #fff;">Jenis Printer</th>
                <th style="background-color: #333; color: #fff;">IP Address</th>
                <th style="background-color: #333; color: #fff;">Lokasi</th>
                <th style="background-color: #333; color: #fff;">Status</th>
                <th style="background-color: #333; color: #fff;">Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($allPrinter)): ?>
                <?php $no = 1; foreach ($allPrinter as $data): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($data['nama_jenis']) ?></td>
                        <td class="str"><?= htmlspecialchars($data['ip_printer']) ?></td>
                        <td><?= htmlspecialchars($data['lokasi_printer']) ?></td>
                        <td>
                            <?php
                            switch ($data['status_printer']) {
                                case 1: echo 'Aktif'; break;
                                case 0: echo 'Idle'; break;
                                case 2: echo 'Off'; break;
                                default: echo 'Tidak Diketahui';
                            }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($data['tanggal_printer']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data printer</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <table style="border: none;">
        <tr style="border: none;">
            <td colspan="4" style="border: none;"></td>
            <td colspan="2" style="border: none; text-align: center;">
                Purwakarta, <?= date('d F Y') ?><br>
                Dicetak oleh,<br><br><br><br>
                <strong>( IT Department )</strong>
            </td>
        </tr>
    </table>

</body>
</html>