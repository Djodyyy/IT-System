<?php
require 'functions/function_komputer.php';
require 'vendor/autoload.php'; // pastikan ini benar sesuai lokasi autoload.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$data = getData(); // ambil semua data komputer

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'Kode Assets');
$sheet->setCellValue('B1', 'Nama Assets');
$sheet->setCellValue('C1', 'Tanggal Pembelian');
$sheet->setCellValue('D1', 'User');
$sheet->setCellValue('E1', 'IP');
$sheet->setCellValue('F1', 'Spec');
$sheet->setCellValue('G1', 'Lokasi');
$sheet->setCellValue('H1', 'Qty');
$sheet->setCellValue('I1', 'Desc');
$sheet->setCellValue('J1', 'Keterangan');

// Isi data mulai dari baris ke-2
$row = 2;
foreach ($data as $d) {
    $sheet->setCellValue("A$row", $d['kode_assets_kom']);
    $sheet->setCellValue("B$row", $d['nama_assets_kom']);
    $sheet->setCellValue("C$row", $d['tgl_pembelian_kom']);
    $sheet->setCellValue("D$row", $d['user_kom']);
    $sheet->setCellValue("E$row", $d['ip_kom']);
    $sheet->setCellValue("F$row", $d['spec_kom']);
    $sheet->setCellValue("G$row", $d['lokasi_kom']);
    $sheet->setCellValue("H$row", $d['qty_kom']);
    $sheet->setCellValue("I$row", $d['desc_kom']);
    $sheet->setCellValue("J$row", $d['keterangan_kom']);
    $row++;
}

// Set header untuk download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_komputer.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
