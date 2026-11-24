# Penjelasan JOIN (bahan demonstrasi)

## 1. Kenapa perlu JOIN?
Tabel `laporan_kerusakan` hanya menyimpan **`id_fasilitas`** (angka), bukan nama fasilitas. Nama, lokasi, dan
kategorinya ada di tabel lain. Untuk menampilkan tabel yang bisa dibaca manusia, tabel-tabel itu harus digabung
dengan **JOIN**, memakai Foreign Key sebagai penghubung.

## 2. Query yang dipakai (`Laporan::semua()` di `src/Laporan.php`)
```sql
SELECT l.id_laporan, l.nama_pelapor, l.prioritas, l.status, l.tanggal_laporan,
       f.nama_fasilitas, f.lokasi,
       k.nama_kategori
FROM laporan_kerusakan AS l
JOIN fasilitas AS f          ON f.id_fasilitas = l.id_fasilitas
JOIN kategori_fasilitas AS k ON k.id_kategori  = f.id_kategori
ORDER BY l.id_laporan DESC;
```

Baris demi baris:
| Bagian | Artinya |
|---|---|
| `FROM laporan_kerusakan AS l` | Mulai dari tabel laporan. `l` adalah nama singkat (alias). |
| `JOIN fasilitas AS f ON f.id_fasilitas = l.id_fasilitas` | Hubungkan setiap laporan dengan fasilitasnya: baris yang `id_fasilitas`-nya sama dipasangkan. |
| `JOIN kategori_fasilitas AS k ON k.id_kategori = f.id_kategori` | Dari fasilitas, lanjut ke kategorinya. Jadi rantainya laporan -> fasilitas -> kategori. |
| `SELECT l..., f..., k...` | Ambil kolom dari ketiga tabel. Awalan `l.`, `f.`, `k.` menunjukkan kolom itu milik tabel mana. |
| `ORDER BY l.id_laporan DESC` | Laporan terbaru paling atas. |

`JOIN` tanpa kata lain adalah **INNER JOIN**: hanya baris yang punya pasangan di kedua tabel yang tampil.
Di sini aman, karena Foreign Key menjamin setiap laporan pasti punya fasilitas, dan setiap fasilitas pasti
punya kategori.

JOIN kedua ada di `Laporan::semuaFasilitas()`, untuk dropdown "Fasilitas yang Rusak" di form
(fasilitas + kategori, ditampilkan sebagai `AC Panasonic – Lab Komputer 2 (Elektronik)`).

## 3. Contoh hasil
| id_laporan | nama_fasilitas | lokasi | nama_kategori |
|---|---|---|---|
| 3 | AC Panasonic | Lab Komputer 2 | Elektronik |
| 21 | AC Panasonic | Lab Komputer 2 | Elektronik |

Laporan 3 dan 21 menunjuk ke **satu baris fasilitas yang sama**. Nama AC dan lokasinya hanya tersimpan sekali
di tabel `fasilitas`. Inilah manfaat normalisasi.

## 4. Kemungkinan pertanyaan penguji
**Apa beda INNER JOIN dan LEFT JOIN?**
INNER JOIN hanya menampilkan baris yang cocok di kedua tabel. LEFT JOIN menampilkan semua baris tabel kiri, dan
kolom tabel kanan diisi NULL kalau tidak ada pasangannya. Contoh di data ini: ada 26 fasilitas, tetapi hanya 20
yang pernah dilaporkan.
```sql
-- hanya fasilitas yang punya laporan (20 fasilitas)
SELECT f.nama_fasilitas FROM fasilitas f JOIN laporan_kerusakan l ON l.id_fasilitas = f.id_fasilitas;

-- semua fasilitas, termasuk yang belum pernah dilaporkan (26 fasilitas)
SELECT f.nama_fasilitas, l.id_laporan FROM fasilitas f LEFT JOIN laporan_kerusakan l ON l.id_fasilitas = f.id_fasilitas;
```

**Fasilitas mana yang belum pernah dilaporkan rusak?**
```sql
SELECT f.nama_fasilitas, f.lokasi
FROM fasilitas f
LEFT JOIN laporan_kerusakan l ON l.id_fasilitas = f.id_fasilitas
WHERE l.id_laporan IS NULL;
```

**Berapa laporan per fasilitas?**
```sql
SELECT f.nama_fasilitas, f.lokasi, COUNT(l.id_laporan) AS jumlah_laporan
FROM fasilitas f
LEFT JOIN laporan_kerusakan l ON l.id_fasilitas = f.id_fasilitas
GROUP BY f.id_fasilitas
ORDER BY jumlah_laporan DESC;
```

**Kenapa nama fasilitas tidak disimpan langsung di tabel laporan saja?**
Karena akan redundan: nama dan lokasi yang sama tertulis berulang di tiap laporan, mudah tidak konsisten
("AC Panasonic" vs "Ac panasonic"), dan kalau lokasi berpindah harus mengubah banyak baris. Dengan tabel
terpisah, data fasilitas diubah di satu tempat.

**Apa yang terjadi kalau fasilitas yang punya laporan dihapus?**
Ditolak database, karena Foreign Key memakai `ON DELETE RESTRICT` (error 1451). Ini mencegah laporan yatim.

**Apa yang terjadi kalau laporan dibuat untuk id_fasilitas yang tidak ada?**
Ditolak dua kali: oleh validasi PHP (`Laporan::validasi()`), dan kalau lolos, oleh Foreign Key (error 1452).

**Apa fungsi alias (`AS l`, `AS f`)?**
Menyingkat penulisan nama tabel, dan membedakan kolom yang namanya sama di dua tabel.

**Kalau diminta mengubah kode saat ujian, di mana letaknya?**
| Permintaan | Yang diubah |
|---|---|
| Tampilkan kolom baru di tabel | `SELECT` di `Laporan::semua()` + `<th>` dan `<td>` di `index.php` |
| Filter laporan berdasarkan status | Tambah `WHERE l.status = ?` di `Laporan::semua()` (prepared statement) |
| Urutkan berdasarkan prioritas | Ubah `ORDER BY` di `Laporan::semua()` |
| Tambah kolom di form | `database.sql`, `form.php`, `tambah()`/`ubah()`/`bersihkan()`/`validasi()` di `Laporan.php` |
