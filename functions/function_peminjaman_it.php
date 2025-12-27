<?php
require_once 'koneksi.php';



function getPeminjamanAktif() {
    global $conn;

    $sql = "SELECT p.*, b.nama_barang
            FROM tb_peminjaman_it p
            JOIN tb_barang_it b ON p.id_barang = b.id_barang
            WHERE p.status_peminjaman = 'Dipinjam'
            ORDER BY p.tgl_pinjam DESC";

    $q = mysqli_query($conn, $sql);
    if (!$q) return [];
    return mysqli_fetch_all($q, MYSQLI_ASSOC);
}

function getAllPeminjaman() {
    global $conn;

    $sql = "SELECT p.*, b.nama_barang
            FROM tb_peminjaman_it p
            JOIN tb_barang_it b ON p.id_barang = b.id_barang
            ORDER BY p.id_peminjaman DESC";

    $q = mysqli_query($conn, $sql);
    if (!$q) return [];
    return mysqli_fetch_all($q, MYSQLI_ASSOC);
}


function addPeminjaman($nama, $id_barang, $keterangan) {
    global $conn;

    $cek = mysqli_query($conn, "SELECT stok FROM tb_barang_it WHERE id_barang='$id_barang'");
    $b = mysqli_fetch_assoc($cek);

    if (!$b || $b['stok'] <= 0) return false;

    $insert = mysqli_query($conn, "
        INSERT INTO tb_peminjaman_it
        (nama_peminjam, id_barang, tgl_pinjam, status_peminjaman, keterangan)
        VALUES
        ('$nama', '$id_barang', NOW(), 'Dipinjam', '$keterangan')
    ");

    if ($insert) {
        mysqli_query($conn, "UPDATE tb_barang_it SET stok = stok - 1 WHERE id_barang='$id_barang'");
        return true;
    }

    return false;
}

function kembalikanBarang($id) {
    global $conn;

    
    $q = mysqli_query($conn, "SELECT id_barang FROM tb_peminjaman_it WHERE id_peminjaman='$id'");
    $d = mysqli_fetch_assoc($q);

    if ($d) {
        $id_barang = $d['id_barang'];
        
        
        $updateStok = mysqli_query($conn, "UPDATE tb_barang_it SET stok = stok + 1 WHERE id_barang='$id_barang'");
        
        if ($updateStok) {
            return mysqli_query($conn, "DELETE FROM tb_peminjaman_it WHERE id_peminjaman='$id'");
        }
    }

    return false;
}

function deletePeminjaman($id) {
    global $conn;
    return mysqli_query($conn, "DELETE FROM tb_peminjaman_it WHERE id_peminjaman='$id'");
}



if (basename($_SERVER['PHP_SELF']) == 'function_peminjaman_it.php') {
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['add'])) {
        $ok = addPeminjaman($_POST['nama_peminjam'], $_POST['id_barang'], $_POST['keterangan']);
        header("Location: ../data_peminjaman_it.php?status=" . ($ok ? "success_add" : "error"));
        exit;
    }

    if (isset($_GET['kembali'])) {
        $ok = kembalikanBarang($_GET['kembali']);
        header("Location: ../data_peminjaman_it.php?status=" . ($ok ? "success_return" : "error"));
        exit;
    }

    if (isset($_GET['hapus'])) {
        $ok = deletePeminjaman($_GET['hapus']);
        header("Location: ../data_peminjaman_it.php?status=" . ($ok ? "success_delete" : "error"));
        exit;
    }
}