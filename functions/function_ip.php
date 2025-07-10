<?php

require_once 'koneksi.php';
include_once 'helper.php';

function getData()
{
    global $conn;
    $sql = 'SELECT * FROM tb_ip';
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);
}

function addData($ip_address, $user_ip, $status_ip)
{
    global $conn;
    $sql = "INSERT INTO tb_ip (ip_address, user_ip, status_ip) 
                    VALUES ('$ip_address', '$user_ip', '$status_ip')";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
    mysqli_close($conn);
}

function showData($id_ip)
{
    global $conn;
    $fixid = mysqli_real_escape_string($conn, $id_ip);
    $sql = "SELECT * FROM tb_ip WHERE id_ip='$fixid'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
}

function editData($id_ip, $ip_address, $user_ip, $status_ip)
{
    global $conn;
    $fixid = mysqli_real_escape_string($conn, $id_ip);
    $sql = "UPDATE tb_ip SET ip_address='$ip_address', user_ip='$user_ip', status_ip='$status_ip'
                    WHERE id_ip='$fixid'";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
    mysqli_close($conn);
}

function deleteData($id_ip)
{
    global $conn;
    $sql = "DELETE FROM tb_ip WHERE id_ip='$id_ip'";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
    mysqli_close($conn);
}

if (isset($_POST['add'])) {
    $ip_address = mysqli_real_escape_string($conn, $_POST['ip_address']);
    $user_ip = mysqli_real_escape_string($conn, $_POST['user_ip']);
    $status_ip = mysqli_real_escape_string($conn, $_POST['status_ip']);
    $add = addData($ip_address, $user_ip, $status_ip);
    session_start();
    unset($_SESSION['message']);
    if ($add) {
        $_SESSION['message'] = $added;
    } else {
        $_SESSION['message'] = $failed;
    }
    header('location:../data_ip.php');
} elseif (isset($_POST['edit'])) {
    $id_ip = mysqli_real_escape_string($conn, $_POST['id_ip']);
    $ip_address = mysqli_real_escape_string($conn, $_POST['ip_address']);
    $user_ip = mysqli_real_escape_string($conn, $_POST['user_ip']);
    $status_ip = mysqli_real_escape_string($conn, $_POST['status_ip']);
    $edit = editData($id_ip, $ip_address, $user_ip, $status_ip);
    session_start();
    unset($_SESSION['message']);
    if ($edit) {
        $_SESSION['message'] = $edited;
    } else {
        $_SESSION['message'] = $failed;
    }
    header('location:../data_ip.php');
} elseif (isset($_GET['hapus'])) {
    $id_ip = mysqli_real_escape_string($conn, $_GET['hapus']);
    $delete = deleteData($id_ip);
    session_start();
    unset($_SESSION['message']);
    if ($delete) {
        $_SESSION['message'] = $deleted;
    } else {
        $_SESSION['message'] = $failed;
    }
    header('location:../data_ip.php');
}

?>
