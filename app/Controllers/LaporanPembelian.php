<?php

namespace App\Controllers;
use Config\Database;

class LaporanPembelian extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        // Parameter filter
        $periode = $this->request->getVar('periode') ?? 'semua';
        $tanggal = $this->request->getVar('tanggal') ?? date('Y-m-d');
        $start_date = $this->request->getVar('start_date') ?? date('Y-m-d', strtotime('-7 days'));
        $end_date = $this->request->getVar('end_date') ?? date('Y-m-d');
        $bulan = $this->request->getVar('bulan') ?? date('m');
        $tahun = $this->request->getVar('tahun') ?? date('Y');
        
        $filterVendor = $this->request->getVar('vendor') ?? 'semua';
        $filterMetode = $this->request->getVar('metode_bayar') ?? 'semua';

        // Query Builder dasar untuk Detail Pembelian Bahan
        $builder = $db->table('detail_pembelian_bahan')
            ->select('detail_pembelian_bahan.*, pembelian_bahan.tgl_pembelian, pembelian_bahan.metode_bayar, bahan_baku.nama_bahan, bahan_baku.status_satuan, vendor.nama_perusahaan')
            ->join('pembelian_bahan', 'detail_pembelian_bahan.id_pembelian = pembelian_bahan.id_pembelian', 'left')
            ->join('bahan_baku', 'detail_pembelian_bahan.id_bahan = bahan_baku.id_bahan', 'left')
            ->join('vendor', 'pembelian_bahan.id_vendor = vendor.id_vendor', 'left');

        // Terapkan filter vendor
        if ($filterVendor != 'semua') {
            $builder->where('pembelian_bahan.id_vendor', $filterVendor);
        }

        // Terapkan filter metode bayar
        if ($filterMetode != 'semua') {
            $builder->where('pembelian_bahan.metode_bayar', $filterMetode);
        }

        // Terapkan filter periode tanggal
        if ($periode == 'hari') {
            $builder->where('pembelian_bahan.tgl_pembelian', $tanggal);
        } elseif ($periode == 'minggu') {
            $builder->where('pembelian_bahan.tgl_pembelian >=', $start_date);
            $builder->where('pembelian_bahan.tgl_pembelian <=', $end_date);
        } elseif ($periode == 'bulan') {
            $builder->where('MONTH(pembelian_bahan.tgl_pembelian)', $bulan);
            $builder->where('YEAR(pembelian_bahan.tgl_pembelian)', $tahun);
        }

        $laporanData = $builder->get()->getResult();

        // Hitung statistik
        $totalTransaksi = count(array_unique(array_column($laporanData, 'id_pembelian')));
        $totalQty = 0;
        $totalPengeluaran = 0;
        $bahanSeringDibeli = '-';
        
        $bahanStats = [];

        foreach ($laporanData as $row) {
            $totalQty += (int)($row->jumlah ?? 0);
            $totalPengeluaran += (float)($row->subtotal ?? 0);

            // Statistik frekuensi bahan
            $nama = $row->nama_bahan ?? '-';
            if ($nama != '-') {
                if (!isset($bahanStats[$nama])) {
                    $bahanStats[$nama] = 0;
                }
                $bahanStats[$nama] += (int)($row->jumlah ?? 0);
            }
        }

        if (!empty($bahanStats)) {
            arsort($bahanStats);
            $bahanSeringDibeli = key($bahanStats) . ' (' . number_format(current($bahanStats), 0, ',', '.') . ' Pcs/Kg)';
        }

        // Ambil data untuk dropdown filter
        $listVendor = $db->table('vendor')->select('id_vendor, nama_perusahaan')->get()->getResult();
        
        $metodeQuery = $db->query("SELECT DISTINCT metode_bayar FROM pembelian_bahan WHERE metode_bayar IS NOT NULL AND metode_bayar != ''")->getResult();
        $listMetode = array_column($metodeQuery, 'metode_bayar');

        $data = [
            'title' => 'Laporan Pembelian Bahan - FreshBakery',
            'periode' => $periode,
            'tanggal' => $tanggal,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'filterVendor' => $filterVendor,
            'filterMetode' => $filterMetode,
            'laporan' => $laporanData,
            'listVendor' => $listVendor,
            'listMetode' => $listMetode,
            'stats' => [
                'total_transaksi' => $totalTransaksi,
                'total_qty' => $totalQty,
                'total_pengeluaran' => $totalPengeluaran,
                'bahan_terpopuler' => $bahanSeringDibeli
            ]
        ];

        return view('laporan/pembelian', $data);
    }
}
