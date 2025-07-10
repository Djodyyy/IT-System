<?php

require_once 'koneksi.php';
include_once 'helper.php';

function getData()
{
	global $conn;
	$sql 	= "SELECT * FROM tb_komputer";
	$result	= mysqli_query($conn, $sql);
	return mysqli_fetch_all($result, MYSQLI_ASSOC);
	mysqli_free_result($result);
	mysqli_close($conn);
}

function getPerbaikan()
{
	$kode = $_GET['kode_assets_kom'];
	global $conn;
	$sql 	= "SELECT a.id_perbaikan_kom, a.deskripsi_perbaikan, a.tanggal_perbaikan, a.status_perbaikan FROM tb_perbaikan a
					LEFT JOIN tb_komputer b ON a.kode_assets_kom = b.kode_assets_kom
					WHERE a.kode_assets_kom ='$kode'";
	$result	= mysqli_query($conn, $sql);
	return mysqli_fetch_all($result, MYSQLI_ASSOC);
	mysqli_free_result($result);
	mysqli_close($conn);
}

function getDetailKomputer($kode_assets_kom)
{
	global $conn;
	$sql = "SELECT * FROM tb_komputer WHERE kode_assets_kom = '$kode_assets_kom'";
	$result = mysqli_query($conn, $sql);
	return mysqli_fetch_assoc($result); // pakai fetch_assoc karena 1 baris saja
}

function addData($kode_assets_kom, $nama_assets_kom, $tgl_pembelian_kom, $user_kom, $ip_kom, $spec_kom, $lokasi_kom, $qty_kom, $desc_kom, $keterangan_kom)
{
	global $conn;

	// Handle NULL values: ganti NULL jadi string kosong atau 0
	$kode_assets_kom   = $kode_assets_kom ?? '';
	$nama_assets_kom   = $nama_assets_kom ?? '';
	$tgl_pembelian_kom = $tgl_pembelian_kom ?? '';
	$user_kom          = $user_kom ?? '';
	$ip_kom            = $ip_kom ?? '';
	$spec_kom          = $spec_kom ?? '';
	$lokasi_kom        = $lokasi_kom ?? '';
	$qty_kom           = is_numeric($qty_kom) ? $qty_kom : 0;
	$desc_kom          = $desc_kom ?? '';
	$keterangan_kom    = $keterangan_kom ?? '';

	// Gunakan prepared statement
	$sql = "INSERT INTO tb_komputer (kode_assets_kom, nama_assets_kom, tgl_pembelian_kom, user_kom, ip_kom, spec_kom, lokasi_kom, qty_kom, desc_kom, keterangan_kom) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$stmt = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param(
		$stmt,
		'sssssssiss',
		$kode_assets_kom,
		$nama_assets_kom,
		$tgl_pembelian_kom,
		$user_kom,
		$ip_kom,
		$spec_kom,
		$lokasi_kom,
		$qty_kom,
		$desc_kom,
		$keterangan_kom
	);

	$result = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	return $result;
}


function addPerbaikan($deskripsi_perbaikan, $tanggal_perbaikan, $status_perbaikan, $kode_assets_kom)
{
	global $conn;
	$sql 	= "INSERT INTO tb_perbaikan (deskripsi_perbaikan, tanggal_perbaikan, status_perbaikan, kode_assets_kom) VALUES ('$deskripsi_perbaikan','$tanggal_perbaikan','$status_perbaikan','$kode_assets_kom')";
	$result	= mysqli_query($conn, $sql);
	return ($result) ? true : false;
	mysqli_close($conn);
}

function editData($kode_assets_kom, $nama_assets_kom, $tgl_pembelian_kom, $user_kom, $ip_kom, $spec_kom, $lokasi_kom, $qty_kom, $desc_kom, $keterangan_kom)
{
	global $conn;
	$fixid 	= mysqli_real_escape_string($conn, $kode_assets_kom);
	$sql 	= "UPDATE tb_komputer SET kode_assets_kom='$kode_assets_kom', nama_assets_kom='$nama_assets_kom', tgl_pembelian_kom='$tgl_pembelian_kom', user_kom='$user_kom', ip_kom='$ip_kom', spec_kom='$spec_kom', lokasi_kom='$lokasi_kom', qty_kom='$qty_kom', desc_kom='$desc_kom', keterangan_kom='$keterangan_kom' WHERE kode_assets_kom='$fixid'";
	$result	= mysqli_query($conn, $sql);
	return ($result) ? true : false;
	mysqli_close($conn);
}

// function editPerbaikan($id_perbaikan_kom, $deskripsi_perbaikan, $tanggal_perbaikan, $status_perbaikan) {
// 	global $conn;
// 	$fixid 	= mysqli_real_escape_string($conn, $id_perbaikan_kom);
// 	$sql 	= "UPDATE tb_perbaikan SET deskripsi_perbaikan='$deskripsi_perbaikan', tanggal_perbaikan='$tanggal_perbaikan', status_perbaikan='$status_perbaikan' WHERE id_perbaikan_kom='$fixid'";
// 	$result	= mysqli_query($conn, $sql);
// 	return ($result) ? true : false;
// 	mysqli_close($conn);
// }

function deleteData($kode_assets_kom)
{
	global $conn;
	$sql 	= "DELETE FROM tb_komputer WHERE kode_assets_kom='$kode_assets_kom'";
	$result	= mysqli_query($conn, $sql);
	return ($result) ? true : false;
	mysqli_close($conn);
}

function deletePerbaikan($id_perbaikan_kom)
{
	global $conn;
	$sql 	= "DELETE FROM tb_perbaikan WHERE id_perbaikan_kom='$id_perbaikan_kom'";
	$result	= mysqli_query($conn, $sql);
	return ($result) ? true : false;
	mysqli_close($conn);
}

if (isset($_POST['add'])) {
	$kode_assets_kom	= mysqli_real_escape_string($conn, $_POST['kode_assets_kom']);
	$nama_assets_kom	= mysqli_real_escape_string($conn, $_POST['nama_assets_kom']);
	$tgl_pembelian_kom	= mysqli_real_escape_string($conn, $_POST['tgl_pembelian_kom']);
	$user_kom	        = mysqli_real_escape_string($conn, $_POST['user_kom']);
	$ip_kom	            = mysqli_real_escape_string($conn, $_POST['ip_kom']);
	$spec_kom	        = mysqli_real_escape_string($conn, $_POST['spec_kom']);
	$lokasi_kom	        = mysqli_real_escape_string($conn, $_POST['lokasi_kom']);
	$qty_kom	        = mysqli_real_escape_string($conn, $_POST['qty_kom']);
	$desc_kom           = mysqli_real_escape_string($conn, $_POST['desc_kom']);
	$keterangan_kom     = mysqli_real_escape_string($conn, $_POST['keterangan_kom']);
	$add 	            = addData($kode_assets_kom, $nama_assets_kom, $tgl_pembelian_kom, $user_kom, $ip_kom, $spec_kom, $lokasi_kom, $qty_kom, $desc_kom, $keterangan_kom);
	session_start();
	unset($_SESSION["message"]);
	if ($add) {
		$_SESSION['message'] = $added;
	} else {
		$_SESSION['message'] = $added_failed;
	}
	header("location:../data_komputer.php");
} elseif (isset($_POST['add1'])) {
	$deskripsi_perbaikan	= mysqli_real_escape_string($conn, $_POST['deskripsi_perbaikan']);
	$tanggal_perbaikan		= mysqli_real_escape_string($conn, $_POST['tanggal_perbaikan']);
	$status_perbaikan		= mysqli_real_escape_string($conn, $_POST['status_perbaikan']);
	$kode_assets_kom		= mysqli_real_escape_string($conn, $_POST['kode_assets_kom']);
	$add1 	            	= addPerbaikan($deskripsi_perbaikan, $tanggal_perbaikan, $status_perbaikan, $kode_assets_kom);
	session_start();
	unset($_SESSION["message"]);
	if ($add1) {
		$_SESSION['message'] = $added;
	} else {
		$_SESSION['message'] = $added_failed;
	}
	header("location:../detail_komputer.php?kode_assets_kom=" . $kode_assets_kom);
} elseif (isset($_POST['edit'])) {
	$kode_assets_kom	= mysqli_real_escape_string($conn, $_POST['kode_assets_kom']);
	$nama_assets_kom	= mysqli_real_escape_string($conn, $_POST['nama_assets_kom']);
	$tgl_pembelian_kom	= mysqli_real_escape_string($conn, $_POST['tgl_pembelian_kom']);
	$user_kom	        = mysqli_real_escape_string($conn, $_POST['user_kom']);
	$ip_kom	            = mysqli_real_escape_string($conn, $_POST['ip_kom']);
	$spec_kom	        = mysqli_real_escape_string($conn, $_POST['spec_kom']);
	$lokasi_kom	        = mysqli_real_escape_string($conn, $_POST['lokasi_kom']);
	$qty_kom	        = mysqli_real_escape_string($conn, $_POST['qty_kom']);
	$desc_kom           = mysqli_real_escape_string($conn, $_POST['desc_kom']);
	$keterangan_kom     = mysqli_real_escape_string($conn, $_POST['keterangan_kom']);
	$edit 		        = editData($kode_assets_kom, $nama_assets_kom, $tgl_pembelian_kom, $user_kom, $ip_kom, $spec_kom, $lokasi_kom, $qty_kom, $desc_kom, $keterangan_kom);
	session_start();
	unset($_SESSION["message"]);
	if ($edit) {
		$_SESSION['message'] = $edited;
	} else {
		$_SESSION['message'] = $failed;
	}
	header("location:../data_komputer.php");

	// }elseif (isset($_POST['edit1'])) {
	// 	$id_perbaikan_kom		= mysqli_real_escape_string($conn, $_POST['id_perbaikan_kom']);
	// 	$deskripsi_perbaikan	= mysqli_real_escape_string($conn, $_POST['deskripsi_perbaikan']);
	// 	$tanggal_perbaikan		= mysqli_real_escape_string($conn, $_POST['tanggal_perbaikan']);
	// 	$status_perbaikan		= mysqli_real_escape_string($conn, $_POST['status_perbaikan']);
	// 	$edit1 		        	= editPerbaikan($id_perbaikan_kom, $deskripsi_perbaikan, $tanggal_perbaikan, $status_perbaikan);
	// 	session_start();
	// 	unset ($_SESSION["message"]);
	// 	if ($edit) {			
	// 		$_SESSION['message'] = $edited;
	// 	}else {
	// 		$_SESSION['message'] = $failed;
	// 	}
	// 	header("location:../data_komputer.php");

} elseif (isset($_GET['hapus'])) {
	$kode_assets_kom    = mysqli_real_escape_string($conn, $_GET['hapus']);
	$delete = deleteData($kode_assets_kom);
	session_start();
	unset($_SESSION["message"]);
	if ($delete) {
		$_SESSION['message'] = $deleted;
	} else {
		$_SESSION['message'] = $failed;
	}
	header("location:../data_komputer.php");
} elseif (isset($_GET['hapus1'])) {
	$kode_assets_kom = $_GET['kode_assets_kom'];
	$id_perbaikan_kom    = mysqli_real_escape_string($conn, $_GET['hapus1']);
	$delete = deletePerbaikan($id_perbaikan_kom);
	session_start();
	unset($_SESSION["message"]);
	if ($delete) {
		$_SESSION['message'] = $deleted;
	} else {
		$_SESSION['message'] = $failed;
	}
	header("location:../detail_komputer.php?kode_assets_kom=" . $kode_assets_kom);
}
function getTotalKomputer() {
    global $conn;
    $result = mysqli_query($conn, "SELECT SUM(qty_kom) as total FROM tb_komputer");
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

function getTotalPerbaikan() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_perbaikan WHERE status_perbaikan = 'perbaikan'");
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

function getTotalPergantian() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_perbaikan WHERE status_perbaikan = 'pergantian'");
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}
