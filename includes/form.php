<?php

?>
<div class="card card-form">
    <div class="card-header">
        <h2><?= escape($judul) ?></h2>
        <p class="teks-muted">Lengkapi formulir di bawah ini dengan informasi yang valid.</p>
    </div>

    <form method="POST" novalidate>
        <div class="form-group">
            <label for="nama_pelapor">Nama Pelapor</label>
            <input type="text" id="nama_pelapor" name="nama_pelapor" maxlength="100" required
                   value="<?= escape($nilai['nama_pelapor']) ?>" placeholder="Contoh: Budi Santoso">
            <?php if (isset($error['nama_pelapor'])): ?>
                <small class="error"><?= escape($error['nama_pelapor']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="id_fasilitas">Fasilitas yang Rusak</label>
            <select id="id_fasilitas" name="id_fasilitas" required>
                <option value="">-- Pilih Fasilitas --</option>
                <?php foreach ($fasilitas as $f): ?>
                    <option value="<?= (int) $f['id_fasilitas'] ?>"
                        <?= (int) $nilai['id_fasilitas'] === (int) $f['id_fasilitas'] ? 'selected' : '' ?>>
                        <?= escape($f['nama_fasilitas']) ?> &ndash; <?= escape($f['lokasi']) ?> (<?= escape($f['nama_kategori']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($error['id_fasilitas'])): ?>
                <small class="error"><?= escape($error['id_fasilitas']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="prioritas">Prioritas</label>
            <select id="prioritas" name="prioritas">
                <?php foreach (Laporan::PRIORITAS as $p): ?>
                    <option <?= $nilai['prioritas'] === $p ? 'selected' : '' ?>><?= escape($p) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($error['prioritas'])): ?>
                <small class="error"><?= escape($error['prioritas']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <?php foreach (Laporan::STATUS as $s): ?>
                    <option <?= $nilai['status'] === $s ? 'selected' : '' ?>><?= escape($s) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($error['status'])): ?>
                <small class="error"><?= escape($error['status']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi Kerusakan</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="1000" required
                      placeholder="Jelaskan detail kerusakan fasilitas..."><?= escape($nilai['deskripsi']) ?></textarea>
            <?php if (isset($error['deskripsi'])): ?>
                <small class="error"><?= escape($error['deskripsi']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-aksi">
            <button type="submit" class="btn btn-primary"><?= escape($tombol) ?></button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
