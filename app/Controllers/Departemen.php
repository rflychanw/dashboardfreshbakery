<?php

namespace App\Controllers;
use Config\Database;

class Departemen extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();
        $query = $db->query("SELECT * FROM departemen");
        $data = [
            'title' => 'Data Departemen - FreshBakery',
            'departemen' => $query->getResult()
        ];
        return view('master/departemen', $data);
    }

    public function create()
    {
        $db = Database::connect();
        
        $id_dept = $this->request->getPost('ID') ?? $this->request->getPost('Id') ?? rand(10, 99);
        $nama_dept = $this->request->getPost('Nama_Departemen') ?? $this->request->getPost('Nama Departemen');
        $budget_tahunan = $this->request->getPost('Budget_Tahunan') ?? $this->request->getPost('Budget Tahunan');

        $db->table('departemen')->insert([
            'id_dept' => $id_dept,
            'nama_dept' => $nama_dept,
            'budget_tahunan' => $budget_tahunan
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Departemen baru berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $nama_dept = $this->request->getPost('Nama_Departemen') ?? $this->request->getPost('Nama Departemen');
        $budget_tahunan = $this->request->getPost('Budget_Tahunan') ?? $this->request->getPost('Budget Tahunan');

        $db->table('departemen')->where('id_dept', $id)->update([
            'nama_dept' => $nama_dept,
            'budget_tahunan' => $budget_tahunan
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data departemen berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        $db->table('departemen')->where('id_dept', $id)->delete();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Departemen berhasil dihapus!'
        ]);
    }
}

