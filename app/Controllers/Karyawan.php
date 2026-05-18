<?php

namespace App\Controllers;
use Config\Database;
 
class Karyawan extends BaseController
{
    public function index(): string
    {
        // 1. Memanggil koneksi database
        $db = Database::connect();
 
        // 2. Mengambil data karyawan beserta nama departemennya
        $query = $db->query("SELECT karyawan.*, departemen.nama_dept 
                             FROM karyawan 
                             LEFT JOIN departemen ON karyawan.id_dept = departemen.id_dept");
        $data = [
            'title' => 'Data Karyawan - FreshBakery',
            'karyawan' => $query->getResult()
        ];

        // 3. Mengirim data ke view
        return view('master/karyawan', $data);
    }
}

