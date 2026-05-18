<?php

namespace App\Controllers;
use Config\Database;

class Departemen extends BaseController
{
    public function index(): string
    {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT * FROM departemen");
        $data = [
            'title' => 'Data Departemen - FreshBakery',
            'departemen' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('master/departemen', $data);
    }
}

