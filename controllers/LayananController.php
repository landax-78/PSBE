<?php
class LayananController extends BaseController
{
    private Layanan $layananModel;

    public function __construct(mysqli $db, Auth $auth)
    {
        parent::__construct($db, $auth);
        $this->layananModel = new Layanan($db);
    }

    public function index(): void
    {
        $this->auth->requireRole('admin');
        $keyword = trim($_GET['q'] ?? '');
        $this->view('admin/layanan/index', [
            'title' => 'Data Layanan Laundry',
            'layanan' => $this->layananModel->getAll($keyword),
            'keyword' => $keyword,
        ]);
    }

    public function create(): void
    {
        $this->auth->requireRole('admin');
        $errors = [];
        if (is_post()) {
            $errors = $this->validateRequired($_POST, [
                'nama_layanan' => 'Nama layanan',
                'satuan' => 'Satuan',
                'harga' => 'Harga',
                'estimasi_hari' => 'Estimasi hari',
                'status' => 'Status',
            ]);
            $harga = $_POST['harga'] ?? '';
            $estimasi = $_POST['estimasi_hari'] ?? '';
            $status = $_POST['status'] ?? 'Aktif';

            if ($harga !== '' && !is_numeric($harga)) {
                $errors[] = 'Harga harus berupa angka.';
            }
            if ($estimasi !== '' && (!ctype_digit((string)$estimasi) || (int)$estimasi < 1)) {
                $errors[] = 'Estimasi hari harus berupa angka minimal 1.';
            }
            if (!in_array($status, ['Aktif', 'Nonaktif'], true)) {
                $errors[] = 'Status layanan tidak valid.';
            }

            if (!$errors) {
                $this->layananModel->create(trim($_POST['nama_layanan']), trim($_POST['satuan']), (float)$harga, (int)$estimasi, $status);
                set_flash('success', 'Data layanan berhasil ditambahkan.');
                redirect('admin/layanan');
            }
        }

        $this->view('admin/layanan/form', [
            'title' => 'Tambah Layanan',
            'errors' => $errors,
            'data' => null,
            'action' => 'index.php?page=admin/layanan/create'
        ]);
    }

    public function edit(): void
    {
        $this->auth->requireRole('admin');
        $id = (int)($_GET['id'] ?? 0);
        $data = $this->layananModel->getById($id);
        if (!$data) {
            set_flash('danger', 'Data layanan tidak ditemukan.');
            redirect('admin/layanan');
        }

        $errors = [];
        if (is_post()) {
            $errors = $this->validateRequired($_POST, [
                'nama_layanan' => 'Nama layanan',
                'satuan' => 'Satuan',
                'harga' => 'Harga',
                'estimasi_hari' => 'Estimasi hari',
                'status' => 'Status',
            ]);
            $harga = $_POST['harga'] ?? '';
            $estimasi = $_POST['estimasi_hari'] ?? '';
            $status = $_POST['status'] ?? 'Aktif';

            if ($harga !== '' && !is_numeric($harga)) {
                $errors[] = 'Harga harus berupa angka.';
            }
            if ($estimasi !== '' && (!ctype_digit((string)$estimasi) || (int)$estimasi < 1)) {
                $errors[] = 'Estimasi hari harus berupa angka minimal 1.';
            }
            if (!in_array($status, ['Aktif', 'Nonaktif'], true)) {
                $errors[] = 'Status layanan tidak valid.';
            }

            if (!$errors) {
                $this->layananModel->update($id, trim($_POST['nama_layanan']), trim($_POST['satuan']), (float)$harga, (int)$estimasi, $status);
                set_flash('success', 'Data layanan berhasil diperbarui.');
                redirect('admin/layanan');
            }
        }

        $this->view('admin/layanan/form', [
            'title' => 'Edit Layanan',
            'errors' => $errors,
            'data' => $data,
            'action' => 'index.php?page=admin/layanan/edit&id=' . $id
        ]);
    }

    public function delete(): void
    {
        $this->auth->requireRole('admin');
        $id = (int)($_GET['id'] ?? 0);
        try {
            $this->layananModel->delete($id);
            set_flash('success', 'Data layanan berhasil dihapus.');
        } catch (Throwable $e) {
            set_flash('danger', 'Layanan tidak dapat dihapus karena sudah dipakai pada transaksi. Ubah status menjadi Nonaktif jika layanan tidak digunakan lagi.');
        }
        redirect('admin/layanan');
    }
}
