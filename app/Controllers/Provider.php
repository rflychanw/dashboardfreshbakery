<?php

namespace App\Controllers;
use Config\Database;
class Provider extends BaseController
{
    public function index(): string
         {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT * FROM provider_pengiriman");
        $data = [
            'title' => 'Data Provider - FreshBakery',
            'provider' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('master/provider', $data);
    }
}

