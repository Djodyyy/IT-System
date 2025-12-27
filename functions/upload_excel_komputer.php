<?php
require_once __DIR__ . '/../vendor/autoload.php'; 
require_once 'koneksi.php';
require_once __DIR__ . '/function_komputer.php';  

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

if (isset($_POST['upload'])) {
    $file_excel = $_FILES['file_excel']['tmp_name'];

    if (!file_exists($file_excel)) {
        header("Location: ../data_komputer.php?success=0"); 
        exit;
    }

    try {
        $spreadsheet = IOFactory::load($file_excel);
        $sheet = $spreadsheet->getActiveSheet()->toArray();

        $sukses = 0;
        $gagal = 0;

        foreach ($sheet as $key => $row) {
            if ($key === 0) continue; 

            $kode_assets_kom   = trim($row[0] ?? '');
            $nama_assets_kom   = trim($row[1] ?? '');
            $tgl_pembelian_kom = $row[2] ?? '';
            $user_kom          = trim($row[3] ?? '');
            $ip_kom            = trim($row[4] ?? '');
            $spec_kom          = trim($row[5] ?? '');
            $lokasi_kom        = trim($row[6] ?? '');
            $qty_kom           = (int)($row[7] ?? 0); 
            $desc_kom          = trim($row[8] ?? '');
            $keterangan_kom    = trim($row[9] ?? '');

            
            if (is_numeric($tgl_pembelian_kom)) {
                $tgl_pembelian_kom = Date::excelToDateTimeObject($tgl_pembelian_kom)->format('Y-m-d');
            }

        
            if (empty($kode_assets_kom) || empty($nama_assets_kom)) {
                $gagal++;
                continue;
            }

            if (addData(
                $kode_assets_kom,
                $nama_assets_kom,
                $tgl_pembelian_kom,
                $user_kom,
                $ip_kom,
                $spec_kom,
                $lokasi_kom,
                $qty_kom,
                $desc_kom,
                $keterangan_kom
            )) {
                $sukses++;
            } else {
                $gagal++;
                
                echo "<b>Gagal insert baris ke-$key:</b><br>";
                echo "SQL Error: " . mysqli_error($conn) . "<br>";
                echo "Data: " . implode(" | ", $row) . "<hr>";
                exit;
            }
        }

        header("Location: ../data_komputer.php?success=1&sukses=$sukses&gagal=$gagal");
        exit;

    } catch (Exception $e) {
        echo "Error load Excel: " . $e->getMessage();
        exit;
    }
} else {
    header("Location: ../data_komputer.php");
    exit;
}
