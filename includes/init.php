<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php'; 
require_once __DIR__ . '/../src/Laporan.php';
require_once __DIR__ . '/../src/Auth.php';

$laporan = new Laporan($db);
$auth    = new Auth($db);

if (!function_exists('escape')) {

    function escape($teks): string
    {
        return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('setPesan')) {
    function setPesan(string $tipe, string $teks): void
    {
        $_SESSION['pesan'] = ['tipe' => $tipe, 'teks' => $teks];   
    }
}

if (!function_exists('ambilPesan')) {

    function ambilPesan(): ?array
    {
        $pesan = $_SESSION['pesan'] ?? null;
        unset($_SESSION['pesan']);
        return $pesan;
    }
}

if (!function_exists('redirect')) {

    function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('isLoggedIn')) {

    function isLoggedIn(): bool
    {
        global $auth;
        return $auth->isLoggedIn();
    }
}

if (!function_exists('wajibLogin')) {

    function wajibLogin(): void
    {
        global $auth;
        $auth->wajibLogin();
    }
}
