<?php
class PelangganController extends BaseController
{
    private Pelanggan $pelangganModel;
    private User $userModel;

    public function __construct(mysqli $db, Auth $auth)
    {
        parent::__construct($db, $auth);
        $this->pelangganModel = new Pelanggan($db);
        $this->userModel = new User($db);
    }

    public function index(): void
    {
        $this->auth->requireRole('admin');
        $keyword = trim($_GET['q'] ?? '');
        $this->view('admin/pelanggan/index', [
            'title' => 'Data Pelanggan',
            'pelanggan' => $this->pelangganModel->getAll($keyword),
            'keyword' => $keyword,
        ]);
    }

    public function create(): void
    {
        $this->auth->requireRole('admin');
        $errors = [];
        if (is_post()) {
            $errors = $this->validateRequired($_POST, [
                'nama' => 'Nama pelanggan',
                'username' => 'Username',
                'email' => 'Email',
                'no_hp' => 'No. HP',
                'alamat' => 'Alamat',
            ]);

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid.';
            }
            if ($email !== '' && $this->userModel->emailExists($email)) {
                $errors[] = 'Email sudah digunakan.';
            }
            if ($username !== '' && $this->userModel->usernameExists($username)) {
                $errors[] = 'Username sudah digunakan.';
            }
            if ($password === '') {
                $password = 'password123';
            }

            if (!$errors) {
                $this->db->begin_transaction();
                try {
                    $userId = $this->userModel->create($username, $email, $password, 'pelanggan');
                    $this->pelangganModel->create($userId, trim($_POST['nama']), trim($_POST['no_hp']), trim($_POST['alamat']));
                    $this->db->commit();
                    set_flash('success', 'Data pelanggan berhasil ditambahkan. Password default jika kosong: password123');
                    redirect('admin/pelanggan');
                } catch (Throwable $e) {
                    $this->db->rollback();
                    $errors[] = 'Gagal menyimpan data: ' . $e->getMessage();
                }
            }
        }

        $this->view('admin/pelanggan/form', [
            'title' => 'Tambah Pelanggan',
            'errors' => $errors,
            'data' => null,
            'action' => 'index.php?page=admin/pelanggan/create'
        ]);
    }

    public function edit(): void
    {
        $this->auth->requireRole('admin');
        $id = (int)($_GET['id'] ?? 0);
        $data = $this->pelangganModel->getById($id);
        if (!$data) {
            set_flash('danger', 'Data pelanggan tidak ditemukan.');
            redirect('admin/pelanggan');
        }

        $errors = [];
        if (is_post()) {
            $errors = $this->validateRequired($_POST, [
                'nama' => 'Nama pelanggan',
                'username' => 'Username',
                'email' => 'Email',
                'no_hp' => 'No. HP',
                'alamat' => 'Alamat',
            ]);
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $userId = (int)$data['user_id'];

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid.';
            }
            if ($email !== '' && $this->userModel->emailExists($email, $userId)) {
                $errors[] = 'Email sudah digunakan.';
            }
            if ($username !== '' && $this->userModel->usernameExists($username, $userId)) {
                $errors[] = 'Username sudah digunakan.';
            }

            if (!$errors) {
                $this->db->begin_transaction();
                try {
                    $this->userModel->update($userId, $username, $email, $password !== '' ? $password : null);
                    $this->pelangganModel->update($id, trim($_POST['nama']), trim($_POST['no_hp']), trim($_POST['alamat']));
                    $this->db->commit();
                    set_flash('success', 'Data pelanggan berhasil diperbarui.');
                    redirect('admin/pelanggan');
                } catch (Throwable $e) {
                    $this->db->rollback();
                    $errors[] = 'Gagal memperbarui data: ' . $e->getMessage();
                }
            }
        }

        $this->view('admin/pelanggan/form', [
            'title' => 'Edit Pelanggan',
            'errors' => $errors,
            'data' => $data,
            'action' => 'index.php?page=admin/pelanggan/edit&id=' . $id
        ]);
    }

    public function delete(): void
    {
        $this->auth->requireRole('admin');
        $id = (int)($_GET['id'] ?? 0);
        try {
            $this->pelangganModel->delete($id);
            set_flash('success', 'Data pelanggan berhasil dihapus.');
        } catch (Throwable $e) {
            set_flash('danger', 'Data pelanggan tidak dapat dihapus karena masih memiliki transaksi.');
        }
        redirect('admin/pelanggan');
    }
}
