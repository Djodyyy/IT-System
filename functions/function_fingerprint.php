<?php
require_once 'koneksi.php';
require_once __DIR__ . '/auth.php';
include_once 'helper.php';

/* ==============================
   KODE AUTO FP-001
================================ */
function generateKodeFingerprint()
{
    global $conn;
    $q = mysqli_query($conn,
        "SELECT kode_fingerprint
         FROM tb_fingerprint
         ORDER BY id_fingerprint DESC
         LIMIT 1"
    );

    $last = mysqli_fetch_assoc($q);
    $num  = $last ? ((int) substr($last['kode_fingerprint'], -3)) + 1 : 1;

    return 'FP-' . str_pad($num, 3, '0', STR_PAD_LEFT);
}

function getTotalFingerprintAktif() {
    global $conn; 
    $query = "SELECT COUNT(*) as total FROM tb_fingerprint WHERE status = 'aktif'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'] ?? 0;
}

/* ==============================
   READ GEDUNG
================================ */
function getAllGedung()
{
    global $conn;
    $q = mysqli_query($conn, "SELECT * FROM tb_gedung ORDER BY kode_gedung ASC");
    return mysqli_fetch_all($q, MYSQLI_ASSOC);
}

/* ==============================
   READ FINGERPRINT
================================ */
function getAllFingerprint()
{
    global $conn;
    // Pastikan kolom ip_address ada di query SELECT f.*
    $sql = "SELECT f.*, g.kode_gedung, g.nama_gedung
            FROM tb_fingerprint f
            JOIN tb_gedung g ON f.id_gedung = g.id_gedung
            ORDER BY g.kode_gedung, f.fungsi";
    $q = mysqli_query($conn, $sql);
    return mysqli_fetch_all($q, MYSQLI_ASSOC);
}

/* ==============================
   CREATE (With IP Address)
================================ */
function addFingerprint($nama, $ip, $idGedung, $fungsi, $status, $ket)
{
    global $conn;
    $kode = generateKodeFingerprint();

    $sql = "INSERT INTO tb_fingerprint
            (kode_fingerprint, nama_fingerprint, ip_address, id_gedung, fungsi, status, keterangan)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    // Bind Param: s=string, i=integer (Total 7: sssisss)
    mysqli_stmt_bind_param(
        $stmt,
        'sssisss',
        $kode,
        $nama,
        $ip,
        $idGedung,
        $fungsi,
        $status,
        $ket
    );

    return mysqli_stmt_execute($stmt);
}

/* ==============================
   UPDATE (With IP Address)
================================ */
function editFingerprint($kode, $nama, $ip, $idGedung, $fungsi, $status, $ket)
{
    global $conn;

    $sql = "UPDATE tb_fingerprint SET
                nama_fingerprint = ?,
                ip_address = ?,
                id_gedung = ?,
                fungsi = ?,
                status = ?,
                keterangan = ?
            WHERE kode_fingerprint = ?";

    $stmt = mysqli_prepare($conn, $sql);
    // Bind Param: s=string, i=integer (Total 7: ssissss)
    mysqli_stmt_bind_param(
        $stmt,
        'ssissss',
        $nama,
        $ip,
        $idGedung,
        $fungsi,
        $status,
        $ket,
        $kode
    );

    return mysqli_stmt_execute($stmt);
}

/* ==============================
   DELETE
================================ */
function deleteFingerprint($kode)
{
    global $conn;
    $stmt = mysqli_prepare($conn,
        "DELETE FROM tb_fingerprint WHERE kode_fingerprint = ?"
    );
    mysqli_stmt_bind_param($stmt, 's', $kode);
    return mysqli_stmt_execute($stmt);
}

/* ==============================
   CONTROLLER
================================ */
if (basename($_SERVER['PHP_SELF']) === 'function_fingerprint.php') {
    requireRole([1]);

    // Logic Tambah
    if (isset($_POST['tambah'])) {
        addFingerprint(
            $_POST['nama_fingerprint'],
            $_POST['ip_address'],
            (int) $_POST['id_gedung'],
            $_POST['fungsi'],
            $_POST['status'],
            $_POST['keterangan'] ?? ''
        );
        header("Location: ../data_fingerprint.php?status=success_add");
        exit;
    }

    // Logic Edit
    if (isset($_POST['edit'])) {
        editFingerprint(
            $_POST['kode_fingerprint'],
            $_POST['nama_fingerprint'],
            $_POST['ip_address'],
            (int) $_POST['id_gedung'],
            $_POST['fungsi'],
            $_POST['status'],
            $_POST['keterangan'] ?? ''
        );
        header("Location: ../data_fingerprint.php?status=success_edit");
        exit;
    }

    // Logic Hapus
    if (isset($_GET['hapus'])) {
        deleteFingerprint($_GET['hapus']);
        header("Location: ../data_fingerprint.php?status=success_delete");
        exit;
    }
}