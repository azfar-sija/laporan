<?php
// READ: menampilkan semua laporan dari database.
require __DIR__ . '/includes/init.php';

try {
    $daftar = $laporan->semua();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $daftar = [];
    setPesan('gagal', 'Data gagal dimuat dari database.');
}

$isAdmin = isLoggedIn();
$judul   = 'Daftar Laporan Kerusakan Fasilitas';
require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
    <div>
        <h2 class="page-title">Daftar Laporan Kerusakan</h2>
        <p class="teks-muted">Memantau dan mengelola laporan kerusakan fasilitas sekolah</p>
    </div>
    <div>
        <a href="tambah.php" class="btn btn-primary">+ Tambah Laporan</a>
    </div>
</div>

<div class="tabel-container">
    <div class="tabel-gulung">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fasilitas</th>
                    <th>Lokasi</th>
                    <th>Kategori</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <?php if ($isAdmin): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php if (!$daftar): ?>
                <tr>
                    <td colspan="<?= $isAdmin ? 8 : 7 ?>" class="kosong">Belum ada laporan kerusakan.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($daftar as $baris): ?>
                <?php
                    $prioritasClass = 'badge-prioritas-' . strtolower($baris['prioritas']);
                    $statusClass    = 'badge-status-' . strtolower($baris['status']);
                ?>
                <tr>
                    <td><strong>#<?= (int) $baris['id_laporan'] ?></strong></td>
                    <td>
                        <strong><?= escape($baris['nama_fasilitas']) ?></strong>
                        <small>Pelapor: <?= escape($baris['nama_pelapor']) ?></small>
                    </td>
                    <td><?= escape($baris['lokasi']) ?></td>
                    <td><?= escape($baris['nama_kategori']) ?></td>
                    <td>
                        <span class="badge <?= $prioritasClass ?>"><?= escape($baris['prioritas']) ?></span>
                    </td>
                    <td>
                        <?php if ($isAdmin): ?>
                            <!-- UPDATE cepat: ubah status langsung dari tabel (Khusus Admin) -->
                            <form action="ubah-status.php" method="POST" class="form-status-inline">
                                <input type="hidden" name="id_laporan" value="<?= (int) $baris['id_laporan'] ?>">
                                <select name="status" aria-label="Status">
                                    <?php foreach (Laporan::STATUS as $s): ?>
                                        <option <?= $baris['status'] === $s ? 'selected' : '' ?>><?= escape($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-sm btn-secondary">Ubah</button>
                            </form>
                        <?php else: ?>
                            <!-- Pengunjung publik hanya melihat badge teks status -->
                            <span class="badge <?= $statusClass ?>"><?= escape($baris['status']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= escape(date('d/m/Y', strtotime($baris['tanggal_laporan']))) ?></td>
                    <?php if ($isAdmin): ?>
                        <td class="aksi">
                            <div class="aksi-buttons">
                                <a href="edit.php?id=<?= (int) $baris['id_laporan'] ?>" class="btn btn-sm btn-outline">Edit</a>
                                <!-- DELETE: lewat POST + konfirmasi -->
                                <form action="hapus.php" method="POST" style="display:inline"
                                      onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                                    <input type="hidden" name="id_laporan" value="<?= (int) $baris['id_laporan'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
