<?php

    require_once 'koneksi.php';
	include_once 'helper.php';

    function getData() {
		global $conn;
		$sql 	= "SELECT * FROM tb_printer";
		$result	= mysqli_query($conn, $sql);
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
		mysqli_free_result($result);
		mysqli_close($conn);
	}

    function showData($id_printer) {
		global $conn;
		$fixid 	= mysqli_real_escape_string($conn, $id_printer);
		$sql 	= "SELECT * FROM tb_printer
					WHERE id_printer='$fixid'";
		$result	= mysqli_query($conn, $sql);
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
		mysqli_close($conn);
	}

    function addData($nama_printer, $lokasi_printer, $ip_printer, $status_printer, $tanggal_printer) {
		global $conn;
		$sql 	= "INSERT INTO tb_printer (nama_printer, lokasi_printer, ip_printer, status_printer, tanggal_printer) VALUES ('$nama_printer','$lokasi_printer','$ip_printer','$status_printer','$tanggal_printer')";
		$result	= mysqli_query($conn, $sql);
		return ($result) ? true : false;
		mysqli_close($conn);
	}

    function editData($id_printer, $nama_printer, $lokasi_printer, $ip_printer, $status_printer, $tanggal_printer) {
		global $conn;
		$fixid 	= mysqli_real_escape_string($conn, $id_printer);
		$sql 	= "UPDATE tb_printer SET nama_printer='$nama_printer', lokasi_printer='$lokasi_printer', ip_printer='$ip_printer', status_printer='$status_printer', tanggal_printer='$tanggal_printer' WHERE id_printer='$fixid'";
		$result	= mysqli_query($conn, $sql);
		return ($result) ? true : false;
		mysqli_close($conn);
	}

    function deleteData($id_printer) {
		global $conn;
		$sql 	= "DELETE FROM tb_printer WHERE id_printer='$id_printer'";
		$result	= mysqli_query($conn, $sql);
		return ($result) ? true : false;
		mysqli_close($conn);
	}

    if (isset($_POST['add'])) {
		$nama_printer	    = mysqli_real_escape_string($conn, $_POST['nama_printer']);
        $lokasi_printer	    = mysqli_real_escape_string($conn, $_POST['lokasi_printer']);
		$ip_printer	        = mysqli_real_escape_string($conn, $_POST['ip_printer']);
        $status_printer	    = mysqli_real_escape_string($conn, $_POST['status_printer']);
		$tanggal_printer    = mysqli_real_escape_string($conn, $_POST['tanggal_printer']);
		$add 	            = addData($nama_printer, $lokasi_printer, $ip_printer, $status_printer, $tanggal_printer);
		session_start();
		unset ($_SESSION["message"]);
		if ($add) {	
			$_SESSION['message'] = $added;
		}else {
			$_SESSION['message'] = $added_failed;
		}
		header("location:../data_printer.php");

	}elseif (isset($_POST['edit'])) {
		$id_printer			= mysqli_real_escape_string($conn, $_POST['id_printer']);
        $nama_printer	    = mysqli_real_escape_string($conn, $_POST['nama_printer']);
        $lokasi_printer	    = mysqli_real_escape_string($conn, $_POST['lokasi_printer']);
		$ip_printer	        = mysqli_real_escape_string($conn, $_POST['ip_printer']);
        $status_printer	    = mysqli_real_escape_string($conn, $_POST['status_printer']);
		$tanggal_printer    = mysqli_real_escape_string($conn, $_POST['tanggal_printer']);
		$edit 		        = editData($id_printer, $nama_printer, $lokasi_printer, $ip_printer, $status_printer, $tanggal_printer);
		session_start();
		unset ($_SESSION["message"]);
		if ($edit) {			
			$_SESSION['message'] = $edited;
		}else {
			$_SESSION['message'] = $failed;
		}
		header("location:../data_printer.php");

	}elseif (isset($_GET['hapus'])) {
		$id_printer    = mysqli_real_escape_string($conn, $_GET['hapus']);
		$delete = deleteData($id_printer);
		session_start();
		unset ($_SESSION["message"]);
		if ($delete) {			
			$_SESSION['message'] = $deleted;
		}else {
			$_SESSION['message'] = $failed;
		}
		header("location:../data_printer.php");
	}
?>