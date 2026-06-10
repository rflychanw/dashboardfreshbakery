<?php

namespace App\Controllers;
use Config\Database;

class LaporanPenjualan extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        // Ambil tanggal transaksi terbaru dari database sebagai default agar data dummy 2024 terlihat
        $latestQuery = $db->query("SELECT MAX(tgl_teransaksi) as max_date FROM penjualan")->getRow();
        $latestDate = $latestQuery->max_date ?? date('Y-m-d H:i:s');
        $latestDateOnly = date('Y-m-d', strtotime($latestDate));

        // Parameter filter
        $periode = $this->request->getVar('periode') ?? 'semua';
        $tanggal = $this->request->getVar('tanggal') ?? $latestDateOnly;
        $start_date = $this->request->getVar('start_date') ?? date('Y-m-d', strtotime('-6 days', strtotime($latestDateOnly)));
        $end_date = $this->request->getVar('end_date') ?? $latestDateOnly;
        
        // Default bulan & tahun berdasarkan tanggal terbaru
        $defaultBulan = date('m', strtotime($latestDateOnly));
        $defaultTahun = date('Y', strtotime($latestDateOnly));
        
        $bulan = $this->request->getVar('bulan') ?? $defaultBulan;
        $tahun = $this->request->getVar('tahun') ?? $defaultTahun;
 
        // Query Builder untuk Laporan Penjualan (Detail Penjualan Item)
        $builder = $db->table('detail_penjualan')
            ->select('detail_penjualan.*, penjualan.tgl_teransaksi, produk.nama_produk, pelanggan.nama_lengkap, karyawan.nama_staf, pembayaran.Metode_pembayaran')
            ->join('penjualan', 'penjualan.No_faktur = detail_penjualan.No_faktur', 'left')
            ->join('produk', 'produk.kode_produk = detail_penjualan.kode_produk', 'left')
            ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left')
            ->join('karyawan', 'karyawan.nip_karyawan = penjualan.nip_karyawan', 'left')
            ->join('pembayaran', 'pembayaran.id_pembayaran = penjualan.id_pembayaran', 'left');

        if ($periode == 'hari') {
            $builder->where('DATE(penjualan.tgl_teransaksi)', $tanggal);
        } elseif ($periode == 'minggu') {
            $builder->where("DATE(penjualan.tgl_teransaksi) >=", $start_date);
            $builder->where("DATE(penjualan.tgl_teransaksi) <=", $end_date);
        } elseif ($periode == 'bulan') {
            $builder->where("MONTH(penjualan.tgl_teransaksi)", $bulan);
            $builder->where("YEAR(penjualan.tgl_teransaksi)", $tahun);
        }

        $laporanData = $builder->get()->getResult();

        // Hitung statistik ringkasan (summary stats)
        $uniqueFakturs = array_unique(array_column($laporanData, 'No_faktur'));
        $totalTransaksi = count($uniqueFakturs);
        
        $totalQty = 0;
        $totalDiskon = 0;
        $totalSubtotal = 0;
        $metodeTerpopuler = '-';
        $metodeCounts = [];

        foreach ($laporanData as $row) {
            $totalQty += (int)($row->qty_beli ?? 0);
            $totalDiskon += (float)($row->diskon_item ?? 0);
            $totalSubtotal += (float)($row->subtotal ?? 0);

            // Hitung metode pembayaran terpopuler
            if (!empty($row->Metode_pembayaran)) {
                $metode = $row->Metode_pembayaran;
                $metodeCounts[$metode] = ($metodeCounts[$metode] ?? 0) + 1;
            }
        }

        if (!empty($metodeCounts)) {
            arsort($metodeCounts);
            $metodeTerpopuler = array_keys($metodeCounts)[0] . ' (' . array_values($metodeCounts)[0] . ' kali)';
        }

        // List Tahun untuk dropdown (dari data unik tgl_teransaksi)
        $tahunQuery = $db->query("SELECT DISTINCT YEAR(tgl_teransaksi) as tahun FROM penjualan ORDER BY tgl_teransaksi DESC")->getResult();
        $listTahun = array_column($tahunQuery, 'tahun');
        if (empty($listTahun)) {
            $listTahun = [date('Y')];
        }

        $data = [
            'title' => 'Laporan Penjualan Produk - FreshBakery',
            'periode' => $periode,
            'tanggal' => $tanggal,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'laporan' => $laporanData,
            'stats' => [
                'total_transaksi' => $totalTransaksi,
                'total_qty' => $totalQty,
                'total_diskon' => $totalDiskon,
                'total_subtotal' => $totalSubtotal,
                'metode_terpopuler' => $metodeTerpopuler
            ],
            'listTahun' => $listTahun
        ];

        return view('laporan/penjualan', $data);
    }
}
