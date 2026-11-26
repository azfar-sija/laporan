<?php


class Auth
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


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


    public function logout(): void
    {
        unset($_SESSION['admin']);
        if (!headers_sent() && session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['admin']['id_admin']);
    }


    public function user(): ?array
    {
        return $_SESSION['admin'] ?? null;
    }


    public function wajibLogin(): void
    {
        if (!$this->isLoggedIn()) {
            setPesan('gagal', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
            redirect('login.php');
        }
    }
}
