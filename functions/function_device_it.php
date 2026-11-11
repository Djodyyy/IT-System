<?php
require_once 'koneksi.php';
require_once __DIR__ . '/auth.php';
include_once 'helper.php';

/* ==============================
   NORMALIZE DATE
================================ */
function normalizeDate($date)
{
    return (!empty($date)) ? $date : null;
}

/* ==============================
   GENERATE KODE DEVICE
   contoh: LAPTOP-001
================================ */
function generateKodeDevice($kategori)
{
    global $conn;

    $sql = "SELECT kode_device_it
            FROM tb_device_it
            WHERE kategori_device = ?
            ORDER BY kode_device_it DESC
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $kategori);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $last = mysqli_fetch_assoc($res);

    $num = $last
        ? ((int) substr($last['kode_device_it'], -3)) + 1
        : 1;

    return strtoupper($kategori) . '-' . str_pad($num, 3, '0', STR_PAD_LEFT);
}

/* ==============================
   READ
================================ */
function getAllDeviceIT()
{
    global $conn;
    $q = mysqli_query($conn, "SELECT * FROM tb_device_it ORDER BY kode_device_it ASC");
    return mysqli_fetch_all($q, MYSQLI_ASSOC);
}

function getDetailDeviceIT($kode)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM tb_device_it WHERE kode_device_it = ?");
    mysqli_stmt_bind_param($stmt, 's', $kode);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

/* ==============================
   CREATE
================================ */
function addDeviceIT($kategori, $nama, $tgl, $lokasi, $qty, $status, $ket)
{
    global $conn;

    $kode = generateKodeDevice($kategori);

    $sql = "INSERT INTO tb_device_it
            (kode_device_it, kategori_device, nama_device, tgl_pembelian,
             lokasi_device, qty, status_device, keterangan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        'sssssiss',
        $kode,
        $kategori,
        $nama,
        $tgl,
        $lokasi,
        $qty,
        $status,
        $ket
    );

    return mysqli_stmt_execute($stmt);
}

/* ==============================
   UPDATE
================================ */
function editDeviceIT($kode, $nama, $tgl, $lokasi, $qty, $status, $ket)
{
    global $conn;

    $sql = "UPDATE tb_device_it SET
                nama_device   = ?,
                tgl_pembelian = ?,
                lokasi_device = ?,
                qty           = ?,
                status_device = ?,
                keterangan    = ?
            WHERE kode_device_it = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        'sssisss',
        $nama,
        $tgl,
        $lokasi,
        $qty,
        $status,
        $ket,
        $kode
    );

    return mysqli_stmt_execute($stmt);
}

/* ==============================
   DELETE
================================ */
function deleteDeviceIT($kode)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM tb_device_it WHERE kode_device_it = ?");
    mysqli_stmt_bind_param($stmt, 's', $kode);
    return mysqli_stmt_execute($stmt);
}

/* ==============================
   CONTROLLER
================================ */
if (basename($_SERVER['PHP_SELF']) === 'function_device_it.php') {
     requireRole([1, 3]);

    /* === ADD === */
    if (isset($_POST['tambah'])) {

        addDeviceIT(
            $_POST['kategori_device'],
            $_POST['nama_device'],
            normalizeDate($_POST['tgl_pembelian'] ?? null),
            $_POST['lokasi_device'],
            (int) $_POST['qty'],
            $_POST['status_device'],
            $_POST['keterangan'] ?? ''
        );

        header("Location: ../data_barang_it.php?status=success_add");
        exit;
    }

    /* === EDIT === */
    if (isset($_POST['edit'])) {

        editDeviceIT(
            $_POST['kode_device_it'],
            $_POST['nama_device'],
            normalizeDate($_POST['tgl_pembelian'] ?? null),
            $_POST['lokasi_device'],
            (int) $_POST['qty'],
            $_POST['status_device'],
            $_POST['keterangan'] ?? ''
        );

        header("Location: ../data_barang_it.php?status=success_edit");
        exit;
    }

    /* === DELETE === */
    if (isset($_GET['hapus'])) {

        deleteDeviceIT($_GET['hapus']);
        header("Location: ../data_barang_it.php?status=success_delete");
        exit;
    }
}
