<?php

namespace App\Controllers;
use Config\Database;
class Penjualan extends BaseController
{
    public function index(): string
    {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT penjualan.*, karyawan.nama_staf, pelanggan.nama_lengkap, pembayaran.Metode_pembayaran 
                             FROM penjualan 
                             LEFT JOIN karyawan ON karyawan.nip_karyawan = penjualan.nip_karyawan
                             LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
                             LEFT JOIN pembayaran ON pembayaran.id_pembayaran = penjualan.id_pembayaran");
        $data = [
            'title' => 'Data Penjualan - FreshBakery',
            'penjualan' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('transaksi/penjualan', $data);
    }
}

