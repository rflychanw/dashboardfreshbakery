<?php

namespace App\Controllers;
use Config\Database;
class Pembelian extends BaseController
{
    public function index(): string
      {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT pembelian_bahan.*, vendor.nama_perusahaan
                             FROM pembelian_bahan 
                             LEFT JOIN vendor ON vendor.id_vendor = pembelian_bahan.id_vendor");
        $data = [
            'title' => 'Data Pembelian - FreshBakery',
            'pembelian' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('transaksi/pembelian', $data);
    }
}

