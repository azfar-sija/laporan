<?php

require __DIR__ . '/includes/init.php';


if (isLoggedIn()) {
    redirect('index.php');
}

$error    = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '') {
        $error['username'] = 'Username wajib diisi.';
    }
    if ($password === '') {
        $error['password'] = 'Password wajib diisi.';
    }

    if (!$error) {
        if ($auth->login($username, $password)) {
            $admin = $auth->user();
            setPesan('sukses', 'Selamat datang, ' . ($admin['nama_lengkap'] ?? 'Admin') . '!');
            redirect('index.php');
        } else {
            $error['umum'] = 'Username atau password salah.';
        }
    }
}

$judul = 'Login Admin';
require __DIR__ . '/includes/header.php';
?>
<div class="card-login-wrapper">
    <div class="card card-login">
        <div class="card-header">
            <h2>Masuk sebagai Admin</h2>
            <p class="teks-muted">Silakan masukkan username dan password Anda</p>
        </div>

        <?php if (!empty($error['umum'])): ?>
            <p class="pesan gagal" role="alert"><?= escape($error['umum']) ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" maxlength="50" required autofocus
                    value="<?= escape($username) ?>" placeholder="Masukkan username">
                <?php if (isset($error['username'])): ?>
                    <small class="error"><?= escape($error['username']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required
                    placeholder="Masukkan password">
                <?php if (isset($error['password'])): ?>
                    <small class="error"><?= escape($error['password']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-aksi">
                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </div>
        </form>

        <div class="card-footer-info">
            <p><a href="index.php">&larr; Kembali ke Beranda</a></p>
        </div>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
