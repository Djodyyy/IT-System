<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// --- 🔹 KONFIGURASI DATABASE MANUAL ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_it"; // ubah sesuai nama database lo

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// --- 🔹 QUERY DATA PERBAIKAN (AMBIL NAMA USER DARI tb_komputer.user_kom) ---
$sql = "SELECT 
            a.kode_assets_kom,
            b.nama_assets_kom,
            b.user_kom AS nama_user,
            a.deskripsi_perbaikan,
            a.tanggal_perbaikan,
            a.status_perbaikan
        FROM tb_perbaikan a
        LEFT JOIN tb_komputer b ON a.kode_assets_kom = b.kode_assets_kom
        ORDER BY a.tanggal_perbaikan DESC";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

if (empty($data)) {
    die("Tidak ada data perbaikan ditemukan. Pastikan tabel tb_perbaikan berisi data.");
}

// --- 🔹 BUAT FILE EXCEL ---
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul laporan
$sheet->setCellValue('A1', 'LAPORAN RIWAYAT PERBAIKAN KOMPUTER');
$sheet->mergeCells('A1:G1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->setCellValue('A2', 'Tanggal Export: ' . date('d/m/Y H:i'));

// Header kolom
$headers = ['No', 'Kode Aset', 'Nama Aset', 'User', 'Deskripsi Perbaikan', 'Tanggal Perbaikan', 'Status Perbaikan'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '4', $header);
    $col++;
}

$sheet->getStyle('A4:G4')->getFont()->setBold(true);
$sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A4:G4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Isi data
$row = 5;
$no = 1;
foreach ($data as $d) {
    $sheet->setCellValue("A$row", $no++);
    $sheet->setCellValue("B$row", $d['kode_assets_kom']);
    $sheet->setCellValue("C$row", $d['nama_assets_kom'] ?? '-');
    $sheet->setCellValue("D$row", $d['nama_user'] ?? '-');
    $sheet->setCellValue("E$row", $d['deskripsi_perbaikan']);
    $sheet->setCellValue("F$row", date('d/m/Y', strtotime($d['tanggal_perbaikan'])));
    $sheet->setCellValue("G$row", ucfirst($d['status_perbaikan']));
    $row++;
}

// Auto width semua kolom
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Border seluruh data
$sheet->getStyle("A4:G" . ($row - 1))
    ->getBorders()->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);

// Output Excel
$filename = 'Laporan_Riwayat_Perbaikan_Komputer_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
