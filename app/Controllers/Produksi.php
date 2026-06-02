<?php

namespace App\Controllers;
use Config\Database;
class Produksi extends BaseController
{
    public function index(): string
       {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT produksi.*, karyawan.nama_staf, bahan_baku.nama_bahan
                             FROM produksi 
                             LEFT JOIN karyawan ON karyawan.nip_karyawan = produksi.nip_karyawan
                             LEFT JOIN bahan_baku ON bahan_baku.id_bahan = produksi.id_bahan");
        $data = [
            'title' => 'Data Produksi - FreshBakery',
            'produksi' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('transaksi/produksi', $data);
    }

    private function nextId($db, string $table, string $column): int
    {
        $row = $db->table($table)->selectMax($column, 'max_id')->get()->getRowArray();
        return ((int) ($row['max_id'] ?? 0)) + 1;
    }

    private function resolveId($db, string $table, string $idColumn, string $nameColumn, ?string $input)
    {
        if (!empty($input)) {
            $row = $db->table($table)
                ->groupStart()
                    ->where($idColumn, $input)
                    ->orLike($nameColumn, $input)
                ->groupEnd()
                ->get()
                ->getRowArray();

            if ($row) {
                return $row[$idColumn];
            }
        }

        $row = $db->table($table)->select($idColumn)->orderBy($idColumn, 'ASC')->get(1)->getRowArray();
        return $row[$idColumn] ?? null;
    }

    public function create()
    {
        $db = Database::connect();

        $id_produksi = $this->request->getPost('ID') ?? $this->request->getPost('Id_Produksi') ?? $this->nextId($db, 'produksi', 'Id_produksi');
        $no_spk = $this->request->getPost('No__SPK') ?? $this->request->getPost('No. SPK') ?? ('SPK/' . date('Y') . '/' . str_pad((string) $id_produksi, 3, '0', STR_PAD_LEFT));
        $tanggal = $this->request->getPost('Tanggal_Produksi') ?? $this->request->getPost('Tanggal Produksi') ?? date('Y-m-d');
        $pic = $this->request->getPost('PIC');
        $bahan = $this->request->getPost('Bahan');
        $hasil_produk = $this->request->getPost('Hasil_Produk') ?? $this->request->getPost('Hasil Produk');
        $varian = $this->request->getPost('Varian');
        $status = $this->request->getPost('Status') ?? 'Proses';

        $db->table('produksi')->insert([
            'Id_produksi' => $id_produksi,
            'nip_karyawan' => $this->resolveId($db, 'karyawan', 'nip_karyawan', 'nama_staf', $pic),
            'id_bahan' => $this->resolveId($db, 'bahan_baku', 'id_bahan', 'nama_bahan', $bahan),
            'no_spk' => $no_spk,
            'tgl_produksi' => $tanggal,
            'hasil_produk' => $hasil_produk,
            'varian' => $varian,
            'status' => $status,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'SPK produksi baru berhasil dibuat!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();

        $tanggal = $this->request->getPost('Tanggal_Produksi') ?? $this->request->getPost('Tanggal Produksi') ?? date('Y-m-d');
        $pic = $this->request->getPost('PIC');
        $bahan = $this->request->getPost('Bahan');
        $hasil_produk = $this->request->getPost('Hasil_Produk') ?? $this->request->getPost('Hasil Produk');
        $varian = $this->request->getPost('Varian');
        $status = $this->request->getPost('Status') ?? 'Proses';

        $db->table('produksi')->where('no_spk', $id)->update([
            'nip_karyawan' => $this->resolveId($db, 'karyawan', 'nip_karyawan', 'nama_staf', $pic),
            'id_bahan' => $this->resolveId($db, 'bahan_baku', 'id_bahan', 'nama_bahan', $bahan),
            'tgl_produksi' => $tanggal,
            'hasil_produk' => $hasil_produk,
            'varian' => $varian,
            'status' => $status,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data produksi berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        $db->table('produksi')->where('no_spk', $id)->delete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data produksi berhasil dihapus!'
        ]);
    }
}
