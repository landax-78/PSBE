<?php
class AdminController extends BaseController
{
    public function dashboard(): void
    {
        $this->auth->requireRole('admin');
        $pelangganModel = new Pelanggan($this->db);
        $layananModel = new Layanan($this->db);
        $transaksiModel = new Transaksi($this->db);

        $this->view('admin/dashboard', [
            'title' => 'Dashboard Admin',
            'totalPelanggan' => $pelangganModel->countAll(),
            'totalLayanan' => $layananModel->countAll(),
            'totalTransaksi' => $transaksiModel->countAll(),
            'transaksiAktif' => $transaksiModel->countActive(),
            'pendapatanBulanIni' => $transaksiModel->totalRevenueThisMonth(),
            'latest' => $transaksiModel->getLatest(5),
        ]);
    }
}
