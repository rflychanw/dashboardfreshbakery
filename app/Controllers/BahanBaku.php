<?php

namespace App\Controllers;
use Config\Database;


class BahanBaku extends BaseController
{
    public function index(): string
    {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data karyawan beserta nama departemennya
        $query = $db->query("SELECT bahan_baku.*, vendor.nama_perusahaan 
                             FROM bahan_baku 
                             LEFT JOIN vendor ON bahan_baku.id_vendor = vendor.id_vendor");
        $data = [
            'title' => 'Data Bahan Baku - FreshBakery',
            'bahan_baku' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('detail/bahan_baku', $data);
    }
}

