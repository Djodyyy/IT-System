<?php
require_once __DIR__ . '/auth.php';   // sudah session_start() di dalamnya

$_SESSION = [];                        // kosongkan data session sekarang
if (ini_get('session.use_cookies')) {  // hapus cookie PHPSESSID di browser
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();                     // hapus session di server

header('Location: ' . BASE_URL . 'login.php');
exit;