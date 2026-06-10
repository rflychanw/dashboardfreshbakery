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

    private function cleanNumber($val)
    {
        if (empty($val)) return 0;
        return floatval(preg_replace('/[^0-9.-]/', '', str_replace(['Rp', '.', ' '], ['', '', ''], $val)));
    }

    public function create()
    {
        $db = Database::connect();
        
        $id_dept = $this->request->getPost('ID') ?? $this->request->getPost('Id') ?? rand(10, 99);
        $nama_dept = $this->request->getPost('Nama_Departemen') ?? $this->request->getPost('Nama Departemen');
        $budget_tahunan_input = $this->request->getPost('Budget_Tahunan') ?? $this->request->getPost('Budget Tahunan') ?? 0;
        $budget_tahunan = $this->cleanNumber($budget_tahunan_input);

        try {
            $db->table('departemen')->insert([
                'id_dept' => $id_dept,
                'nama_dept' => $nama_dept,
                'budget_tahunan' => $budget_tahunan
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Departemen baru berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            $err = $e->getMessage();
            if (strpos($err, 'Duplicate') !== false) {
                $err = 'ID Departemen sudah ada di database!';
            }
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $err
            ]);
        }
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $nama_dept = $this->request->getPost('Nama_Departemen') ?? $this->request->getPost('Nama Departemen');
        $budget_tahunan_input = $this->request->getPost('Budget_Tahunan') ?? $this->request->getPost('Budget Tahunan') ?? 0;
        $budget_tahunan = $this->cleanNumber($budget_tahunan_input);

        try {
            $db->table('departemen')->where('id_dept', $id)->update([
                'nama_dept' => $nama_dept,
                'budget_tahunan' => $budget_tahunan
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data departemen berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        $db = Database::connect();
        
        try {
            $db->table('karyawan')->where('id_dept', $id)->update(['id_dept' => null]);
            $db->table('departemen')->where('id_dept', $id)->delete();
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Departemen berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ]);
        }
    }
}

