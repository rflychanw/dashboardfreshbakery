<?php

namespace App\Controllers;

use Config\Database;

class Pelanggan extends BaseController
{
    public function index(): string
    {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT * FROM pelanggan");
        $data = [
            'title' => 'Data Pelanggan - FreshBakery',
            'pelanggan' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('master/pelanggan', $data);
    }
}

