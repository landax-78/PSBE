<?php
class LaporanController extends BaseController
{
    public function index(): void
    {
        $this->auth->requireRole('admin');
        $transaksiModel = new Transaksi($this->db);
        $start = $_GET['start'] ?? date('Y-m-01');
        $end = $_GET['end'] ?? date('Y-m-d');

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) {
            $start = date('Y-m-01');
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) {
            $end = date('Y-m-d');
        }

        $this->view('admin/laporan/index', [
            'title' => 'Laporan Transaksi',
            'start' => $start,
            'end' => $end,
            'laporan' => $transaksiModel->report($start, $end),
            'total' => $transaksiModel->totalReport($start, $end),
        ]);
    }
}
