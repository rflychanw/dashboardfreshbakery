<?php

namespace App\Controllers;
use Config\Database;
class Delivery extends BaseController
{
    public function index(): string
    {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT delivery.*, karyawan.nama_staf, produk.nama_produk, provider_pengiriman.nama_provider, provider_pengiriman.jenis_layanan, detail_delivery.biaya, detail_delivery.jumlah, detail_delivery.status
                             FROM delivery 
                             LEFT JOIN karyawan ON karyawan.nip_karyawan = delivery.nip_karyawan
                             LEFT JOIN produk ON produk.kode_produk = delivery.kode_produk
                             LEFT JOIN provider_pengiriman ON provider_pengiriman.id_provider = delivery.id_provider
                             LEFT JOIN detail_delivery ON detail_delivery.id_detail_kirim = delivery.id_detail_kirim");
        $data = [
            'title' => 'Data Delivery - FreshBakery',
            'delivery' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('transaksi/delivery', $data);
    }

    private function nextId($db, string $table, string $column): int
    {
        $row = $db->table($table)->selectMax($column, 'max_id')->get()->getRowArray();
        return ((int) ($row['max_id'] ?? 0)) + 1;
    }

    private function resolveId($db, string $table, string $idColumn, string $nameColumn, ?string $input, $fallback = null)
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

        if ($fallback !== null) {
            return $fallback;
        }

        $row = $db->table($table)->select($idColumn)->orderBy($idColumn, 'ASC')->get(1)->getRowArray();
        return $row[$idColumn] ?? null;
    }

    public function create()
    {
        $db = Database::connect();

        $id_pengiriman = $this->request->getPost('ID') ?? $this->request->getPost('Id_Pengiriman') ?? $this->nextId($db, 'delivery', 'id_pengiriman');
        $id_detail_kirim = $this->nextId($db, 'detail_delivery', 'Id_detail_kirim');
        $kode_pengiriman = $this->request->getPost('No__Pengiriman') ?? $this->request->getPost('No. Pengiriman') ?? ('DLV/' . date('Y') . '/' . str_pad((string) $id_pengiriman, 3, '0', STR_PAD_LEFT));
        $pic = $this->request->getPost('PIC');
        $penerima = $this->request->getPost('Penerima');
        $alamat = $this->request->getPost('Alamat_Penerima') ?? $this->request->getPost('Alamat Penerima') ?? $this->request->getPost('Alamat');
        $jenis_pembayaran = $this->request->getPost('Jenis_Pembayaran') ?? $this->request->getPost('Jenis Pembayaran') ?? 'Tunai';
        $provider = $this->request->getPost('Provider');
        $produk = $this->request->getPost('Produk');
        $jumlah = (int) ($this->request->getPost('Jumlah') ?? 0);
        $status = $this->request->getPost('Status') ?? 'Pending';

        $db->table('detail_delivery')->insert([
            'Id_detail_kirim' => $id_detail_kirim,
            'jumlah' => $jumlah,
            'biaya' => $this->request->getPost('Biaya') ?? 'Rp 0',
            'berat' => $this->request->getPost('Berat') ?? '',
            'Status' => $status,
        ]);

        $db->table('delivery')->insert([
            'id_pengiriman' => $id_pengiriman,
            'nip_karyawan' => $this->resolveId($db, 'karyawan', 'nip_karyawan', 'nama_staf', $pic),
            'Id_detail_kirim' => $id_detail_kirim,
            'kode_pengiriman' => $kode_pengiriman,
            'penerima' => $penerima,
            'alamat' => $alamat,
            'Jenis_pembayaran' => $jenis_pembayaran,
            'id_provider' => $this->resolveId($db, 'provider_pengiriman', 'id_provider', 'nama_provider', $provider),
            'kode_produk' => $this->resolveId($db, 'produk', 'kode_produk', 'nama_produk', $produk),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Jadwal pengiriman baru berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();

        $delivery = $db->table('delivery')->where('kode_pengiriman', $id)->orWhere('id_pengiriman', $id)->get()->getRowArray();
        if (!$delivery) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pengiriman tidak ditemukan.'
            ]);
        }

        $pic = $this->request->getPost('PIC');
        $penerima = $this->request->getPost('Penerima');
        $alamat = $this->request->getPost('Alamat_Penerima') ?? $this->request->getPost('Alamat Penerima') ?? $this->request->getPost('Alamat');
        $jenis_pembayaran = $this->request->getPost('Jenis_Pembayaran') ?? $this->request->getPost('Jenis Pembayaran') ?? 'Tunai';
        $provider = $this->request->getPost('Provider');
        $produk = $this->request->getPost('Produk');
        $jumlah = (int) ($this->request->getPost('Jumlah') ?? 0);
        $status = $this->request->getPost('Status') ?? 'Pending';

        $db->table('detail_delivery')->where('Id_detail_kirim', $delivery['Id_detail_kirim'])->update([
            'jumlah' => $jumlah,
            'Status' => $status,
        ]);

        $db->table('delivery')->where('id_pengiriman', $delivery['id_pengiriman'])->update([
            'nip_karyawan' => $this->resolveId($db, 'karyawan', 'nip_karyawan', 'nama_staf', $pic),
            'penerima' => $penerima,
            'alamat' => $alamat,
            'Jenis_pembayaran' => $jenis_pembayaran,
            'id_provider' => $this->resolveId($db, 'provider_pengiriman', 'id_provider', 'nama_provider', $provider),
            'kode_produk' => $this->resolveId($db, 'produk', 'kode_produk', 'nama_produk', $produk),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data pengiriman berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();

        $delivery = $db->table('delivery')->where('kode_pengiriman', $id)->orWhere('id_pengiriman', $id)->get()->getRowArray();
        if ($delivery) {
            $db->table('delivery')->where('id_pengiriman', $delivery['id_pengiriman'])->delete();
            $db->table('detail_delivery')->where('Id_detail_kirim', $delivery['Id_detail_kirim'])->delete();
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pengiriman berhasil dihapus!'
        ]);
    }
}
