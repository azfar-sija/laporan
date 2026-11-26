<?php

require __DIR__ . '/includes/init.php';

$fasilitas = $laporan->semuaFasilitas();
$error    = [];
$nilai    = [   
    'nama_pelapor' => '', 'id_fasilitas' => '', 'deskripsi' => '',
    'prioritas'    => 'Sedang', 'status'  => 'Menunggu',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilai = Laporan::bersihkan($_POST);        
    $error = $laporan->validasi($nilai);        

    if (!$error) {                              
        try {
            $laporan->tambah($nilai);
            setPesan('sukses', 'Laporan berhasil ditambahkan.');
            redirect('index.php');
        } catch (PDOException $e) {
            error_log($e->getMessage());
            setPesan('gagal', 'Laporan gagal disimpan. Coba lagi.');
        }
    }
}

$judul  = 'Tambah Laporan';
$tombol = 'Simpan';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/form.php';
require __DIR__ . '/includes/footer.php';
