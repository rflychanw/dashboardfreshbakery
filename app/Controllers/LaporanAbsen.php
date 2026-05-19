<?php

namespace App\Controllers;
use Config\Database;

class LaporanAbsen extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        // Parameter filter
        $filterStatus = $this->request->getVar('status') ?? 'semua';
        $filterDept = $this->request->getVar('departemen') ?? 'semua';

        // Query Builder untuk Laporan Karyawan
        $builder = $db->table('karyawan')
            ->select('karyawan.*, departemen.nama_dept')
            ->join('departemen', 'karyawan.id_dept = departemen.id_dept', 'left');

        if ($filterStatus != 'semua') {
            $builder->where('karyawan.status_staf', $filterStatus);
        }
        if ($filterDept != 'semua') {
            $builder->where('karyawan.id_dept', $filterDept);
        }

        $laporanData = $builder->get()->getResult();

        // Hitung statistik berdasarkan data asli (semua data karyawan di database)
        $totalBuilder = $db->table('karyawan');
        $allKaryawan = $totalBuilder->get()->getResult();

        $totalKaryawan = count($allKaryawan);
        $totalAktif = 0;
        $totalCuti = 0;
        $totalKeluar = 0;

        foreach ($allKaryawan as $k) {
            $status = strtolower(trim($k->status_staf));
            if ($status == 'aktif') {
                $totalAktif++;
            } elseif ($status == 'cuti') {
                $totalCuti++;
            } elseif ($status == 'keluar') {
                $totalKeluar++;
            }
        }

        // Ambil data untuk dropdown filter departemen
        $listDept = $db->table('departemen')->select('id_dept, nama_dept')->get()->getResult();

        $data = [
            'title' => 'Laporan Absen Karyawan - FreshBakery',
            'filterStatus' => $filterStatus,
            'filterDept' => $filterDept,
            'laporan' => $laporanData,
            'listDept' => $listDept,
            'stats' => [
                'total_karyawan' => $totalKaryawan,
                'total_aktif' => $totalAktif,
                'total_cuti' => $totalCuti,
                'total_keluar' => $totalKeluar
            ]
        ];

        return view('laporan/absen', $data);
    }
}
