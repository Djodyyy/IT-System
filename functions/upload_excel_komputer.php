<?php
require_once __DIR__ . '/../vendor/autoload.php'; // naik 1 folder ke vendor
require_once 'koneksi.php';
require_once __DIR__ . '/function_komputer.php';  // file addData

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['upload'])) {
    $file_excel = $_FILES['file_excel']['tmp_name'];

    if (!file_exists($file_excel)) {
        header("Location: ../data_komputer.php?success=0"); // gagal karena file tidak ditemukan
        exit;
    }

    try {
        $spreadsheet = IOFactory::load($file_excel);
        $sheet = $spreadsheet->getActiveSheet()->toArray();

        $sukses = 0;
        $gagal = 0;

        foreach ($sheet as $key => $row) {
            if ($key === 0) continue; // skip header

            $kode_assets_kom     = $row[0];
            $nama_assets_kom     = $row[1];
            $tgl_pembelian_kom   = $row[2];
            $user_kom            = $row[3];
            $ip_kom              = $row[4];
            $spec_kom            = $row[5];
            $lokasi_kom          = $row[6];
            $qty_kom             = $row[7];
            $desc_kom            = $row[8];
            $keterangan_kom      = $row[9];

            // Lewatkan baris kosong
            if (empty($kode_assets_kom) || empty($nama_assets_kom)) {
                $gagal++;
                continue;
            }

            if (addData($kode_assets_kom, $nama_assets_kom, $tgl_pembelian_kom, $user_kom, $ip_kom, $spec_kom, $lokasi_kom, $qty_kom, $desc_kom, $keterangan_kom)) {
                $sukses++;
            } else {
                $gagal++;
            }
        }

        // redirect ke halaman utama dengan notifikasi
        header("Location: ../data_komputer.php?success=1");
        exit;

    } catch (Exception $e) {
        // Jika file corrupt atau format salah
        header("Location: ../data_komputer.php?success=0");
        exit;
    }
} else {
    // akses langsung tanpa submit
    header("Location: ../data_komputer.php");
    exit;
}
