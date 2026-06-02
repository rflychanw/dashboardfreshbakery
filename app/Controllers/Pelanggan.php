<?php

namespace App\Controllers;

use Config\Database;

class Pelanggan extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();
        $query = $db->query("SELECT * FROM pelanggan");
        $data = [
            'title' => 'Data Pelanggan - FreshBakery',
            'pelanggan' => $query->getResult()
        ];
        return view('master/pelanggan', $data);
    }

    public function create()
    {
        $db = Database::connect();
        
        $id_pelanggan = $this->request->getPost('ID') ?? $this->request->getPost('Id_Pelanggan') ?? $this->request->getPost('Id Pelanggan') ?? ('PL' . rand(100, 999));
        $nama_lengkap = $this->request->getPost('Nama_Lengkap') ?? $this->request->getPost('Nama Lengkap');
        $no_tlp = $this->request->getPost('No__Telepon') ?? $this->request->getPost('No. Telepon') ?? $this->request->getPost('No. Tlp') ?? $this->request->getPost('Kontak');
        $total_poin_loyalitas = intval($this->request->getPost('Poin_Loyalitas') ?? $this->request->getPost('Total_Poin_Loyalitas') ?? $this->request->getPost('Poin Loyalitas') ?? 0);

        $db->table('pelanggan')->insert([
            'Id_pelanggan' => $id_pelanggan,
            'nama_lengkap' => $nama_lengkap,
            'no_tlp' => $no_tlp,
            'total_poin_loyalitas' => $total_poin_loyalitas
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pelanggan baru berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $nama_lengkap = $this->request->getPost('Nama_Lengkap') ?? $this->request->getPost('Nama Lengkap');
        $no_tlp = $this->request->getPost('No__Telepon') ?? $this->request->getPost('No. Telepon') ?? $this->request->getPost('No. Tlp') ?? $this->request->getPost('Kontak');
        $total_poin_loyalitas = intval($this->request->getPost('Poin_Loyalitas') ?? $this->request->getPost('Total_Poin_Loyalitas') ?? $this->request->getPost('Poin Loyalitas') ?? 0);

        $db->table('pelanggan')->where('Id_pelanggan', $id)->update([
            'nama_lengkap' => $nama_lengkap,
            'no_tlp' => $no_tlp,
            'total_poin_loyalitas' => $total_poin_loyalitas
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data pelanggan berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        $db->table('pelanggan')->where('Id_pelanggan', $id)->delete();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pelanggan berhasil dihapus!'
        ]);
    }
}

