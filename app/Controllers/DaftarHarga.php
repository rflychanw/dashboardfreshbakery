<?php

namespace App\Controllers;
use Config\Database;

class DaftarHarga extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();
        $query = $db->query("SELECT * FROM daftar_harga");
        $data = [
            'title' => 'Data Daftar Harga - FreshBakery',
            'daftar_harga' => $query->getResult()
        ];
        return view('master/daftar_harga', $data);
    }

    private function cleanNumber($val)
    {
        if (empty($val)) return 0;
        return floatval(preg_replace('/[^0-9.-]/', '', str_replace(['Rp', '.', ' '], ['', '', ''], $val)));
    }

    public function create()
    {
        $db = Database::connect();
        
        $kode_produk = $this->request->getPost('SKU') ?? $this->request->getPost('Sku') ?? $this->request->getPost('Kode_Produk') ?? $this->request->getPost('Kode Produk');
        $harga_asli_input = $this->request->getPost('Harga_Asli') ?? $this->request->getPost('Harga Asli') ?? 0;
        $harga_diskon_input = $this->request->getPost('Harga_Diskon') ?? $this->request->getPost('Harga Diskon') ?? 0;
        
        $harga_asli = $this->cleanNumber($harga_asli_input);
        $harga_diskon = $this->cleanNumber($harga_diskon_input);

        $db->table('daftar_harga')->insert([
            'kode_produk' => $kode_produk,
            'Harga_asli' => $harga_asli,
            'Harga_diskon' => $harga_diskon
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Daftar harga baru berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $kode_produk = $this->request->getPost('SKU') ?? $this->request->getPost('Sku') ?? $this->request->getPost('Kode_Produk') ?? $this->request->getPost('Kode Produk');
        $harga_asli_input = $this->request->getPost('Harga_Asli') ?? $this->request->getPost('Harga Asli') ?? 0;
        $harga_diskon_input = $this->request->getPost('Harga_Diskon') ?? $this->request->getPost('Harga Diskon') ?? 0;
        
        $harga_asli = $this->cleanNumber($harga_asli_input);
        $harga_diskon = $this->cleanNumber($harga_diskon_input);

        $db->table('daftar_harga')->where('Id_detail_harga', $id)->update([
            'kode_produk' => $kode_produk,
            'Harga_asli' => $harga_asli,
            'Harga_diskon' => $harga_diskon
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data daftar harga berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        $db->table('daftar_harga')->where('Id_detail_harga', $id)->delete();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Daftar harga berhasil dihapus!'
        ]);
    }
}

