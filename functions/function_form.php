<?php

use LDAP\Result;

require_once 'koneksi.php';
include_once 'helper.php';

function getData()
{
    global $conn;
    $sql = "SELECT * FROM tb_form a
					LEFT JOIN tb_catrige b ON a.id_catrige = b.id_catrige";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);
}

function getCatrige()
{
    global $conn;
    $sql = 'SELECT * FROM tb_catrige';
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);
}

function showData($id_form)
{
    global $conn;
    $fixid = mysqli_real_escape_string($conn, $id_form);
    $sql = "SELECT * FROM tb_form
					WHERE id_form='$fixid'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
}

function addData($id_catrige, $depart, $tanggal_form, $qty_pengambilan)
{
    global $conn;

    $cekstock = mysqli_query(
        $conn,
        "SELECT * FROM tb_catrige WHERE id_catrige=$id_catrige"
    );
    $nilai = mysqli_fetch_array($cekstock);
    $stock = $nilai['jml_catrige'];
    $stockout = $stock - $qty_pengambilan; // pengurangan

    if ($stock < $qty_pengambilan) {
        return false;
    } else {
        $sql = "INSERT INTO tb_form (id_catrige, depart, tanggal_form, qty_pengambilan) VALUES ('$id_catrige','$depart','$tanggal_form','$qty_pengambilan')";
        $result = mysqli_query($conn, $sql);

        if ($sql) {
            $upstock = "UPDATE tb_catrige SET jml_catrige='$stockout' WHERE id_catrige='$id_catrige'";
            $result = mysqli_query($conn, $upstock);
        }
        return $result ? true : false;
    }
    mysqli_close($conn);
}

function editData(
    $id_form,
    $id_catrige,
    $depart,
    $tanggal_form,
    $qty_pengambilan
) {
    global $conn;
    $fixid = mysqli_real_escape_string($conn, $id_form);
    $sql = "UPDATE tb_form SET id_catrige='$id_catrige', depart='$depart', tanggal_form='$tanggal_form', qty_pengambilan='$qty_pengambilan' WHERE id_form='$fixid'";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
    mysqli_close($conn);
}

function deleteData($id_form)
{
    global $conn;
    $sql = "DELETE FROM tb_form WHERE id_form='$id_form'";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
    mysqli_close($conn);
}

if (isset($_POST['add'])) {
    $id_catrige = mysqli_real_escape_string($conn, $_POST['id_catrige']);
    $depart = mysqli_real_escape_string($conn, $_POST['depart']);
    $tanggal_form = mysqli_real_escape_string($conn, $_POST['tanggal_form']);
    $qty_pengambilan = mysqli_real_escape_string(
        $conn,
        $_POST['qty_pengambilan']
    );
    $add = addData($id_catrige, $depart, $tanggal_form, $qty_pengambilan);
    session_start();
    unset($_SESSION['message']);
    if ($add) {
        $_SESSION['message'] = $added;
    } else {
        $_SESSION['message'] = $added_failed;
    }
    header('location:../form_catrige.php');
} elseif (isset($_POST['edit'])) {
    $id_form = mysqli_real_escape_string($conn, $_POST['id_form']);
    $id_catrige = mysqli_real_escape_string($conn, $_POST['id_catrige']);
    $depart = mysqli_real_escape_string($conn, $_POST['depart']);
    $tanggal_form = mysqli_real_escape_string($conn, $_POST['tanggal_form']);
    $qty_pengambilan = mysqli_real_escape_string(
        $conn,
        $_POST['qty_pengambilan']
    );
    $edit = editData(
        $id_form,
        $id_catrige,
        $depart,
        $tanggal_form,
        $qty_pengambilan
    );
    session_start();
    unset($_SESSION['message']);
    if ($edit) {
        $_SESSION['message'] = $edited;
    } else {
        $_SESSION['message'] = $failed;
    }
    header('location:../form_catrige.php');
} elseif (isset($_GET['hapus'])) {
    $id_form = mysqli_real_escape_string($conn, $_GET['hapus']);
    $delete = deleteData($id_form);
    session_start();
    unset($_SESSION['message']);
    if ($delete) {
        $_SESSION['message'] = $deleted;
    } else {
        $_SESSION['message'] = $failed;
    }
    header('location:../form_catrige.php');
}
?>
