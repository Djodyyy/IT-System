<?php
require_once 'koneksi.php';
require_once 'function_ip.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['ok' => false, 'error' => 'DB connection failed']);
    exit;
}

$ip = isset($_GET['ip']) ? trim($_GET['ip']) : '';
$exclude_id = isset($_GET['exclude_id']) ? (int)$_GET['exclude_id'] : null;

if ($ip === '') {
    echo json_encode(['ok' => false, 'error' => 'IP kosong']);
    exit;
}

$exists = findIpByAddress($ip, $exclude_id);

if ($exists) {
    echo json_encode([
        'ok' => true,
        'exists' => true,
        'user_ip' => $exists['user_ip'],
        'status_ip' => $exists['status_ip'],
        'id_ip' => $exists['id_ip']
    ]);
} else {
    echo json_encode(['ok' => true, 'exists' => false]);
}
