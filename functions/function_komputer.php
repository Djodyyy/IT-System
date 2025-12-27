<?php
require_once 'koneksi.php';
include_once 'helper.php';


function normalizeDate($date)
{
    return (!empty($date)) ? $date : NULL;
}


function generateKodeAsset($kategori, $dept)
{
    global $conn;

    $sql = "SELECT kode_assets_kom 
            FROM tb_komputer
            WHERE kategori_kom = ?
            AND dept_kom = ?
            ORDER BY kode_assets_kom DESC
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $kategori, $dept);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $last = mysqli_fetch_assoc($result);

    $newNumber = $last ? ((int) substr($last['kode_assets_kom'], -3)) + 1 : 1;
    $urut = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    return "$kategori-$dept-$urut";
}

function getData()
{
    global $conn;
    $sql = "SELECT * FROM tb_komputer ORDER BY kode_assets_kom ASC";
    $query = mysqli_query($conn, $sql);
    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}

function getDetailKomputer($kode_assets_kom)
{
    global $conn;
    $sql = "SELECT * FROM tb_komputer WHERE kode_assets_kom = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $kode_assets_kom);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}


function getPerbaikan($kode_assets_kom)
{
    global $conn;
    $sql = "SELECT * FROM tb_perbaikan 
            WHERE kode_assets_kom = ?
            ORDER BY tanggal_perbaikan DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $kode_assets_kom);
    mysqli_stmt_execute($stmt);

    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

function addPerbaikan($kode_assets_kom, $deskripsi, $tanggal, $status)
{
    global $conn;
    $sql = "INSERT INTO tb_perbaikan
            (kode_assets_kom, deskripsi_perbaikan, tanggal_perbaikan, status_perbaikan)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss',
        $kode_assets_kom,
        $deskripsi,
        $tanggal,
        $status
    );

    return mysqli_stmt_execute($stmt);
}

function deletePerbaikan($id)
{
    global $conn;
    $sql = "DELETE FROM tb_perbaikan WHERE id_perbaikan_kom = ?"; 
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    return mysqli_stmt_execute($stmt);
}


function addData($kategori_kom, $dept_kom, $nama_assets_kom, $tgl_pembelian_kom, $user_kom, $ip_kom, $spec_kom, $lokasi_kom, $qty_kom, $desc_kom, $keterangan_kom) {
    global $conn;
    $kode_assets_kom = generateKodeAsset($kategori_kom, $dept_kom);
    $sql = "INSERT INTO tb_komputer 
        (kode_assets_kom, kategori_kom, dept_kom, nama_assets_kom, tgl_pembelian_kom,
         user_kom, ip_kom, spec_kom, lokasi_kom, qty_kom, desc_kom, keterangan_kom)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,'ssssssssisss',
        $kode_assets_kom, $kategori_kom, $dept_kom, $nama_assets_kom, $tgl_pembelian_kom,
        $user_kom, $ip_kom, $spec_kom, $lokasi_kom, $qty_kom, $desc_kom, $keterangan_kom
    );
    return mysqli_stmt_execute($stmt);
}

function editData($kode_assets_kom, $nama_assets_kom, $tgl_pembelian_kom, $user_kom, $ip_kom, $spec_kom, $lokasi_kom, $qty_kom, $desc_kom, $keterangan_kom) {
    global $conn;
    $sql = "UPDATE tb_komputer SET
        nama_assets_kom = ?, tgl_pembelian_kom = ?, user_kom = ?, ip_kom = ?, spec_kom = ?,
        lokasi_kom = ?, qty_kom = ?, desc_kom = ?, keterangan_kom = ?
        WHERE kode_assets_kom = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,'ssssssisss',
        $nama_assets_kom, $tgl_pembelian_kom, $user_kom, $ip_kom, $spec_kom,
        $lokasi_kom, $qty_kom, $desc_kom, $keterangan_kom, $kode_assets_kom
    );
    return mysqli_stmt_execute($stmt);
}

function deleteData($kode_assets_kom)
{
    global $conn;
    $sql = "DELETE FROM tb_komputer WHERE kode_assets_kom = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $kode_assets_kom);
    return mysqli_stmt_execute($stmt);
}


function getTotalKomputer() {
    global $conn;
    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_komputer");
    $data = mysqli_fetch_assoc($res);
    return $data ? $data['total'] : 0;
}

function getTotalPerbaikan() {
    global $conn;
    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_perbaikan");
    $data = mysqli_fetch_assoc($res);
    return $data ? $data['total'] : 0;
}


if (basename($_SERVER['PHP_SELF']) == 'function_komputer.php') {
    if (isset($_POST['add'])) {
        addData($_POST['kategori_kom'], $_POST['dept_kom'], $_POST['nama_assets_kom'], normalizeDate($_POST['tgl_pembelian_kom'] ?? null), $_POST['user_kom'], $_POST['ip_kom'], $_POST['spec_kom'], $_POST['lokasi_kom'], $_POST['qty_kom'], $_POST['desc_kom'] ?? '', $_POST['keterangan_kom'] ?? '');
        header("location:../data_komputer.php?status=success_add");
        exit;
    } elseif (isset($_POST['edit'])) {
        editData($_POST['kode_assets_kom'], $_POST['nama_assets_kom'], normalizeDate($_POST['tgl_pembelian_kom'] ?? null), $_POST['user_kom'], $_POST['ip_kom'], $_POST['spec_kom'], $_POST['lokasi_kom'], $_POST['qty_kom'], $_POST['desc_kom'] ?? '', $_POST['keterangan_kom'] ?? '');
        header("location:../edit_komputer.php?kode_assets_kom=".$_POST['kode_assets_kom']."&success=edit");
        exit;
    } elseif (isset($_POST['add1'])) {
        addPerbaikan($_POST['kode_assets_kom'], $_POST['deskripsi_perbaikan'], normalizeDate($_POST['tanggal_perbaikan']), $_POST['status_perbaikan']);
        header("location:../edit_komputer.php?kode_assets_kom=".$_POST['kode_assets_kom']."&success=add");
        exit;
    } elseif (isset($_GET['hapus1'])) {
        deletePerbaikan($_GET['hapus1']);
        header("location:../edit_komputer.php?kode_assets_kom=".$_GET['kode_assets_kom']."&success=delete");
        exit;
    } elseif (isset($_GET['hapus'])) {
        deleteData($_GET['hapus']);
        header("location:../data_komputer.php?status=success_delete");
        exit;
    }
}