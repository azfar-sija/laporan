# Sistem Pelaporan Kerusakan Fasilitas

Website CRUD untuk mencatat dan memantau laporan kerusakan fasilitas sekolah (proyektor, AC, kursi, keran, dan lain-lain). Dibuat dengan **PHP Native, MySQL, HTML5, Vanilla CSS murni, dan PDO**.

## Fitur & Hak Akses

Aplikasi menerapkan model **Public Report, Protected Admin**:

| Peran | Halaman yang Dapat Diakses | Fitur & Hak Akses |
|---|---|---|
| **Publik** (Tanpa Login) | `index.php`, `tambah.php`, `login.php` | - Melihat daftar laporan kerusakan (kolom Aksi & form ubah status disembunyikan)<br>- Menambahkan laporan kerusakan fasilitas baru secara mandiri |
| **Admin** (Wajib Login) | Semua halaman (`index.php`, `tambah.php`, `edit.php`, `ubah-status.php`, `hapus.php`, `logout.php`) | - Mengubah status laporan langsung dari tabel (`ubah-status.php`)<br>- Mengedit detail laporan (`edit.php`)<br>- Menghapus laporan kerusakan (`hapus.php`)<br>- Logout dari sesi admin (`logout.php`) |

### Akun Login Admin Default
- **URL Login:** `login.php` (atau klik tombol **Login Admin** di navbar)
- **Username:** `admin`
- **Password:** `admin123`
- **Nama Lengkap:** `Administrator Sarpras`
- Password tersimpan dalam database dengan enkripsi `password_hash()` (algoritma `PASSWORD_DEFAULT` / BCRYPT).

### Keamanan & Standar Kode
- **Keamanan:** Semua query menggunakan PDO *Prepared Statements* (pencegahan SQL Injection), output HTML di-escape menggunakan `escape()` (`htmlspecialchars`), password diverifikasi dengan `password_verify()`, serta regenerasi session ID saat login/logout.
- **Validasi:** Pengecekan field wajib, panjang karakter maksimal, validitas referensi foreign key fasilitas, serta validitas opsi ENUM prioritas dan status.
- **Routing & Proteksi:** File `edit.php`, `ubah-status.php`, dan `hapus.php` diproteksi ketat menggunakan `wajibLogin()`. Pengunjung yang belum login akan diarahkan ke `login.php` disertai flash message.
- **Desain:** Tampilan Clean & Minimalis modern menggunakan **Vanilla CSS murni** (`css/app.css`) tanpa ketergantungan library/framework eksternal.

---

## Kebutuhan Sistem
- PHP 8.0 atau lebih baru (ekstensi `pdo_mysql` dan `mbstring` aktif)
- MySQL 5.7+ atau MariaDB 10.4+
- Web browser modern

## Cara Menjalankan
1. Nyalakan MySQL & Apache (di XAMPP: klik Start pada **Apache** dan **MySQL**).
2. Impor `database.sql` lewat phpMyAdmin atau klien database (DBeaver/HeidiSQL).
   Ini membuat database `laporan_fasilitas_db` berisi 4 tabel (`kategori_fasilitas`, `fasilitas`, `laporan_kerusakan`, dan `admin`), 26 fasilitas, 23 laporan contoh, dan 1 akun admin default.
3. Tempatkan folder proyek di direktori web server (misal `c:/xampp/htdocs/laporan-fasilitas`).
4. Buka di browser: `http://localhost/laporan-fasilitas` (atau jalankan `php -S localhost:8000` di terminal dan buka `http://localhost:8000`).
5. Jika user/password MySQL berbeda, sesuaikan pengaturannya di `config/koneksi.php`.

---

## Struktur File
```
laporan-fasilitas/
├── config/
│   └── koneksi.php        Koneksi database PDO
├── src/
│   ├── Laporan.php        Query SQL CRUD laporan + validasi & sanitasi
│   └── Auth.php           Logika autentikasi Admin, session, & proteksi rute
├── includes/
│   ├── init.php           Inisialisasi session, DB, objek $laporan & $auth, fungsi helper
│   ├── header.php         Navbar atas responsif, info login admin, & flash messages
│   ├── footer.php         Bagian penutup halaman & copyright
│   └── form.php           Formulir input bersama untuk tambah & edit laporan
├── css/
│   └── app.css            Styling Clean & Minimalist Vanilla CSS (tanpa framework)
├── docs/                  ERD, penjelasan JOIN, logbook, & refleksi
├── index.php              READ: Beranda & daftar laporan (tampilan publik / admin)
├── tambah.php             CREATE: Form tambah laporan (bisa diakses publik)
├── edit.php               UPDATE: Form edit laporan (khusus admin, wajib login)
├── ubah-status.php        UPDATE: Ubah status cepat dari tabel (khusus admin, wajib login)
├── hapus.php              DELETE: Hapus laporan (khusus admin, wajib login via POST)
├── login.php              AUTH: Form login admin
├── logout.php             AUTH: Logout admin & pembersihan session
└── database.sql           Skema tabel (4 tabel 3NF) + data seed awal
```

---

## Database dan Relasi Antar Tabel

![ERD](docs/erd.png)

| Tabel | Primary Key | Foreign Key | Keterangan |
|---|---|---|---|
| `kategori_fasilitas` | `id_kategori` | - | Kategori master: Elektronik, Furnitur, Kebersihan, Bangunan |
| `fasilitas` | `id_fasilitas` | `id_kategori` -> `kategori_fasilitas` | Fasilitas fisik di lokasi tertentu |
| `laporan_kerusakan` | `id_laporan` | `id_fasilitas` -> `fasilitas` | Laporan kerusakan fasilitas dari pelapor |
| `admin` | `id_admin` | - | Akun pengelola sistem (username unik, password hash) |

### Relasi & Normalisasi
- **Relasi:** `kategori_fasilitas (1) -> (N) fasilitas`, dan `fasilitas (1) -> (N) laporan_kerusakan`.
- **Integrity:** Foreign Key menggunakan `ON UPDATE CASCADE ON DELETE RESTRICT`.
- **Normalisasi 3NF:** Data fasilitas dan kategori tidak diduplikasi di tabel laporan; laporan hanya mereferensikan `id_fasilitas`.

### Query JOIN Utama
Halaman `index.php` memanggil `Laporan::semua()` yang melakukan `INNER JOIN` 3 tabel:
```sql
SELECT l.id_laporan, l.nama_pelapor, l.prioritas, l.status, l.tanggal_laporan,
       f.nama_fasilitas, f.lokasi, k.nama_kategori
FROM laporan_kerusakan AS l
JOIN fasilitas AS f          ON f.id_fasilitas = l.id_fasilitas
JOIN kategori_fasilitas AS k ON k.id_kategori  = f.id_kategori
ORDER BY l.id_laporan DESC;
```

---

## Alur Pola Post/Redirect/Get (PRG)
1. Browser mengirimkan permintaan `POST` (misal simpan laporan, ubah status, hapus, login).
2. Server memproses query melalui model (`Laporan` atau `Auth`).
3. Server menyimpan flash message ke dalam `$_SESSION['pesan']`.
4. Server merespons dengan header HTTP `Location: index.php` (atau rute tujuan).
5. Browser melakukan permintaan `GET` bersih ke halaman tujuan, menampilkan flash message satu kali, sehingga tombol *Refresh* tidak akan menduplikasi pengiriman form.
