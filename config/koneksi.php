<?php

$host     = '127.0.0.1';
$namaDb   = 'laporan_fasilitas_db';
$user     = 'root';
$password = '';

try {
    $db = new PDO(
        "mysql:host=$host;dbname=$namaDb;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Koneksi database gagal: ' . $e->getMessage());
    http_response_code(500);
    exit(
        '<h1>Koneksi database gagal</h1>'
        . '<p>Pastikan MySQL sudah berjalan dan database <code>' . htmlspecialchars($namaDb) . '</code> '
        . 'sudah diimpor dari <code>database.sql</code>.</p>'
        . '<p>Cek juga user dan password di <code>config/koneksi.php</code>.</p>'
    );
}
