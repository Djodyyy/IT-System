<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/auth.php';
requireRole([1, 3], true);
header('Content-Type: application/json');

$kategori = $_GET['kategori'] ?? '';

$stmt = $conn->prepare(
    "SELECT id_barang, nama_barang, stok
     FROM tb_barang_it
     WHERE kategori_barang = ? AND stok > 0
     ORDER BY nama_barang ASC"
);
$stmt->bind_param('s', $kategori);
$stmt->execute();
$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode($data);