<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

if (
    isset($_POST['nama_barang'], $_POST['kategori_barang'], $_POST['kondisi_barang'], $_POST['stok'], $_POST['lokasi_barang'])
) {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO tb_barang_it
        (nama_barang, kategori_barang, kondisi_barang, stok, lokasi_barang, keterangan)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssiss",
        $_POST['nama_barang'],
        $_POST['kategori_barang'],
        $_POST['kondisi_barang'],
        $_POST['stok'],
        $_POST['lokasi_barang'],
        $_POST['keterangan']
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
