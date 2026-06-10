<?php

namespace App\Controllers;
use Config\Database;
class Vendor extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();
        $query = $db->query("SELECT * FROM vendor");
        $data = [
            'title' => 'Data Vendor - FreshBakery',
            'vendor' => $query->getResult()
        ];
        return view('master/vendor', $data);
    }

    public function create()
    {
        $db = Database::connect();
        
        $id_vendor = $this->request->getPost('ID') ?? $this->request->getPost('Id') ?? rand(10, 99);
        $nama_perusahaan = $this->request->getPost('Nama_Vendor') ?? $this->request->getPost('Nama Vendor');
        $nama_sales = $this->request->getPost('Nama_Sales') ?? $this->request->getPost('Nama Sales');
        $no_telp = $this->request->getPost('No__Telepon') ?? $this->request->getPost('No. Telepon') ?? $this->request->getPost('Kontak');
        $alamat_kantor = $this->request->getPost('Alamat');
        $termin_pembayaran = $this->request->getPost('Termin_Pembayaran') ?? $this->request->getPost('Termin Pembayaran');

        $db->table('vendor')->insert([
            'id_vendor' => $id_vendor,
            'nama_perusahaan' => $nama_perusahaan,
            'nama_sales' => $nama_sales,
            'no_telp' => $no_telp,
            'alamat_kantor' => $alamat_kantor,
            'termin_pembayaran' => $termin_pembayaran
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Vendor baru berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $nama_perusahaan = $this->request->getPost('Nama_Vendor') ?? $this->request->getPost('Nama Vendor');
        $nama_sales = $this->request->getPost('Nama_Sales') ?? $this->request->getPost('Nama Sales');
        $no_telp = $this->request->getPost('No__Telepon') ?? $this->request->getPost('No. Telepon') ?? $this->request->getPost('Kontak');
        $alamat_kantor = $this->request->getPost('Alamat');
        $termin_pembayaran = $this->request->getPost('Termin_Pembayaran') ?? $this->request->getPost('Termin Pembayaran');

        $db->table('vendor')->where('id_vendor', $id)->update([
            'nama_perusahaan' => $nama_perusahaan,
            'nama_sales' => $nama_sales,
            'no_telp' => $no_telp,
            'alamat_kantor' => $alamat_kantor,
            'termin_pembayaran' => $termin_pembayaran
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data vendor berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        
        try {
            // Clear FK references
            $db->table('bahan_baku')->where('id_vendor', $id)->update(['id_vendor' => null]);
            $db->table('pembelian_bahan')->where('id_vendor', $id)->update(['id_vendor' => null]);
            
            $db->table('vendor')->where('id_vendor', $id)->delete();
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Vendor berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ]);
        }
    }
}

