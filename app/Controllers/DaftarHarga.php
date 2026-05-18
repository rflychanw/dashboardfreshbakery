<?php

namespace App\Controllers;
use Config\Database;

class DaftarHarga extends BaseController
{
    public function index(): string
      {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT * FROM daftar_harga");
        $data = [
            'title' => 'Data Daftar Harga - FreshBakery',
            'daftar_harga' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('master/daftar_harga', $data);
    }
}

