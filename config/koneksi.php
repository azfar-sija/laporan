<?php
// Koneksi database memakai PDO.
// Kalau MySQL kamu memakai password / port lain, ubah nilai di bawah ini saja.

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
            // Kalau query salah, PHP melempar exception (bukan diam-diam gagal)
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Hasil query berupa array asosiatif: $baris['nama_fasilitas']
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Pakai prepared statement asli dari MySQL (lebih aman dari SQL injection)
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Detail error disimpan di log server, pengguna cukup melihat pesan ramah
    error_log('Koneksi database gagal: ' . $e->getMessage());
    http_response_code(500);
    exit(
        '<h1>Koneksi database gagal</h1>'
        . '<p>Pastikan MySQL sudah berjalan dan database <code>' . htmlspecialchars($namaDb) . '</code> '
        . 'sudah diimpor dari <code>database.sql</code>.</p>'
        . '<p>Cek juga user dan password di <code>config/koneksi.php</code>.</p>'
    );
}
