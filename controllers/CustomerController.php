<?php
class CustomerController extends BaseController
{
    private Pelanggan $pelangganModel;
    private Transaksi $transaksiModel;
    private User $userModel;

    public function __construct(mysqli $db, Auth $auth)
    {
        parent::__construct($db, $auth);
        $this->pelangganModel = new Pelanggan($db);
        $this->transaksiModel = new Transaksi($db);
        $this->userModel = new User($db);
    }

    public function dashboard(): void
    {
        $this->auth->requireRole('pelanggan');
        $pelanggan = $this->pelangganModel->getByUserId((int)$_SESSION['user']['id']);
        $transaksi = $pelanggan ? $this->transaksiModel->getByPelangganId((int)$pelanggan['id']) : [];
        $aktif = array_filter($transaksi, fn($row) => $row['status'] !== 'Diambil');

        $this->view('pelanggan/dashboard', [
            'title' => 'Dashboard Pelanggan',
            'pelanggan' => $pelanggan,
            'transaksi' => array_slice($transaksi, 0, 5),
            'totalTransaksi' => count($transaksi),
            'transaksiAktif' => count($aktif),
        ]);
    }

    public function status(): void
    {
        $this->auth->requireRole('pelanggan');
        $pelanggan = $this->pelangganModel->getByUserId((int)$_SESSION['user']['id']);
        $transaksi = $pelanggan ? $this->transaksiModel->getByPelangganId((int)$pelanggan['id']) : [];
        $aktif = array_values(array_filter($transaksi, fn($row) => $row['status'] !== 'Diambil'));

        $this->view('pelanggan/status', [
            'title' => 'Status Laundry',
            'transaksi' => $aktif,
            'statuses' => Transaksi::STATUSES,
        ]);
    }

    public function riwayat(): void
    {
        $this->auth->requireRole('pelanggan');
        $pelanggan = $this->pelangganModel->getByUserId((int)$_SESSION['user']['id']);
        $transaksi = $pelanggan ? $this->transaksiModel->getByPelangganId((int)$pelanggan['id']) : [];

        $this->view('pelanggan/riwayat', [
            'title' => 'Riwayat Laundry',
            'transaksi' => $transaksi,
        ]);
    }

    public function profil(): void
    {
        $this->auth->requireRole('pelanggan');
        $pelanggan = $this->pelangganModel->getByUserId((int)$_SESSION['user']['id']);
        if (!$pelanggan) {
            set_flash('danger', 'Profil pelanggan tidak ditemukan.');
            redirect('pelanggan/dashboard');
        }

        $errors = [];
        if (is_post()) {
            $errors = $this->validateRequired($_POST, [
                'nama' => 'Nama lengkap',
                'username' => 'Username',
                'email' => 'Email',
                'no_hp' => 'No. HP',
                'alamat' => 'Alamat',
            ]);
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $userId = (int)$pelanggan['user_id'];

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid.';
            }
            if ($email !== '' && $this->userModel->emailExists($email, $userId)) {
                $errors[] = 'Email sudah digunakan akun lain.';
            }
            if ($username !== '' && $this->userModel->usernameExists($username, $userId)) {
                $errors[] = 'Username sudah digunakan akun lain.';
            }

            if (!$errors) {
                $this->db->begin_transaction();
                try {
                    $this->userModel->update($userId, $username, $email, $password !== '' ? $password : null);
                    $this->pelangganModel->update((int)$pelanggan['id'], trim($_POST['nama']), trim($_POST['no_hp']), trim($_POST['alamat']));
                    $_SESSION['user']['username'] = $username;
                    $_SESSION['user']['email'] = $email;
                    $this->db->commit();
                    set_flash('success', 'Profil berhasil diperbarui.');
                    redirect('pelanggan/profil');
                } catch (Throwable $e) {
                    $this->db->rollback();
                    $errors[] = 'Gagal memperbarui profil: ' . $e->getMessage();
                }
            }
        }

        $this->view('pelanggan/profil', [
            'title' => 'Edit Profil',
            'pelanggan' => $pelanggan,
            'errors' => $errors,
        ]);
    }
}
