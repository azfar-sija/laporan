<?php

class Laporan
{

    public const PRIORITAS = ['Rendah', 'Sedang', 'Tinggi'];
    public const STATUS    = ['Menunggu', 'Diproses', 'Selesai'];

    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    public function semua(): array
    {
        $sql = "SELECT l.id_laporan, l.nama_pelapor, l.prioritas, l.status, l.tanggal_laporan,
                    f.nama_fasilitas, f.lokasi,
                    k.nama_kategori
                FROM laporan_kerusakan AS l
                JOIN fasilitas AS f          ON f.id_fasilitas = l.id_fasilitas
                JOIN kategori_fasilitas AS k ON k.id_kategori  = f.id_kategori
                ORDER BY l.id_laporan DESC";
        return $this->db->query($sql)->fetchAll();
    }


    public function cari(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM laporan_kerusakan WHERE id_laporan = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }


    public function semuaFasilitas(): array
    {
        $sql = "SELECT f.id_fasilitas, f.nama_fasilitas, f.lokasi, k.nama_kategori
                FROM fasilitas AS f
                JOIN kategori_fasilitas AS k ON k.id_kategori = f.id_kategori
                ORDER BY f.nama_fasilitas, f.lokasi";
        return $this->db->query($sql)->fetchAll();
    }



    public function tambah(array $data): void
    {
        $sql = "INSERT INTO laporan_kerusakan
                    (nama_pelapor, id_fasilitas, deskripsi, prioritas, status)
                VALUES (?, ?, ?, ?, ?)";
        $this->db->prepare($sql)->execute([
            $data['nama_pelapor'],
            (int) $data['id_fasilitas'],
            $data['deskripsi'],
            $data['prioritas'],
            $data['status'],
        ]);
    }


    public function ubah(int $id, array $data): void
    {
        $sql = "UPDATE laporan_kerusakan
                SET nama_pelapor = ?, id_fasilitas = ?,
                    deskripsi = ?, prioritas = ?, status = ?
                WHERE id_laporan = ?";
        $this->db->prepare($sql)->execute([
            $data['nama_pelapor'],
            (int) $data['id_fasilitas'],
            $data['deskripsi'],
            $data['prioritas'],
            $data['status'],
            $id,
        ]);
    }


    public function ubahStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE laporan_kerusakan SET status = ? WHERE id_laporan = ?");
        $stmt->execute([$status, $id]);
    }


    public function hapus(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM laporan_kerusakan WHERE id_laporan = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }


    public function validasi(array $data): array
    {
        $error = [];

        $wajib = [
            'nama_pelapor' => ['Nama pelapor', 100],
            'deskripsi'    => ['Deskripsi', 1000],
        ];
        foreach ($wajib as $field => [$label, $maks]) {
            $nilai = trim((string) ($data[$field] ?? ''));
            if ($nilai === '') {
                $error[$field] = "$label wajib diisi.";
            } elseif (mb_strlen($nilai) > $maks) {
                $error[$field] = "$label maksimal $maks karakter.";
            }
        }


        $idFasilitas = (int) ($data['id_fasilitas'] ?? 0);
        $idValid     = array_map('intval', array_column($this->semuaFasilitas(), 'id_fasilitas'));
        if (!in_array($idFasilitas, $idValid, true)) {
            $error['id_fasilitas'] = 'Pilih fasilitas yang tersedia.';
        }

        if (!in_array($data['prioritas'] ?? '', self::PRIORITAS, true)) {
            $error['prioritas'] = 'Prioritas tidak valid.';
        }
        if (!in_array($data['status'] ?? '', self::STATUS, true)) {
            $error['status'] = 'Status tidak valid.';
        }

        return $error;
    }


    public static function bersihkan(array $post): array
    {
        $hasil = [];
        foreach (['nama_pelapor', 'deskripsi'] as $f) {
            $hasil[$f] = trim((string) ($post[$f] ?? ''));
        }
        foreach (['id_fasilitas', 'prioritas', 'status'] as $f) {
            $hasil[$f] = (string) ($post[$f] ?? '');
        }
        return $hasil;
    }
}
