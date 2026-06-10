<?php

namespace App\Controllers;
use Config\Database;


class BahanBaku extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();
        $query = $db->query("SELECT bahan_baku.*, vendor.nama_perusahaan 
                             FROM bahan_baku 
                             LEFT JOIN vendor ON bahan_baku.id_vendor = vendor.id_vendor");
        $data = [
            'title' => 'Data Bahan Baku - FreshBakery',
            'bahan_baku' => $query->getResult()
        ];
        return view('master/bahan_baku', $data);
    }

    private function cleanNumber($val)
    {
        if (empty($val)) return 0;
        return floatval(preg_replace('/[^0-9.-]/', '', str_replace(['Rp', '.', ' '], ['', '', ''], $val)));
    }

    public function create()
    {
        $db = Database::connect();
        
        $id_bahan = $this->request->getPost('ID') ?? $this->request->getPost('ID_Bahan') ?? $this->request->getPost('ID Bahan') ?? ('BB' . rand(10, 99));
        $nama_perusahaan = $this->request->getPost('Nama_Perusahaan') ?? $this->request->getPost('Nama Perusahaan') ?? $this->request->getPost('Vendor');
        $nama_bahan = $this->request->getPost('Nama_Bahan') ?? $this->request->getPost('Nama Bahan');
        $stok_saat_ini = intval($this->request->getPost('Stok_Tersedia') ?? $this->request->getPost('Stok_Saat_Ini') ?? $this->request->getPost('Stok Tersedia') ?? $this->request->getPost('Stok Saat Ini') ?? 0);
        $status_satuan = $this->request->getPost('Satuan') ?? $this->request->getPost('Status_Satuan') ?? $this->request->getPost('Status Satuan') ?? 'kg';
        
        $harga_beli_input = $this->request->getPost('Harga_Beli') ?? $this->request->getPost('Harga_Beli_Unit') ?? $this->request->getPost('Harga Beli') ?? $this->request->getPost('Harga Beli Unit') ?? 0;
        $harga_beli_unit = $this->cleanNumber($harga_beli_input);
        
        $stok_min = intval($this->request->getPost('Status_Stok') ?? $this->request->getPost('Stok_Min') ?? $this->request->getPost('Status Stok') ?? $this->request->getPost('Stok Min') ?? 0);

        // Resolve vendor ID
        $id_vendor = 1;
        if (!empty($nama_perusahaan)) {
            $vendorRow = $db->table('vendor')
                ->groupStart()
                    ->where('id_vendor', $nama_perusahaan)
                    ->orLike('nama_perusahaan', $nama_perusahaan)
                ->groupEnd()
                ->get()->getRowArray();
            if ($vendorRow) {
                $id_vendor = $vendorRow['id_vendor'];
            }
        }

        $db->table('bahan_baku')->insert([
            'id_bahan' => $id_bahan,
            'id_vendor' => $id_vendor,
            'nama_bahan' => $nama_bahan,
            'stok_saat_ini' => $stok_saat_ini,
            'status_satuan' => $status_satuan,
            'harga_beli_unit' => $harga_beli_unit,
            'stok_min' => $stok_min
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Bahan baku baru berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $nama_perusahaan = $this->request->getPost('Nama_Perusahaan') ?? $this->request->getPost('Nama Perusahaan') ?? $this->request->getPost('Vendor');
        $nama_bahan = $this->request->getPost('Nama_Bahan') ?? $this->request->getPost('Nama Bahan');
        $stok_saat_ini = intval($this->request->getPost('Stok_Tersedia') ?? $this->request->getPost('Stok_Saat_Ini') ?? $this->request->getPost('Stok Tersedia') ?? $this->request->getPost('Stok Saat Ini') ?? 0);
        $status_satuan = $this->request->getPost('Satuan') ?? $this->request->getPost('Status_Satuan') ?? $this->request->getPost('Status Satuan') ?? 'kg';
        
        $harga_beli_input = $this->request->getPost('Harga_Beli') ?? $this->request->getPost('Harga_Beli_Unit') ?? $this->request->getPost('Harga Beli') ?? $this->request->getPost('Harga Beli Unit') ?? 0;
        $harga_beli_unit = $this->cleanNumber($harga_beli_input);
        
        $stok_min = intval($this->request->getPost('Status_Stok') ?? $this->request->getPost('Stok_Min') ?? $this->request->getPost('Status Stok') ?? $this->request->getPost('Stok Min') ?? 0);

        // Resolve vendor ID
        $id_vendor = 1;
        if (!empty($nama_perusahaan)) {
            $vendorRow = $db->table('vendor')
                ->groupStart()
                    ->where('id_vendor', $nama_perusahaan)
                    ->orLike('nama_perusahaan', $nama_perusahaan)
                ->groupEnd()
                ->get()->getRowArray();
            if ($vendorRow) {
                $id_vendor = $vendorRow['id_vendor'];
            }
        }

        $db->table('bahan_baku')->where('id_bahan', $id)->update([
            'id_vendor' => $id_vendor,
            'nama_bahan' => $nama_bahan,
            'stok_saat_ini' => $stok_saat_ini,
            'status_satuan' => $status_satuan,
            'harga_beli_unit' => $harga_beli_unit,
            'stok_min' => $stok_min
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data bahan baku berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        
        try {
            $db->table('produksi')->where('id_bahan', $id)->update(['id_bahan' => null]);
            $db->table('bahan_baku')->where('id_bahan', $id)->delete();
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Bahan baku berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ]);
        }
    }
}

