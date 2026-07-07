<?php
class AuthController extends BaseController
{
    public function login(): void
    {
        if ($this->auth->check()) {
            $role = $_SESSION['user']['role'];
            redirect($role === 'admin' ? 'admin/dashboard' : 'pelanggan/dashboard');
        }

        $errors = [];
        if (is_post()) {
            $identity = trim($_POST['identity'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $errors = $this->validateRequired($_POST, [
                'identity' => 'Username atau email',
                'password' => 'Password'
            ]);

            if (!$errors && $this->auth->login($identity, $password)) {
                $role = $_SESSION['user']['role'];
                set_flash('success', 'Login berhasil. Selamat datang di CleanWash.');
                redirect($role === 'admin' ? 'admin/dashboard' : 'pelanggan/dashboard');
            }

            if (!$errors) {
                $errors[] = 'Username/email atau password salah.';
            }
        }

        $viewFile = __DIR__ . '/../views/auth/login.php';
        require $viewFile;
    }

    public function register(): void
    {
        if ($this->auth->check()) {
            $role = $_SESSION['user']['role'];
            redirect($role === 'admin' ? 'admin/dashboard' : 'pelanggan/dashboard');
        }

        $errors = [];

        if (is_post()) {
            $errors = $this->validateRequired($_POST, [
                'nama' => 'Nama lengkap',
                'username' => 'Username',
                'email' => 'Email',
                'no_hp' => 'No. HP',
                'alamat' => 'Alamat',
                'password' => 'Password',
                'password_confirm' => 'Konfirmasi password',
            ]);

            $nama = trim($_POST['nama'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $noHp = trim($_POST['no_hp'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $passwordConfirm = trim($_POST['password_confirm'] ?? '');

            $userModel = new User($this->db);
            $pelangganModel = new Pelanggan($this->db);

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid.';
            }

            if ($username !== '' && strlen($username) < 4) {
                $errors[] = 'Username minimal 4 karakter.';
            }

            if ($password !== '' && strlen($password) < 6) {
                $errors[] = 'Password minimal 6 karakter.';
            }

            if ($password !== '' && $passwordConfirm !== '' && $password !== $passwordConfirm) {
                $errors[] = 'Konfirmasi password tidak sama.';
            }

            if ($email !== '' && $userModel->emailExists($email)) {
                $errors[] = 'Email sudah digunakan.';
            }

            if ($username !== '' && $userModel->usernameExists($username)) {
                $errors[] = 'Username sudah digunakan.';
            }

            if (!$errors) {
                $this->db->begin_transaction();
                try {
                    $userId = $userModel->create($username, $email, $password, 'pelanggan');
                    $pelangganModel->create($userId, $nama, $noHp, $alamat);
                    $this->db->commit();

                    set_flash('success', 'Pendaftaran berhasil. Silakan login menggunakan username dan password yang dibuat.');
                    redirect('login');
                } catch (Throwable $e) {
                    $this->db->rollback();
                    $errors[] = 'Pendaftaran gagal: ' . $e->getMessage();
                }
            }
        }

        $viewFile = __DIR__ . '/../views/auth/register.php';
        require $viewFile;
    }

    public function logout(): void
    {
        $this->auth->logout();
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_regenerate_id(true);
        set_flash('success', 'Logout berhasil.');
        redirect('login');
    }
}
