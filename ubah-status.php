<?php

require __DIR__ . '/includes/init.php';
wajibLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int) ($_POST['id_laporan'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($id <= 0 || !in_array($status, Laporan::STATUS, true)) {
        setPesan('gagal', 'Status tidak valid.');
    } elseif (!$laporan->cari($id)) {
        setPesan('gagal', 'Laporan tidak ditemukan.');
    } else {
        try {
            $laporan->ubahStatus($id, $status);
            setPesan('sukses', "Status laporan #$id diubah menjadi $status.");
        } catch (PDOException $e) {
            error_log($e->getMessage());
            setPesan('gagal', 'Status gagal diubah.');
        }
    }
}

redirect('index.php');
