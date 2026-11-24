<?php
// File "pembuka" yang dipanggil di paling atas setiap halaman:
//   require __DIR__ . '/includes/init.php';
// Isinya: session, koneksi database, objek $laporan, dan fungsi bantu.

session_start();

require __DIR__ . '/../config/koneksi.php';   // membuat variabel $db (PDO)
require __DIR__ . '/../src/Laporan.php';

$laporan = new Laporan($db);

/** Cegah XSS: ubah karakter HTML khusus sebelum ditampilkan. */
function escape($teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

/** Simpan pesan untuk ditampilkan satu kali di halaman berikutnya. */
function setPesan(string $tipe, string $teks): void
{
    $_SESSION['pesan'] = ['tipe' => $tipe, 'teks' => $teks];   // tipe: sukses | gagal
}

/** Ambil pesan lalu hapus, supaya tidak muncul lagi setelah refresh. */
function ambilPesan(): ?array
{
    $pesan = $_SESSION['pesan'] ?? null;
    unset($_SESSION['pesan']);
    return $pesan;
}

/** Pindah halaman lalu hentikan script. */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}
