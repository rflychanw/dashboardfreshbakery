<?php

namespace App\Controllers;
use Config\Database;
class Delivery extends BaseController
{
    public function index(): string
    {
        // 1. Memanggil koneksi database
        $db = Database::connect();

        // 2. Mengambil data dari tabel pelanggan
        $query = $db->query("SELECT delivery.*, karyawan.nama_staf, produk.nama_produk, provider_pengiriman.nama_provider, provider_pengiriman.jenis_layanan, detail_delivery.biaya, detail_delivery.jumlah, detail_delivery.status
                             FROM delivery 
                             LEFT JOIN karyawan ON karyawan.nip_karyawan = delivery.nip_karyawan
                             LEFT JOIN produk ON produk.kode_produk = delivery.kode_produk
                             LEFT JOIN provider_pengiriman ON provider_pengiriman.id_provider = delivery.id_provider
                             LEFT JOIN detail_delivery ON detail_delivery.id_detail_kirim = delivery.id_detail_kirim");
        $data = [
            'title' => 'Data Delivery - FreshBakery',
            'delivery' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('transaksi/delivery', $data);
    }
}

