<?php
require_once 'koneksi.php';

function getAllBarangIT() {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM tb_barang_it ORDER BY nama_barang ASC");
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}
