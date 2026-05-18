<?php

namespace App\Controllers;
use Config\Database;

class Produk extends BaseController
{

    public function index(): string
       {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT produk.*, daftar_harga.Harga_diskon
                             FROM produk 
                             LEFT JOIN daftar_harga ON daftar_harga.Id_detail_harga = produk.Id_detail_harga");
        $data = [
            'title' => 'Data Produk - FreshBakery',
            'produk' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('master/produk', $data);
    }
}

