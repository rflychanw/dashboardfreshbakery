<?php

namespace App\Controllers;
use Config\Database;
class Provider extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();
        $query = $db->query("SELECT * FROM provider_pengiriman");
        $data = [
            'title' => 'Data Provider - FreshBakery',
            'provider' => $query->getResult()
        ];
        return view('master/provider', $data);
    }

    public function create()
    {
        $db = Database::connect();
        
        $nama_provider = $this->request->getPost('Nama_Provider') ?? $this->request->getPost('Nama Provider');
        $jenis_layanan = $this->request->getPost('Jenis_Layanan') ?? $this->request->getPost('Jenis Layanan');
        $no_telp = $this->request->getPost('Kontak') ?? $this->request->getPost('No__Telepon') ?? $this->request->getPost('No. Telepon');
        $alamat_kantor = $this->request->getPost('Alamat_Kantor') ?? $this->request->getPost('Alamat Kantor') ?? $this->request->getPost('Alamat');
        $status_aktif = $this->request->getPost('Status') ?? 'Aktif';

        $db->table('provider_pengiriman')->insert([
            'nama_provider' => $nama_provider,
            'jenis_layanan' => $jenis_layanan,
            'no_telp' => $no_telp,
            'alamat_kantor' => $alamat_kantor,
            'status_aktif' => $status_aktif,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Provider baru berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $nama_provider = $this->request->getPost('Nama_Provider') ?? $this->request->getPost('Nama Provider');
        $jenis_layanan = $this->request->getPost('Jenis_Layanan') ?? $this->request->getPost('Jenis Layanan');
        $no_telp = $this->request->getPost('Kontak') ?? $this->request->getPost('No__Telepon') ?? $this->request->getPost('No. Telepon');
        $alamat_kantor = $this->request->getPost('Alamat_Kantor') ?? $this->request->getPost('Alamat Kantor') ?? $this->request->getPost('Alamat');
        $status_aktif = $this->request->getPost('Status') ?? 'Aktif';

        $db->table('provider_pengiriman')->where('id_provider', $id)->update([
            'nama_provider' => $nama_provider,
            'jenis_layanan' => $jenis_layanan,
            'no_telp' => $no_telp,
            'alamat_kantor' => $alamat_kantor,
            'status_aktif' => $status_aktif,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data provider berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        
        try {
            $db->table('delivery')->where('id_provider', $id)->update(['id_provider' => null]);
            $db->table('provider_pengiriman')->where('id_provider', $id)->delete();
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Provider berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ]);
        }
    }
}

