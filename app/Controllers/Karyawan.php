<?php

namespace App\Controllers;
use Config\Database;
 
class Karyawan extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();
        $query = $db->query("SELECT karyawan.*, departemen.nama_dept 
                             FROM karyawan 
                             LEFT JOIN departemen ON karyawan.id_dept = departemen.id_dept");
        $data = [
            'title' => 'Data Karyawan - FreshBakery',
            'karyawan' => $query->getResult()
        ];
        return view('master/karyawan', $data);
    }

    public function create()
    {
        $db = Database::connect();
        
        $nip = $this->request->getPost('NIP') ?? $this->request->getPost('Nip') ?? rand(100000, 999999);
        $nama_staf = $this->request->getPost('Nama_Lengkap') ?? $this->request->getPost('Nama Lengkap');
        $jabatan = $this->request->getPost('Jabatan');
        $departemen_input = $this->request->getPost('Departemen');
        $email_kantor = $this->request->getPost('Email') ?? $this->request->getPost('Email_Kantor') ?? $this->request->getPost('Email Kantor');
        $tgl_masuk = $this->request->getPost('Tanggal_Masuk') ?? $this->request->getPost('Tanggal Masuk');
        $status_staf = $this->request->getPost('Status') ?? 'Aktif';

        // Resolve Departemen ID
        $id_dept = 1; // default fallback
        if (!empty($departemen_input)) {
            $deptRow = $db->table('departemen')
                ->groupStart()
                    ->where('id_dept', $departemen_input)
                    ->orLike('nama_dept', $departemen_input)
                ->groupEnd()
                ->get()->getRowArray();
            if ($deptRow) {
                $id_dept = $deptRow['id_dept'];
            }
        }

        $db->table('karyawan')->insert([
            'nip_karyawan' => $nip,
            'id_dept' => $id_dept,
            'nama_staf' => $nama_staf,
            'jabatan' => $jabatan,
            'email_kantor' => $email_kantor,
            'tgl_masuk' => $tgl_masuk,
            'status_staf' => $status_staf
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Karyawan baru berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $nama_staf = $this->request->getPost('Nama_Lengkap') ?? $this->request->getPost('Nama Lengkap');
        $jabatan = $this->request->getPost('Jabatan');
        $departemen_input = $this->request->getPost('Departemen');
        $email_kantor = $this->request->getPost('Email') ?? $this->request->getPost('Email_Kantor') ?? $this->request->getPost('Email Kantor');
        $tgl_masuk = $this->request->getPost('Tanggal_Masuk') ?? $this->request->getPost('Tanggal Masuk');
        $status_staf = $this->request->getPost('Status') ?? 'Aktif';

        // Resolve Departemen ID
        $id_dept = 1;
        if (!empty($departemen_input)) {
            $deptRow = $db->table('departemen')
                ->groupStart()
                    ->where('id_dept', $departemen_input)
                    ->orLike('nama_dept', $departemen_input)
                ->groupEnd()
                ->get()->getRowArray();
            if ($deptRow) {
                $id_dept = $deptRow['id_dept'];
            }
        }

        $db->table('karyawan')->where('nip_karyawan', $id)->update([
            'id_dept' => $id_dept,
            'nama_staf' => $nama_staf,
            'jabatan' => $jabatan,
            'email_kantor' => $email_kantor,
            'tgl_masuk' => $tgl_masuk,
            'status_staf' => $status_staf
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data karyawan berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        $db->table('karyawan')->where('nip_karyawan', $id)->delete();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Karyawan berhasil dihapus!'
        ]);
    }
}

