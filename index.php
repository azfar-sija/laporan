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

$judul = 'Laporan Kerusakan Fasilitas';
require __DIR__ . '/includes/header.php';
?>
<p><a href="tambah.php">+ Tambah Laporan</a></p>

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
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!$daftar): ?>
        <tr><td colspan="8" class="kosong">Belum ada laporan.</td></tr>
    <?php endif; ?>
    <?php foreach ($daftar as $baris): ?>
        <tr>
            <td><?= (int) $baris['id_laporan'] ?></td>
            <td><?= escape($baris['nama_fasilitas']) ?><br><small>oleh <?= escape($baris['nama_pelapor']) ?></small></td>
            <td><?= escape($baris['lokasi']) ?></td>
            <td><?= escape($baris['nama_kategori']) ?></td>
            <td><?= escape($baris['prioritas']) ?></td>
            <td>
                <!-- UPDATE cepat: ubah status langsung dari tabel -->
                <form action="ubah-status.php" method="POST">
                    <input type="hidden" name="id_laporan" value="<?= (int) $baris['id_laporan'] ?>">
                    <select name="status" aria-label="Status">
                        <?php foreach (Laporan::STATUS as $s): ?>
                            <option <?= $baris['status'] === $s ? 'selected' : '' ?>><?= escape($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Ubah</button>
                </form>
            </td>
            <td><?= escape(date('d/m/Y', strtotime($baris['tanggal_laporan']))) ?></td>
            <td class="aksi">
                <a href="edit.php?id=<?= (int) $baris['id_laporan'] ?>">Edit</a>
                <!-- DELETE: lewat POST + konfirmasi -->
                <form action="hapus.php" method="POST" style="display:inline"
                      onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                    <input type="hidden" name="id_laporan" value="<?= (int) $baris['id_laporan'] ?>">
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
