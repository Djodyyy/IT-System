<?php
require_once 'koneksi.php';


function getAllPrinter() {
    global $conn;
    $sql = "SELECT p.*, j.nama_jenis 
            FROM tb_printer p
            JOIN tb_jenis_printer j ON p.id_jenis = j.id_jenis
            ORDER BY p.id_printer DESC";
    $result = mysqli_query($conn, $sql);
    if (!$result) return [];
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $data;
}


function getTotalPrinterAktif() {
    global $conn;
    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_printer WHERE status_printer = '1'");
    if (!$res) return 0;
    $data = mysqli_fetch_assoc($res);
    return $data['total'] ?? 0;
}


function getAllJenisPrinter() {
    global $conn;
    $sql = "SELECT * FROM tb_jenis_printer ORDER BY nama_jenis ASC";
    $result = mysqli_query($conn, $sql);
    if (!$result) return [];
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $data;
}


function getPrinterById($id_printer) {
    global $conn;
    $id_printer = (int)$id_printer;
    $sql = "SELECT * FROM tb_printer WHERE id_printer = $id_printer";
    $result = mysqli_query($conn, $sql);
    if (!$result) return null;
    return mysqli_fetch_assoc($result);
}


function addJenisPrinter($nama_jenis) {
    global $conn;
    $nama_jenis = mysqli_real_escape_string($conn, $nama_jenis);
    $sql = "INSERT INTO tb_jenis_printer (nama_jenis) VALUES ('$nama_jenis')";
    return mysqli_query($conn, $sql);
}


function addPrinter($id_jenis, $lokasi, $ip, $status, $tanggal) {
    global $conn;
    $stmt = mysqli_prepare($conn, 
        "INSERT INTO tb_printer (id_jenis, lokasi_printer, ip_printer, status_printer, tanggal_printer) 
         VALUES (?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "issis", $id_jenis, $lokasi, $ip, $status, $tanggal);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}


function updatePrinter($id_printer, $id_jenis, $lokasi_printer, $ip_printer, $status_printer, $tanggal_printer) {
    global $conn;
    $id_printer = (int)$id_printer;
    $id_jenis = (int)$id_jenis;
    $lokasi_printer = mysqli_real_escape_string($conn, $lokasi_printer);
    $ip_printer = mysqli_real_escape_string($conn, $ip_printer);
    $status_printer = mysqli_real_escape_string($conn, $status_printer);
    $tanggal_printer = mysqli_real_escape_string($conn, $tanggal_printer);

    $sql = "UPDATE tb_printer 
            SET id_jenis=$id_jenis, lokasi_printer='$lokasi_printer',
                ip_printer='$ip_printer', status_printer='$status_printer', tanggal_printer='$tanggal_printer'
            WHERE id_printer=$id_printer";
    return mysqli_query($conn, $sql);
}


function deletePrinter($id_printer) {
    global $conn;
    $id_printer = (int)$id_printer;
    $sql = "DELETE FROM tb_printer WHERE id_printer = $id_printer";
    return mysqli_query($conn, $sql);
}



if (basename($_SERVER['PHP_SELF']) == 'function_printer.php') {

    
    if (isset($_POST['add_jenis'])) {
        $ok = addJenisPrinter($_POST['nama_jenis']);
        header('Location: ../data_printer.php?status=' . ($ok ? 'success_add_jenis' : 'error'));
        exit;
    }

    
    if (isset($_POST['add'])) {
        $ok = addPrinter($_POST['jenis_printer'], $_POST['lokasi_printer'], $_POST['ip_printer'], $_POST['status_printer'], $_POST['tanggal_printer']);
        header('Location: ../data_printer.php?status=' . ($ok ? 'success_add' : 'error'));
        exit;
    }

    
    if (isset($_POST['edit'])) {
        $ok = updatePrinter($_POST['id_printer'], $_POST['jenis_printer'], $_POST['lokasi_printer'], $_POST['ip_printer'], $_POST['status_printer'], $_POST['tanggal_printer']);
        header('Location: ../data_printer.php?status=' . ($ok ? 'success_update' : 'error'));
        exit;
    }

    
    if (isset($_GET['hapus'])) {
        $ok = deletePrinter($_GET['hapus']);
        header('Location: ../data_printer.php?status=' . ($ok ? 'success_delete' : 'error'));
        exit;
    }
}
?>