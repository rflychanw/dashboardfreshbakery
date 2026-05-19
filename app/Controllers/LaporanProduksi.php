<?php

namespace App\Controllers;
use Config\Database;

class LaporanProduksi extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        // Ambil tanggal terbaru dari database sebagai default agar data dummy 2024 terlihat
        $latestQuery = $db->query("SELECT MAX(tgl_produksi) as max_date FROM produksi")->getRow();
        $latestDate = $latestQuery->max_date ?? date('Y-m-d');

        // Parameter filter
        $periode = $this->request->getVar('periode') ?? 'semua';
        $tanggal = $this->request->getVar('tanggal') ?? $latestDate;
        $start_date = $this->request->getVar('start_date') ?? date('Y-m-d', strtotime('-6 days', strtotime($tanggal)));
        $end_date = $this->request->getVar('end_date') ?? $tanggal;
        
        // Default bulan & tahun berdasarkan tanggal terbaru
        $defaultBulan = date('m', strtotime($latestDate));
        $defaultTahun = date('Y', strtotime($latestDate));
        
        $bulan = $this->request->getVar('bulan') ?? $defaultBulan;
        $tahun = $this->request->getVar('tahun') ?? $defaultTahun;

        // Query Builder untuk Laporan Produksi
        $builder = $db->table('produksi')
            ->select('produksi.*, karyawan.nama_staf as pic, bahan_baku.nama_bahan')
            ->join('karyawan', 'karyawan.nip_karyawan = produksi.nip_karyawan', 'left')
            ->join('bahan_baku', 'bahan_baku.id_bahan = produksi.id_bahan', 'left');

        if ($periode == 'hari') {
            $builder->where('produksi.tgl_produksi', $tanggal);
        } elseif ($periode == 'minggu') {
            $builder->where("produksi.tgl_produksi >=", $start_date);
            $builder->where("produksi.tgl_produksi <=", $end_date);
        } elseif ($periode == 'bulan') {
            $builder->where("MONTH(produksi.tgl_produksi)", $bulan);
            $builder->where("YEAR(produksi.tgl_produksi)", $tahun);
        }
        // Jika 'semua', query builder tidak diberi batasan where agar menampilkan semua data

        $laporanData = $builder->get()->getResult();

        // Hitung statistik ringkasan (summary stats)
        $totalProduksi = count($laporanData);
        $totalSelesai = 0;
        $totalProses = 0;
        $varianTerpopuler = '-';
        $varianCounts = [];

        foreach ($laporanData as $row) {
            if (strtolower(trim($row->status)) == 'selesai') {
                $totalSelesai++;
            } else {
                $totalProses++;
            }

            // Hitung varian terpopuler
            if (!empty($row->hasil_produk)) {
                $productName = $row->hasil_produk . ' (' . $row->varian . ')';
                $varianCounts[$productName] = ($varianCounts[$productName] ?? 0) + 1;
            }
        }

        if (!empty($varianCounts)) {
            arsort($varianCounts);
            $varianTerpopuler = array_keys($varianCounts)[0] . ' (' . array_values($varianCounts)[0] . ' kali)';
        }

        // List Tahun untuk dropdown (dari data unik tgl_produksi)
        $tahunQuery = $db->query("SELECT DISTINCT YEAR(tgl_produksi) as tahun FROM produksi ORDER BY tgl_produksi DESC")->getResult();
        $listTahun = array_column($tahunQuery, 'tahun');
        if (empty($listTahun)) {
            $listTahun = [date('Y')];
        }

        $data = [
            'title' => 'Laporan Produksi Roti - FreshBakery',
            'periode' => $periode,
            'tanggal' => $tanggal,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'laporan' => $laporanData,
            'stats' => [
                'total' => $totalProduksi,
                'selesai' => $totalSelesai,
                'proses' => $totalProses,
                'terpopuler' => $varianTerpopuler
            ],
            'listTahun' => $listTahun
        ];

        return view('laporan/produksi', $data);
    }
}
