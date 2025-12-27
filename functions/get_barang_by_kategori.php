<?php
require_once 'koneksi.php';
header('Content-Type: application/json');

$kategori = $_GET['kategori'] ?? '';

$data = [];
$q = mysqli_query($conn,
    "SELECT id_barang, nama_barang, stok
     FROM tb_barang_it
     WHERE kategori_barang='$kategori' AND stok > 0
     ORDER BY nama_barang ASC"
);

while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
}

echo json_encode($data);
