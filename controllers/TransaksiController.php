<?php
class TransaksiController extends BaseController
{
    private Transaksi $transaksiModel;
    private Pelanggan $pelangganModel;
    private Layanan $layananModel;

    public function __construct(mysqli $db, Auth $auth)
    {
        parent::__construct($db, $auth);
        $this->transaksiModel = new Transaksi($db);
        $this->pelangganModel = new Pelanggan($db);
        $this->layananModel = new Layanan($db);
    }

    public function index(): void
    {
        $this->auth->requireRole('admin');
        $keyword = trim($_GET['q'] ?? '');
        $this->view('admin/transaksi/index', [
            'title' => 'Data Transaksi Laundry',
            'transaksi' => $this->transaksiModel->getAll($keyword),
            'keyword' => $keyword,
            'statuses' => Transaksi::STATUSES,
        ]);
    }

    public function create(): void
    {
        $this->auth->requireRole('admin');
        $errors = [];
        if (is_post()) {
            $errors = $this->validateTransactionInput();
            if (!$errors) {
                try {
                    $details = $this->buildDetailsFromPost();
                    $id = $this->transaksiModel->create(
                        (int)$_POST['pelanggan_id'],
                        $_POST['tanggal_masuk'],
                        $_POST['tanggal_selesai_est'],
                        $_POST['status'],
                        $details
                    );
                    set_flash('success', 'Transaksi berhasil dibuat.');
                    redirect('admin/transaksi/detail&id=' . $id);
                } catch (Throwable $e) {
                    $errors[] = $e->getMessage();
                }
            }
        }

        $this->view('admin/transaksi/form', [
            'title' => 'Tambah Transaksi',
            'errors' => $errors,
            'data' => null,
            'detail' => [],
            'pelanggan' => $this->pelangganModel->getAll(),
            'layanan' => $this->layananModel->getActive(),
            'statuses' => Transaksi::STATUSES,
            'action' => 'index.php?page=admin/transaksi/create'
        ]);
    }

    public function edit(): void
    {
        $this->auth->requireRole('admin');
        $id = (int)($_GET['id'] ?? 0);
        $data = $this->transaksiModel->getById($id);
        if (!$data) {
            set_flash('danger', 'Transaksi tidak ditemukan.');
            redirect('admin/transaksi');
        }

        $errors = [];
        if (is_post()) {
            $errors = $this->validateTransactionInput();
            if (!$errors) {
                try {
                    $details = $this->buildDetailsFromPost();
                    $this->transaksiModel->update(
                        $id,
                        (int)$_POST['pelanggan_id'],
                        $_POST['tanggal_masuk'],
                        $_POST['tanggal_selesai_est'],
                        $_POST['status'],
                        $details
                    );
                    set_flash('success', 'Transaksi berhasil diperbarui.');
                    redirect('admin/transaksi/detail&id=' . $id);
                } catch (Throwable $e) {
                    $errors[] = $e->getMessage();
                }
            }
        }

        $this->view('admin/transaksi/form', [
            'title' => 'Edit Transaksi',
            'errors' => $errors,
            'data' => $data,
            'detail' => $this->transaksiModel->getDetail($id),
            'pelanggan' => $this->pelangganModel->getAll(),
            'layanan' => $this->layananModel->getActive(),
            'statuses' => Transaksi::STATUSES,
            'action' => 'index.php?page=admin/transaksi/edit&id=' . $id
        ]);
    }

    public function detail(): void
    {
        $this->auth->requireRole('admin');
        $id = (int)($_GET['id'] ?? 0);
        $data = $this->transaksiModel->getById($id);
        if (!$data) {
            set_flash('danger', 'Transaksi tidak ditemukan.');
            redirect('admin/transaksi');
        }

        $this->view('admin/transaksi/detail', [
            'title' => 'Detail Transaksi',
            'data' => $data,
            'detail' => $this->transaksiModel->getDetail($id),
            'statuses' => Transaksi::STATUSES,
        ]);
    }

    public function updateStatus(): void
    {
        $this->auth->requireRole('admin');
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        try {
            $this->transaksiModel->updateStatus($id, $status);
            set_flash('success', 'Status laundry berhasil diperbarui.');
        } catch (Throwable $e) {
            set_flash('danger', 'Gagal memperbarui status: ' . $e->getMessage());
        }
        $back = $_POST['back'] ?? 'admin/transaksi';
        redirect($back);
    }

    public function delete(): void
    {
        $this->auth->requireRole('admin');
        $id = (int)($_GET['id'] ?? 0);
        $this->transaksiModel->delete($id);
        set_flash('success', 'Transaksi berhasil dihapus.');
        redirect('admin/transaksi');
    }

    private function validateTransactionInput(): array
    {
        $errors = $this->validateRequired($_POST, [
            'pelanggan_id' => 'Pelanggan',
            'tanggal_masuk' => 'Tanggal masuk',
            'tanggal_selesai_est' => 'Estimasi selesai',
            'status' => 'Status',
        ]);

        if (!empty($_POST['tanggal_masuk']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['tanggal_masuk'])) {
            $errors[] = 'Format tanggal masuk tidak valid.';
        }
        if (!empty($_POST['tanggal_selesai_est']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['tanggal_selesai_est'])) {
            $errors[] = 'Format estimasi selesai tidak valid.';
        }
        if (!in_array($_POST['status'] ?? '', Transaksi::STATUSES, true)) {
            $errors[] = 'Status laundry tidak valid.';
        }
        if (empty($_POST['layanan_id']) || !is_array($_POST['layanan_id'])) {
            $errors[] = 'Detail layanan minimal 1 baris.';
        }
        foreach (($_POST['berat'] ?? []) as $berat) {
            if ($berat === '' || !is_numeric($berat) || (float)$berat <= 0) {
                $errors[] = 'Berat/jumlah cucian harus angka lebih dari 0.';
                break;
            }
        }
        return $errors;
    }

    private function buildDetailsFromPost(): array
    {
        $details = [];
        $layananIds = $_POST['layanan_id'] ?? [];
        $berats = $_POST['berat'] ?? [];
        foreach ($layananIds as $index => $layananId) {
            if ((int)$layananId > 0 && isset($berats[$index])) {
                $details[] = [
                    'layanan_id' => (int)$layananId,
                    'berat' => (float)$berats[$index],
                ];
            }
        }
        return $details;
    }
}
