<?php

namespace App\Controllers;
use Config\Database;
class Pembayaran extends BaseController
{
    public function index(): string
       {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT pembayaran.*, karyawan.nama_staf, pelanggan.nama_lengkap
                             FROM pembayaran 
                             LEFT JOIN karyawan ON karyawan.nip_karyawan = pembayaran.nip_karyawan
                             LEFT JOIN pelanggan ON pelanggan.id_pelanggan = pembayaran.id_pelanggan");
        $data = [
            'title' => 'Data Pembayaran - FreshBakery',
            'pembayaran' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('transaksi/pembayaran', $data);
    }

    private function nextId($db, string $table, string $column): int
    {
        $row = $db->table($table)->selectMax($column, 'max_id')->get()->getRowArray();
        return ((int) ($row['max_id'] ?? 0)) + 1;
    }

    private function resolveKaryawan($db, ?string $input): int
    {
        if (!empty($input)) {
            $row = $db->table('karyawan')
                ->groupStart()
                    ->where('nip_karyawan', $input)
                    ->orLike('nama_staf', $input)
                ->groupEnd()
                ->get()
                ->getRowArray();

            if ($row) {
                return (int) $row['nip_karyawan'];
            }
        }

        $row = $db->table('karyawan')->select('nip_karyawan')->orderBy('nip_karyawan', 'ASC')->get(1)->getRowArray();
        return (int) ($row['nip_karyawan'] ?? 0);
    }

    private function resolvePelanggan($db, ?string $input): string
    {
        if (!empty($input)) {
            $row = $db->table('pelanggan')
                ->groupStart()
                    ->where('Id_pelanggan', $input)
                    ->orLike('nama_lengkap', $input)
                ->groupEnd()
                ->get()
                ->getRowArray();

            if ($row) {
                return $row['Id_pelanggan'];
            }
        }

        $row = $db->table('pelanggan')->select('Id_pelanggan')->orderBy('Id_pelanggan', 'ASC')->get(1)->getRowArray();
        return $row['Id_pelanggan'] ?? '';
    }

    private function normalizeMetode(?string $metode): string
    {
        if ($metode === 'Transfer Bank' || $metode === 'Transfer') {
            return 'Transer Bank';
        }

        $allowed = ['Tunai', 'Transer Bank', 'Kartu Debit', 'Qris', 'E-Wallet', 'Virtual Account (VA)'];
        return in_array($metode, $allowed, true) ? $metode : 'Tunai';
    }

    public function create()
    {
        $db = Database::connect();

        $id_pembayaran = $this->request->getPost('ID') ?? $this->request->getPost('Id_pembayaran') ?? $this->nextId($db, 'pembayaran', 'Id_pembayaran');
        $metode = $this->normalizeMetode($this->request->getPost('Metode_Pembayaran') ?? $this->request->getPost('Metode Pembayaran'));
        $kasir = $this->request->getPost('Nama_Kasir') ?? $this->request->getPost('Nama Kasir') ?? $this->request->getPost('Kasir');
        $pelanggan = $this->request->getPost('Pelanggan');

        $db->table('pembayaran')->insert([
            'Id_pembayaran' => $id_pembayaran,
            'Metode_pembayaran' => $metode,
            'nip_karyawan' => $this->resolveKaryawan($db, $kasir),
            'Id_pelanggan' => $this->resolvePelanggan($db, $pelanggan),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pembayaran baru berhasil dicatat!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();

        $metode = $this->normalizeMetode($this->request->getPost('Metode_Pembayaran') ?? $this->request->getPost('Metode Pembayaran'));
        $kasir = $this->request->getPost('Nama_Kasir') ?? $this->request->getPost('Nama Kasir') ?? $this->request->getPost('Kasir');
        $pelanggan = $this->request->getPost('Pelanggan');

        $db->table('pembayaran')->where('Id_pembayaran', $id)->update([
            'Metode_pembayaran' => $metode,
            'nip_karyawan' => $this->resolveKaryawan($db, $kasir),
            'Id_pelanggan' => $this->resolvePelanggan($db, $pelanggan),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data pembayaran berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();

        try {
            $db->table('penjualan')->where('Id_pembayaran', $id)->update(['Id_pembayaran' => null]);
            $db->table('pembayaran')->where('Id_pembayaran', $id)->delete();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pembayaran berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ]);
        }
    }
}
