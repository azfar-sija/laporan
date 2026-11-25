<?php
// AUTH: logout admin secara aman.
require __DIR__ . '/includes/init.php';

$auth->logout();
setPesan('sukses', 'Anda telah berhasil logout.');
redirect('index.php');
