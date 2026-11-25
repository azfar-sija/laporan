<?php
// Kelas Auth = menangani autentikasi Admin berbasis session dan database (PDO).

class Auth
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Cari admin berdasarkan username, verifikasi password, dan simpan session jika valid.
     */
    public function login(string $username, string $password): bool
    {
        $username = trim($username);
        if ($username === '' || $password === '') {
            return false;
        }

        $stmt = $this->db->prepare("SELECT id_admin, username, password_hash, nama_lengkap FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Regenerasi ID session untuk mencegah session fixation jika header belum terkirim
            if (!headers_sent() && session_status() === PHP_SESSION_ACTIVE) {
                session_regenerate_id(true);
            }

            $_SESSION['admin'] = [
                'id_admin'     => (int) $admin['id_admin'],
                'username'     => $admin['username'],
                'nama_lengkap' => $admin['nama_lengkap'],
            ];
            return true;
        }

        return false;
    }

    /**
     * Hapus sesi admin secara aman.
     */
    public function logout(): void
    {
        unset($_SESSION['admin']);
        if (!headers_sent() && session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    /**
     * Cek apakah saat ini ada admin yang sedang login.
     */
    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['admin']['id_admin']);
    }

    /**
     * Ambil data admin yang sedang login (null jika belum login).
     */
    public function user(): ?array
    {
        return $_SESSION['admin'] ?? null;
    }

    /**
     * Pastikan pengguna sudah login sebagai admin.
     * Jika belum, redirect ke login.php disertai flash message gagal.
     */
    public function wajibLogin(): void
    {
        if (!$this->isLoggedIn()) {
            setPesan('gagal', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
            redirect('login.php');
        }
    }
}
