<?php
// UPDATE: pilih satu laporan, tampilkan di form, simpan perubahannya.
require __DIR__ . '/includes/init.php';

$id   = (int) ($_GET['id'] ?? 0);
$data = $id > 0 ? $laporan->cari($id) : null;

if (!$data) {
    setPesan('gagal', 'Laporan tidak ditemukan.');
    redirect('index.php');
}

$fasilitas = $laporan->semuaFasilitas();
$error    = [];
$nilai    = $data;   // form langsung terisi data lama dari database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilai = Laporan::bersihkan($_POST);
    $error = $laporan->validasi($nilai);

    if (!$error) {
        try {
            $laporan->ubah($id, $nilai);
            setPesan('sukses', 'Laporan berhasil diperbarui.');
            redirect('index.php');
        } catch (PDOException $e) {
            error_log($e->getMessage());
            setPesan('gagal', 'Perubahan gagal disimpan. Coba lagi.');
        }
    }
}

$judul  = 'Edit Laporan #' . $id;
$tombol = 'Simpan Perubahan';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/form.php';
require __DIR__ . '/includes/footer.php';
