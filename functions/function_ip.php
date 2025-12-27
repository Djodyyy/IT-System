<?php
require_once 'koneksi.php';

if (!$conn) {
    error_log("Koneksi database gagal: " . mysqli_connect_error());
    die("Terjadi kesalahan koneksi database.");
}

function getAllIp()
{
    global $conn;
    $sql = "SELECT * FROM tb_ip ORDER BY id_ip DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        error_log("Query Error (getAllIp): " . mysqli_error($conn));
        return [];
    }

    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $data;
}

function findIpByAddress($ip_address, $exclude_id = null)
{
    global $conn;
    $ip_address = mysqli_real_escape_string($conn, trim($ip_address));
    $exclude = '';
    if ($exclude_id !== null) {
        $exclude_id = (int)$exclude_id;
        $exclude = " AND id_ip != $exclude_id";
    }
    $sql = "SELECT * FROM tb_ip WHERE ip_address = '$ip_address' $exclude LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        error_log("Query Error (findIpByAddress): " . mysqli_error($conn));
        return null;
    }
    return mysqli_fetch_assoc($result); 
}

function getTotalIpTerpakai()
{
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM tb_ip WHERE LOWER(status_ip) LIKE '%aktif%'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        error_log("Query Error (getTotalIpTerpakai): " . mysqli_error($conn));
        return 0;
    }

    $row = mysqli_fetch_assoc($result);
    return isset($row['total']) ? (int)$row['total'] : 0;
}

function getIpById($id_ip)
{
    global $conn;
    $id_ip = (int)$id_ip;
    $sql = "SELECT * FROM tb_ip WHERE id_ip = $id_ip";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        error_log("Query Error (getIpById): " . mysqli_error($conn));
        return [];
    }

    return mysqli_fetch_assoc($result);
}


function addIp($ip_address, $user_ip, $status_ip)
{
    global $conn;
    $ip_address = mysqli_real_escape_string($conn, trim($ip_address));
    $user_ip    = mysqli_real_escape_string($conn, trim($user_ip));
    $status_ip  = mysqli_real_escape_string($conn, trim($status_ip));

    $exists = findIpByAddress($ip_address);
    if ($exists) {
        if ($exists['user_ip'] !== $user_ip) {
            error_log("Terdeteksi IP sudah dipakai user lain: $ip_address oleh {$exists['user_ip']}");
            return false;
        }
    }

    $sql = "INSERT INTO tb_ip (ip_address, user_ip, status_ip) VALUES ('$ip_address', '$user_ip', '$status_ip')";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        error_log("Query Error (addIp): " . mysqli_error($conn));
        return false;
    }

    return true;
}

function updateIp($id_ip, $ip_address, $user_ip, $status_ip)
{
    global $conn;
    $id_ip      = (int)$id_ip;
    $ip_address = mysqli_real_escape_string($conn, trim($ip_address));
    $user_ip    = mysqli_real_escape_string($conn, trim($user_ip));
    $status_ip  = mysqli_real_escape_string($conn, trim($status_ip));

    $exists = findIpByAddress($ip_address, $id_ip);
    if ($exists) {
        if ($exists['user_ip'] !== $user_ip) {
            error_log("Terdeteksi IP sudah dipakai user lain (update): $ip_address oleh {$exists['user_ip']}");
            return false;
        }
    }

    $sql = "UPDATE tb_ip 
            SET ip_address = '$ip_address', user_ip = '$user_ip', status_ip = '$status_ip' 
            WHERE id_ip = $id_ip";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        error_log("Query Error (updateIp): " . mysqli_error($conn));
        return false;
    }

    return true;
}

function deleteIp($id_ip)
{
    global $conn;
    $id_ip = (int)$id_ip;
    $sql = "DELETE FROM tb_ip WHERE id_ip = $id_ip";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        error_log("Query Error (deleteIp): " . mysqli_error($conn));
        return false;
    }

    return true;
}

if (basename($_SERVER['PHP_SELF']) == 'function_ip.php') {
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['add'])) {
        $ok = addIp($_POST['ip_address'], $_POST['user_ip'], $_POST['status_ip']);
        $_SESSION['message'] = $ok ? 'Data berhasil ditambahkan!' : 'Gagal menambah data.';
        header('Location: ../data_ip.php');
        exit;
    }

    if (isset($_POST['edit'])) {
        $ok = updateIp($_POST['id_ip'], $_POST['ip_address'], $_POST['user_ip'], $_POST['status_ip']);
        $_SESSION['message'] = $ok ? 'Data berhasil diubah!' : 'Gagal mengubah data.';
        header('Location: ../data_ip.php');
        exit;
    }

    if (isset($_GET['hapus'])) {
        $ok = deleteIp($_GET['hapus']);
        $_SESSION['message'] = $ok ? 'Data berhasil dihapus!' : 'Gagal menghapus data.';
        header('Location: ../data_ip.php');
        exit;
    }
}