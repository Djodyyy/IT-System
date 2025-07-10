<?php

require_once 'koneksi.php';
include_once 'helper.php';

function getData()
{
    global $conn;
    $sql = 'SELECT * FROM tb_catrige a
            LEFT JOIN tb_printer b ON a.id_printer = b.id_printer';
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);
}

function getPrinter()
{
    global $conn;
    $sql = 'SELECT * FROM tb_printer';
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);
}

function getCatrige()
{
    $id_catrige = $_GET['id_catrige'];
    global $conn;
    $sql = "SELECT * FROM tb_add_catrige a
				LEFT JOIN tb_catrige b ON a.id_catrige = b.id_catrige
				WHERE a.id_catrige=$id_catrige ORDER BY a.id_add_catrige DESC";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);
}

function showData($id_catrige)
{
    global $conn;
    $fixid = mysqli_real_escape_string($conn, $id_catrige);
    $sql = "SELECT * FROM tb_catrige a
            LEFT JOIN tb_printer b ON a.id_printer = b.id_printer
			WHERE id_catrige='$fixid'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
}

function addData($id_printer, $type_catrige, $color_catrige ,$jml_catrige)
{
    global $conn;
    $sql = "INSERT INTO tb_catrige (id_printer, type_catrige, color_catrige, jml_catrige) VALUES ('$id_printer','$type_catrige','$color_catrige','$jml_catrige')";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
    mysqli_close($conn);
}

function addCatrige($id_catrige, $tanggal_catrige, $jumlah_catrige)
{
    global $conn;

    $cekstock = mysqli_query(
        $conn,
        "SELECT * FROM tb_catrige WHERE id_catrige=$id_catrige"
    );
    $nilai = mysqli_fetch_array($cekstock);
    $stock = $nilai['jml_catrige'];
    $stockin = $stock + $jumlah_catrige; // penambahan

    $sql = "INSERT INTO tb_add_catrige (id_catrige, tanggal_catrige, jumlah_catrige) VALUES ('$id_catrige','$tanggal_catrige','$jumlah_catrige')";
    $result = mysqli_query($conn, $sql);
    if ($sql) {
        $upstock = "UPDATE tb_catrige SET jml_catrige='$stockin' WHERE id_catrige='$id_catrige'";
        $result = mysqli_query($conn, $upstock);
    }
    return $result ? true : false;
    mysqli_close($conn);
}

function editData($id_catrige, $id_printer, $type_catrige, $color_catrige ,$jml_catrige)
{
    global $conn;
    $fixid = mysqli_real_escape_string($conn, $id_catrige);
    $sql = "UPDATE tb_catrige SET id_printer='$id_printer', type_catrige='$type_catrige', color_catrige='$color_catrige',jml_catrige='$jml_catrige' WHERE id_catrige='$fixid'";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
    mysqli_close($conn);
}

function deleteData($id_catrige)
{
    global $conn;
    $sql = "DELETE FROM tb_catrige WHERE id_catrige='$id_catrige'";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
    mysqli_close($conn);
}

function deleteData1($id_add_catrige)
{
    global $conn;
    $sql = "DELETE FROM tb_add_catrige WHERE id_add_catrige='$id_add_catrige'";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
    mysqli_close($conn);
}

if (isset($_POST['add'])) {
    $id_printer = mysqli_real_escape_string($conn, $_POST['id_printer']);
    $type_catrige = mysqli_real_escape_string($conn, $_POST['type_catrige']);
    $color_catrige = mysqli_real_escape_string($conn, $_POST['color_catrige']);
    $jml_catrige = mysqli_real_escape_string($conn, $_POST['jml_catrige']);
    $add = addData($id_printer, $type_catrige, $color_catrige ,$jml_catrige);
    session_start();
    unset($_SESSION['message']);
    if ($add) {
        $_SESSION['message'] = $added;
    } else {
        $_SESSION['message'] = $added_failed;
    }
    header('location:../data_catrige.php');
} elseif (isset($_POST['add1'])) {
    $id_catrige = mysqli_real_escape_string($conn, $_POST['id_catrige']);
    $tanggal_catrige = mysqli_real_escape_string($conn, $_POST['tanggal_catrige']);
    $jumlah_catrige = mysqli_real_escape_string($conn, $_POST['jumlah_catrige']);
    $add1 = addCatrige($id_catrige, $tanggal_catrige, $jumlah_catrige);
    session_start();
    unset($_SESSION['message']);
    if ($add1) {
        $_SESSION['message'] = $added;
    } else {
        $_SESSION['message'] = $added_failed;
    }
    header('location:../detail_catrige.php?id_catrige=' . $id_catrige);
} elseif (isset($_POST['edit'])) {
    $id_catrige = mysqli_real_escape_string($conn, $_POST['id_catrige']);
    $id_printer = mysqli_real_escape_string($conn, $_POST['id_printer']);
    $type_catrige = mysqli_real_escape_string($conn, $_POST['type_catrige']);
    $color_catrige = mysqli_real_escape_string($conn, $_POST['color_catrige']);
    $jml_catrige = mysqli_real_escape_string($conn, $_POST['jml_catrige']);
    $edit = editData($id_catrige, $id_printer, $type_catrige, $color_catrige ,$jml_catrige);
    session_start();
    unset($_SESSION['message']);
    if ($edit) {
        $_SESSION['message'] = $edited;
    } else {
        $_SESSION['message'] = $failed;
    }
    header('location:../data_catrige.php');
} elseif (isset($_GET['hapus'])) {
    $id_catrige = mysqli_real_escape_string($conn, $_GET['hapus']);
    $delete = deleteData($id_catrige);
    session_start();
    unset($_SESSION['message']);
    if ($delete) {
        $_SESSION['message'] = $deleted;
    } else {
        $_SESSION['message'] = $failed;
    }
    header('location:../data_catrige.php');
} elseif (isset($_GET['hapus1'])) {
    $id_add_catrige = mysqli_real_escape_string($conn, $_GET['hapus1']);
    $delete1 = deleteData1($id_add_catrige);
    session_start();
    unset($_SESSION['message']);
    if ($delete1) {
        $_SESSION['message'] = $deleted;
    } else {
        $_SESSION['message'] = $failed;
    }
    header('location:../detail_catrige.php?id_catrige=' . $_GET['id_catrige']);
}
?>
