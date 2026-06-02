<?php

namespace App\Controllers;
use Config\Database;

class Produk extends BaseController
{

    public function index(): string
    {
        $db = Database::connect();
        $query = $db->query("SELECT produk.*, daftar_harga.Harga_diskon
                             FROM produk 
                             LEFT JOIN daftar_harga ON daftar_harga.Id_detail_harga = produk.Id_detail_harga");
        $data = [
            'title' => 'Data Produk - FreshBakery',
            'produk' => $query->getResult()
        ];
        return view('master/produk', $data);
    }

    private function cleanNumber($val)
    {
        if (empty($val)) return 0;
        return floatval(preg_replace('/[^0-9.-]/', '', str_replace(['Rp', '.', ' '], ['', '', ''], $val)));
    }

    public function create()
    {
        $db = Database::connect();
        
        $kode_produk = $this->request->getPost('SKU') ?? $this->request->getPost('Sku') ?? $this->request->getPost('Kode_Produk') ?? $this->request->getPost('Kode Produk') ?? ('PR' . rand(100, 999));
        $nama_produk = $this->request->getPost('Nama_Produk') ?? $this->request->getPost('Nama Produk');
        $kategori_roti = $this->request->getPost('Kategori') ?? 'Roti';
        
        $harga_jual_input = $this->request->getPost('Harga_Jual') ?? $this->request->getPost('Harga Jual') ?? 0;
        $harga_diskon_input = $this->request->getPost('Harga_Diskon') ?? $this->request->getPost('Harga Diskon') ?? 0;
        $harga_jual = $this->cleanNumber($harga_jual_input);
        $harga_diskon = $this->cleanNumber($harga_diskon_input);

        $stok = intval($this->request->getPost('Stok') ?? 0);
        $masa_kadaluarsa = $this->request->getPost('Masa_Kadaluarsa') ?? $this->request->getPost('Masa Kadaluarsa') ?? date('Y-m-d', strtotime('+3 days'));

        // 1. Insert first to get a daftar_harga record
        $db->table('daftar_harga')->insert([
            'kode_produk' => $kode_produk,
            'Harga_asli' => $harga_jual,
            'Harga_diskon' => $harga_diskon
        ]);
        $id_detail_harga = $db->insertID();

        // 2. Insert into produk
        $db->table('produk')->insert([
            'kode_produk' => $kode_produk,
            'Id_detail_harga' => $id_detail_harga,
            'nama_produk' => $nama_produk,
            'kategori_roti' => $kategori_roti,
            'stok' => $stok,
            'harga_jual' => $harga_jual,
            'masa_kadaluarsa' => $masa_kadaluarsa
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Produk baru berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $nama_produk = $this->request->getPost('Nama_Produk') ?? $this->request->getPost('Nama Produk');
        $kategori_roti = $this->request->getPost('Kategori') ?? 'Roti';
        
        $harga_jual_input = $this->request->getPost('Harga_Jual') ?? $this->request->getPost('Harga Jual') ?? 0;
        $harga_diskon_input = $this->request->getPost('Harga_Diskon') ?? $this->request->getPost('Harga Diskon') ?? 0;
        $harga_jual = $this->cleanNumber($harga_jual_input);
        $harga_diskon = $this->cleanNumber($harga_diskon_input);

        $stok = intval($this->request->getPost('Stok') ?? 0);
        $masa_kadaluarsa = $this->request->getPost('Masa_Kadaluarsa') ?? $this->request->getPost('Masa Kadaluarsa') ?? date('Y-m-d', strtotime('+3 days'));

        // Get existing product to update its daftar_harga
        $prod = $db->table('produk')->where('kode_produk', $id)->get()->getRowArray();
        if ($prod && $prod['Id_detail_harga']) {
            $db->table('daftar_harga')->where('Id_detail_harga', $prod['Id_detail_harga'])->update([
                'Harga_asli' => $harga_jual,
                'Harga_diskon' => $harga_diskon
            ]);
        } else {
            // Create a new price record if not exists
            $db->table('daftar_harga')->insert([
                'kode_produk' => $id,
                'Harga_asli' => $harga_jual,
                'Harga_diskon' => $harga_diskon
            ]);
            $id_detail_harga = $db->insertID();
            $db->table('produk')->where('kode_produk', $id)->update(['Id_detail_harga' => $id_detail_harga]);
        }

        $db->table('produk')->where('kode_produk', $id)->update([
            'nama_produk' => $nama_produk,
            'kategori_roti' => $kategori_roti,
            'stok' => $stok,
            'harga_jual' => $harga_jual,
            'masa_kadaluarsa' => $masa_kadaluarsa
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data produk berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        
        // Remove related prices first to keep database clean
        $prod = $db->table('produk')->where('kode_produk', $id)->get()->getRowArray();
        if ($prod && $prod['Id_detail_harga']) {
            $db->table('daftar_harga')->where('Id_detail_harga', $prod['Id_detail_harga'])->delete();
        }
        
        $db->table('produk')->where('kode_produk', $id)->delete();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Produk berhasil dihapus!'
        ]);
    }
}

