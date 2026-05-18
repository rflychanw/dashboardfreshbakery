<?php

namespace App\Controllers;
use Config\Database;
class Vendor extends BaseController
{
    public function index(): string
     {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT * FROM vendor");
        $data = [
            'title' => 'Data Vendor - FreshBakery',
            'vendor' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('master/vendor', $data);
    }
}

