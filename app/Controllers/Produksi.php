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
}

