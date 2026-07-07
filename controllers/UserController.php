<?php
class UserController extends BaseController
{
    public function index(): void
    {
        $this->auth->requireRole('admin');
        $userModel = new User($this->db);

        $this->view('admin/users/index', [
            'title' => 'Kelola User',
            'users' => $userModel->getAllWithPelanggan(),
            'totalAdmin' => $userModel->countByRole('admin'),
            'totalPelanggan' => $userModel->countByRole('pelanggan'),
        ]);
    }

    public function updateRole(): void
    {
        $this->auth->requireRole('admin');

        if (!is_post()) {
            redirect('admin/users');
        }

        $id = (int)($_POST['id'] ?? 0);
        $role = trim($_POST['role'] ?? '');
        $currentUserId = (int)($_SESSION['user']['id'] ?? 0);
        $userModel = new User($this->db);
        $targetUser = $userModel->findById($id);

        if (!$targetUser) {
            set_flash('danger', 'User tidak ditemukan.');
            redirect('admin/users');
        }

        if (!in_array($role, ['admin', 'pelanggan'], true)) {
            set_flash('danger', 'Role tidak valid.');
            redirect('admin/users');
        }

        if ($id === $currentUserId) {
            set_flash('warning', 'Anda tidak dapat mengubah role akun yang sedang digunakan sendiri.');
            redirect('admin/users');
        }

        if ($targetUser['role'] === 'admin' && $role === 'pelanggan' && $userModel->countByRole('admin') <= 1) {
            set_flash('warning', 'Role tidak bisa diubah karena minimal harus ada satu admin aktif.');
            redirect('admin/users');
        }

        if ($role === 'pelanggan' && !$userModel->hasPelangganProfile($id)) {
            set_flash('warning', 'User admin ini belum memiliki profil pelanggan, sehingga tidak bisa langsung diubah menjadi pelanggan.');
            redirect('admin/users');
        }

        $userModel->updateRole($id, $role);
        set_flash('success', 'Role user berhasil diperbarui.');
        redirect('admin/users');
    }
}
