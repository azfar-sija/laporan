<?php
// Form yang dipakai bersama oleh tambah.php dan edit.php.
// Variabel yang dibutuhkan (disiapkan oleh halaman pemanggil):
//   $nilai    -> isian saat ini (array)
//   $error    -> pesan error per field (array, boleh kosong)
//   $fasilitas -> daftar fasilitas untuk dropdown
//   $tombol   -> teks tombol simpan
?>
<form method="POST" novalidate>
    <label for="nama_pelapor">Nama Pelapor</label>
    <input id="nama_pelapor" name="nama_pelapor" maxlength="100" required
           value="<?= escape($nilai['nama_pelapor']) ?>">
    <?php if (isset($error['nama_pelapor'])): ?><small class="error"><?= escape($error['nama_pelapor']) ?></small><?php endif; ?>

    <label for="id_fasilitas">Fasilitas yang Rusak</label>
    <select id="id_fasilitas" name="id_fasilitas" required>
        <?php foreach ($fasilitas as $f): ?>
            <option value="<?= (int) $f['id_fasilitas'] ?>"
                <?= (int) $nilai['id_fasilitas'] === (int) $f['id_fasilitas'] ? 'selected' : '' ?>>
                <?= escape($f['nama_fasilitas']) ?> &ndash; <?= escape($f['lokasi']) ?> (<?= escape($f['nama_kategori']) ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (isset($error['id_fasilitas'])): ?><small class="error"><?= escape($error['id_fasilitas']) ?></small><?php endif; ?>

    <label for="prioritas">Prioritas</label>
    <select id="prioritas" name="prioritas">
        <?php foreach (Laporan::PRIORITAS as $p): ?>
            <option <?= $nilai['prioritas'] === $p ? 'selected' : '' ?>><?= escape($p) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="status">Status</label>
    <select id="status" name="status">
        <?php foreach (Laporan::STATUS as $s): ?>
            <option <?= $nilai['status'] === $s ? 'selected' : '' ?>><?= escape($s) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="deskripsi">Deskripsi Kerusakan</label>
    <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="1000" required><?= escape($nilai['deskripsi']) ?></textarea>
    <?php if (isset($error['deskripsi'])): ?><small class="error"><?= escape($error['deskripsi']) ?></small><?php endif; ?>

    <p>
        <button type="submit"><?= escape($tombol) ?></button>
        <a href="index.php">Batal</a>
    </p>
</form>
