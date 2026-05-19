<?php

namespace App\Controllers;
use Config\Database;

class LaporanPengiriman extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        // Parameter filter
        $filterStatus = $this->request->getVar('status') ?? 'semua';
        $filterProvider = $this->request->getVar('provider') ?? 'semua';
        $filterBayar = $this->request->getVar('jenis_pembayaran') ?? 'semua';

        // Query Builder untuk Laporan Pengiriman
        $builder = $db->table('delivery')
            ->select('delivery.*, karyawan.nama_staf, produk.nama_produk, provider_pengiriman.nama_provider, provider_pengiriman.jenis_layanan, detail_delivery.biaya, detail_delivery.jumlah, detail_delivery.berat, detail_delivery.status as status_kirim')
            ->join('karyawan', 'karyawan.nip_karyawan = delivery.nip_karyawan', 'left')
            ->join('produk', 'produk.kode_produk = delivery.kode_produk', 'left')
            ->join('provider_pengiriman', 'provider_pengiriman.id_provider = delivery.id_provider', 'left')
            ->join('detail_delivery', 'detail_delivery.id_detail_kirim = delivery.id_detail_kirim', 'left');

        if ($filterStatus != 'semua') {
            $builder->where('detail_delivery.status', $filterStatus);
        }
        if ($filterProvider != 'semua') {
            $builder->where('delivery.id_provider', $filterProvider);
        }
        if ($filterBayar != 'semua') {
            $builder->where('delivery.Jenis_pembayaran', $filterBayar);
        }

        $laporanData = $builder->get()->getResult();

        // Hitung statistik
        $totalPengiriman = count($laporanData);
        $totalTerkirim = 0;
        $totalProses = 0;
        $totalPending = 0;
        $totalOngkir = 0;
        $totalQty = 0;

        foreach ($laporanData as $row) {
            $status = $row->status_kirim;
            if ($status == 'Terkirim') {
                $totalTerkirim++;
            } elseif ($status == 'Proses') {
                $totalProses++;
            } else {
                $totalPending++;
            }

            // Bersihkan biaya ongkir (dari "Rp 25.000" menjadi 25000)
            $cleanBiaya = (float)preg_replace('/[^0-9]/', '', $row->biaya ?? '0');
            $totalOngkir += $cleanBiaya;

            // Hitung qty barang dikirim
            $totalQty += (int)($row->jumlah ?? 0);
        }

        // Ambil data untuk opsi dropdown filter
        $listProvider = $db->table('provider_pengiriman')->select('id_provider, nama_provider, jenis_layanan')->get()->getResult();
        
        $bayarQuery = $db->query("SELECT DISTINCT Jenis_pembayaran FROM delivery WHERE Jenis_pembayaran IS NOT NULL AND Jenis_pembayaran != ''")->getResult();
        $listBayar = array_column($bayarQuery, 'Jenis_pembayaran');

        $data = [
            'title' => 'Laporan Pengiriman (Delivery) - FreshBakery',
            'filterStatus' => $filterStatus,
            'filterProvider' => $filterProvider,
            'filterBayar' => $filterBayar,
            'laporan' => $laporanData,
            'listProvider' => $listProvider,
            'listBayar' => $listBayar,
            'stats' => [
                'total_pengiriman' => $totalPengiriman,
                'total_terkirim' => $totalTerkirim,
                'total_proses' => $totalProses,
                'total_pending' => $totalPending,
                'total_ongkir' => $totalOngkir,
                'total_qty' => $totalQty
            ]
        ];

        return view('laporan/pengiriman', $data);
    }
}
