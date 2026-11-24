<?php
// Bagian atas halaman. Set $judul sebelum require file ini.
$judul = $judul ?? 'Laporan Kerusakan Fasilitas';
$pesan = ambilPesan();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= escape($judul) ?> | Laporan Fasilitas</title>
    <link rel="stylesheet" href="css/simple.min.css">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<header>
    <h1><?= escape($judul) ?></h1>
</header>
<main>
<?php if ($pesan): ?>
    <p class="pesan <?= escape($pesan['tipe']) ?>" role="alert"><?= escape($pesan['teks']) ?></p>
<?php endif; ?>
