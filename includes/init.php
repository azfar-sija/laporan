<?php
// File "pembuka" yang dipanggil di paling atas setiap halaman:
//   require_once __DIR__ . '/includes/init.php';
// Isinya: session, koneksi database, objek $laporan & $auth, dan fungsi bantu.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';   // membuat variabel $db (PDO)
require_once __DIR__ . '/../src/Laporan.php';
require_once __DIR__ . '/../src/Auth.php';

$laporan = new Laporan($db);
$auth    = new Auth($db);

if (!function_exists('escape')) {
    /** Cegah XSS: ubah karakter HTML khusus sebelum ditampilkan. */
    function escape($teks): string
    {
        return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('setPesan')) {
    /** Simpan pesan untuk ditampilkan satu kali di halaman berikutnya. */
    function setPesan(string $tipe, string $teks): void
    {
        $_SESSION['pesan'] = ['tipe' => $tipe, 'teks' => $teks];   // tipe: sukses | gagal
    }
}

if (!function_exists('ambilPesan')) {
    /** Ambil pesan lalu hapus, supaya tidak muncul lagi setelah refresh. */
    function ambilPesan(): ?array
    {
        $pesan = $_SESSION['pesan'] ?? null;
        unset($_SESSION['pesan']);
        return $pesan;
    }
}

if (!function_exists('redirect')) {
    /** Pindah halaman lalu hentikan script. */
    function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('isLoggedIn')) {
    /** Cek apakah admin sedang login. */
    function isLoggedIn(): bool
    {
        global $auth;
        return $auth->isLoggedIn();
    }
}

if (!function_exists('wajibLogin')) {
    /** Proteksi halaman: wajib login sebagai admin. */
    function wajibLogin(): void
    {
        global $auth;
        $auth->wajibLogin();
    }
}
