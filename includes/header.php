<?php
// Bagian atas halaman. Set $judul sebelum require file ini.
$judul    = $judul ?? 'Laporan Kerusakan Fasilitas';
$pesan    = ambilPesan();
$isAdmin  = isLoggedIn();
$user     = $isAdmin ? $auth->user() : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= escape($judul) ?> | Laporan Fasilitas</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<header class="site-header">
    <div class="header-inner">
        <a href="index.php" class="brand">
            <span class="brand-icon">&#9881;</span>
            <span class="brand-title">Laporan Fasilitas</span>
        </a>

        <nav class="header-nav">
            <a href="tambah.php" class="btn btn-primary btn-sm">+ Tambah Laporan</a>
            <?php if ($isAdmin): ?>
                <div class="user-info">
                    <span>Admin:</span>
                    <strong class="user-name"><?= escape($user['nama_lengkap'] ?? 'Administrator') ?></strong>
                </div>
                <a href="logout.php" class="btn btn-outline btn-sm">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline btn-sm">Login Admin</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="site-main">
<?php if ($pesan): ?>
    <div class="pesan <?= escape($pesan['tipe']) ?>" role="alert">
        <?= escape($pesan['teks']) ?>
    </div>
<?php endif; ?>
