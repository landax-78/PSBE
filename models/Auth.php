<?php
class Auth
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function login(string $identity, string $password): bool
    {
        $user = $this->userModel->findByUsernameOrEmail($identity);
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        session_regenerate_id(true);
        return true;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        session_write_close();
    }

    public function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public function requireLogin(): void
    {
        if (!$this->check()) {
            set_flash('warning', 'Silakan login terlebih dahulu.');
            redirect('login');
        }
    }

    public function requireRole(string $role): void
    {
        $this->requireLogin();
        if (($_SESSION['user']['role'] ?? '') !== $role) {
            set_flash('danger', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            $fallback = ($_SESSION['user']['role'] ?? '') === 'admin' ? 'admin/dashboard' : 'pelanggan/dashboard';
            redirect($fallback);
        }
    }
}
