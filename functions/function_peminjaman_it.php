<?php
require_once 'koneksi.php';
require_once __DIR__ . '/auth.php';
include_once 'helper.php';


function getPeminjamanAktif()
{
    global $conn;

    $sql = "SELECT p.*, b.nama_barang
            FROM tb_peminjaman_it p
            JOIN tb_barang_it b ON p.id_barang = b.id_barang
            WHERE p.status_peminjaman = 'Dipinjam'
            ORDER BY p.tgl_pinjam DESC";

    $q = mysqli_query($conn, $sql);
    if (!$q) return [];
    return mysqli_fetch_all($q, MYSQLI_ASSOC);
}

function getAllPeminjaman()
{
    global $conn;

    $sql = "SELECT p.*, b.nama_barang
            FROM tb_peminjaman_it p
            JOIN tb_barang_it b ON p.id_barang = b.id_barang
            ORDER BY p.id_peminjaman DESC";

    $q = mysqli_query($conn, $sql);
    if (!$q) return [];
    return mysqli_fetch_all($q, MYSQLI_ASSOC);
}


function addPeminjaman($nama, $id_barang, $keterangan)
{
    global $conn;

    $nama      = trim((string) $nama);
    $id_barang = (int) $id_barang;
    if ($nama === '' || $id_barang <= 0) return false;

    $conn->begin_transaction();
    try {
        // Cek stok + kurangi dalam SATU query => tidak ada celah race condition
        $stmt = $conn->prepare(
            "UPDATE tb_barang_it SET stok = stok - 1 WHERE id_barang = ? AND stok > 0"
        );
        $stmt->bind_param('i', $id_barang);
        $stmt->execute();
        $berhasil = ($stmt->affected_rows === 1);   // 0 = barang tidak ada / stok habis
        $stmt->close();

        if (!$berhasil) {
            $conn->rollback();
            return false;
        }

        $stmt = $conn->prepare(
            "INSERT INTO tb_peminjaman_it
             (nama_peminjam, id_barang, tgl_pinjam, status_peminjaman, keterangan)
             VALUES (?, ?, NOW(), 'Dipinjam', ?)"
        );
        $stmt->bind_param('sis', $nama, $id_barang, $keterangan);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        return true;
    } catch (Throwable $e) {
        $conn->rollback();
        error_log($e->getMessage());     // detail error ke log, bukan ke layar user
        return false;
    }
}

function kembalikanBarang($id)
{
    global $conn;

    $id = (int) $id;
    if ($id <= 0) return false;

    $conn->begin_transaction();
    try {
        // Hanya yang masih 'Dipinjam' yang bisa dikembalikan (cegah klik/replay berulang)
        $stmt = $conn->prepare(
            "UPDATE tb_peminjaman_it
             SET status_peminjaman = 'Dikembalikan', tgl_kembali = NOW()
             WHERE id_peminjaman = ? AND status_peminjaman = 'Dipinjam'"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $berhasil = ($stmt->affected_rows === 1);
        $stmt->close();

        if (!$berhasil) {
            $conn->rollback();
            return false;
        }

        // Tambah stok barang yang terkait dengan peminjaman ini
        $stmt = $conn->prepare(
            "UPDATE tb_barang_it b
             JOIN tb_peminjaman_it p ON p.id_barang = b.id_barang
             SET b.stok = b.stok + 1
             WHERE p.id_peminjaman = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        return true;
    } catch (Throwable $e) {
        $conn->rollback();
        error_log($e->getMessage());
        return false;
    }
}

function getPeminjamanFilter($bulan = null, $dari = null, $sampai = null)
{
    global $conn;

    $where  = [];
    $types  = '';
    $params = [];

    if ($bulan) {   // format: YYYY-MM
        $where[] = "DATE_FORMAT(p.tgl_pinjam, '%Y-%m') = ?";
        $types  .= 's';
        $params[] = $bulan;
    }
    if ($dari && $sampai) {
        $where[] = "DATE(p.tgl_pinjam) BETWEEN ? AND ?";
        $types  .= 'ss';
        $params[] = $dari;
        $params[] = $sampai;
    }

    // Potongan SQL ini murni tulisan kita sendiri; nilai user HANYA lewat '?'
    $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $conn->prepare("
        SELECT p.*, b.nama_barang
        FROM tb_peminjaman_it p
        JOIN tb_barang_it b ON p.id_barang = b.id_barang
        $whereSql
        ORDER BY p.tgl_pinjam DESC
    ");
    if ($params) {
        $stmt->bind_param($types, ...$params);   // ... = "bongkar array jadi argumen"
    }
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data;
}

function deletePeminjaman($id)
{
    global $conn;
    $id   = (int) $id;
    $stmt = $conn->prepare("DELETE FROM tb_peminjaman_it WHERE id_peminjaman = ?");
    $stmt->bind_param('i', $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}



// ==========================================================================
// HANDLER: hanya jalan kalau file ini dipanggil LANGSUNG sebagai endpoint
// (bukan saat di-require oleh halaman lain seperti report_peminjaman_publik.php)
// ==========================================================================
if (basename($_SERVER['PHP_SELF']) == 'function_peminjaman_it.php') {
    requireRole([1, 3]);

    if (isset($_POST['add'])) {
        $ok = addPeminjaman($_POST['nama_peminjam'], $_POST['id_barang'], $_POST['keterangan']);
        header("Location: ../data_peminjaman_it.php?status=" . ($ok ? "success_add" : "error"));
        exit;
    }

    if (isset($_GET['kembali'])) {
        $ok = kembalikanBarang($_GET['kembali']);
        header("Location: ../data_peminjaman_it.php?status=" . ($ok ? "success_return" : "error"));
        exit;
    }

    // Handler hapus sengaja dinonaktifkan (seperti di versi lama)
    // if (isset($_GET['hapus'])) {
    //     $ok = deletePeminjaman($_GET['hapus']);
    //     header("Location: ../data_peminjaman_it.php?status=" . ($ok ? "success_delete" : "error"));
    //     exit;
    // }
}