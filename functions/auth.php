<?php
// functions/auth.php
// Satu-satunya tempat yang memutuskan "siapa boleh masuk".

// Sesuaikan dengan nama folder di Laragon (C:\laragon\www\IT-SYSTEM)
const BASE_URL = '/IT-SYSTEM/';

// Peta izin halaman: nama file => role yang boleh.
// Halaman yang TIDAK terdaftar di sini = hanya role 1 (admin).
const PAGE_ROLES = [
    'index.php'                => [1, 2, 3], // role 2 & 3 nanti di-redirect oleh header.php
    '404.php'                  => [1, 2, 3],
    'report_komputer.php'      => [1, 2],
    'data_barang_it.php'       => [1, 3],
    'data_peminjaman_it.php'   => [1, 3],
    'report_peminjaman_it.php' => [1, 3],
];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    return ($_SESSION['login'] ?? '') === 'yes';
}

function currentRole(): int
{
    // kolom role di DB bertipe varchar, jadi di-cast ke int
    return (int) ($_SESSION['role'] ?? 0);
}

// Belum login? Tendang ke login.php (atau balas 401 untuk endpoint AJAX)
function requireLogin(bool $asJson = false): void
{
    if (isLoggedIn()) {
        return;
    }
    if ($asJson) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
        exit;
    }
    header('Location: ' . BASE_URL . 'login.php');
    exit; // WAJIB: header() tidak menghentikan script
}

// Sudah login tapi role-nya salah? Tolak dengan 403.
function requireRole(array $allowed, bool $asJson = false): void
{
    requireLogin($asJson);
    if (in_array(currentRole(), $allowed, true)) {
        return;
    }
    http_response_code(403);
    if ($asJson) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Forbidden']);
    } else {
        echo 'Akses ditolak.';
    }
    exit;
}

// Dipakai header.php & header_detail.php: cek halaman yang sedang dibuka
function enforcePageAccess(): void
{
    $page    = basename($_SERVER['PHP_SELF']);
    $allowed = PAGE_ROLES[$page] ?? [1]; // default deny: tak terdaftar = admin saja
    requireRole($allowed);
}