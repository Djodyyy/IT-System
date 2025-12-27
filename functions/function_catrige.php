<?php
require_once 'koneksi.php';
include_once 'helper.php';

if (!$conn) {
    error_log("Koneksi database gagal: " . mysqli_connect_error());
}

function getAllCatrige()
{
    global $conn;
    $sql = "SELECT a.*, j.nama_jenis AS nama_printer
            FROM tb_catrige a
            LEFT JOIN tb_jenis_printer j ON a.id_jenis = j.id_jenis
            ORDER BY a.id_catrige DESC";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        error_log('Query Error (getAllCatrige): ' . mysqli_error($conn));
        return [];
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


if (!function_exists('getAllJenisPrinter')) {
    function getAllJenisPrinter()
    {
        global $conn;
        $query = "SELECT * FROM tb_jenis_printer ORDER BY nama_jenis ASC";
        $result = mysqli_query($conn, $query);
        $data = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }
}


function addCatrige($id_jenis, $type_catrige, $color_catrige, $jml_catrige) {
    global $conn;
    $query = "INSERT INTO tb_catrige (id_jenis, type_catrige, color_catrige, jml_catrige)
              VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isss", $id_jenis, $type_catrige, $color_catrige, $jml_catrige);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function updateCatrige($id_catrige, $id_jenis, $type_catrige, $color_catrige, $jml_catrige)
{
    global $conn;
    $stmt = mysqli_prepare($conn,
        "UPDATE tb_catrige 
         SET id_jenis=?, type_catrige=?, color_catrige=?, jml_catrige=? 
         WHERE id_catrige=?"
    );
    mysqli_stmt_bind_param($stmt, 'issii', $id_jenis, $type_catrige, $color_catrige, $jml_catrige, $id_catrige);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function deleteCatrige($id_catrige)
{
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM tb_catrige WHERE id_catrige = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id_catrige);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}


if (basename($_SERVER['PHP_SELF']) == 'function_catrige.php') {
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['add'])) {
        $ok = addCatrige($_POST['id_jenis'], $_POST['type_catrige'], $_POST['color_catrige'], $_POST['jml_catrige']);
        $_SESSION['message'] = $ok ? 'Data catridge berhasil ditambahkan!' : 'Gagal menambahkan data catridge.';
        header('Location: ../data_catrige.php?status=' . ($ok ? 'success_add' : 'error'));
        exit();
    }

    if (isset($_POST['edit'])) {
        $ok = updateCatrige($_POST['id_catrige'], $_POST['id_jenis'], $_POST['type_catrige'], $_POST['color_catrige'], $_POST['jml_catrige']);
        $_SESSION['message'] = $ok ? 'Data catridge berhasil diubah!' : 'Gagal mengubah data catridge.';
        header('Location: ../data_catrige.php?status=' . ($ok ? 'success_update' : 'error'));
        exit();
    }

    if (isset($_GET['hapus'])) {
        $ok = deleteCatrige($_GET['hapus']);
        $_SESSION['message'] = $ok ? 'Data catridge berhasil dihapus!' : 'Gagal menghapus data catridge.';
        header('Location: ../data_catrige.php?status=' . ($ok ? 'success_delete' : 'error'));
        exit();
    }
}