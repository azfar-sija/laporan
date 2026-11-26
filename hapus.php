<?php

require __DIR__ . '/includes/init.php';
wajibLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id_laporan'] ?? 0);

    try {
        if ($id > 0 && $laporan->hapus($id)) {
            setPesan('sukses', 'Laporan berhasil dihapus.');
        } else {
            setPesan('gagal', 'Laporan tidak ditemukan.');
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        setPesan('gagal', 'Laporan gagal dihapus.');
    }
}

redirect('index.php');
