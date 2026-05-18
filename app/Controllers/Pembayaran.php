<?php

namespace App\Controllers;
use Config\Database;
class Pembayaran extends BaseController
{
    public function index(): string
       {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT pembayaran.*, karyawan.nama_staf, pelanggan.nama_lengkap
                             FROM pembayaran 
                             LEFT JOIN karyawan ON karyawan.nip_karyawan = pembayaran.nip_karyawan
                             LEFT JOIN pelanggan ON pelanggan.id_pelanggan = pembayaran.id_pelanggan");
        $data = [
            'title' => 'Data Pembayaran - FreshBakery',
            'pembayaran' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('transaksi/pembayaran', $data);
    }
}

