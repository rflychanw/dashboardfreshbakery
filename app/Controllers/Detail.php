<?php

namespace App\Controllers;
use Config\Database;

class Detail extends BaseController
{
    public function bahanBaku()
    {
        $db = Database::connect();
        $query = $db->query("SELECT bahan_baku.*, vendor.nama_perusahaan 
                             FROM bahan_baku 
                             LEFT JOIN vendor ON bahan_baku.id_vendor = vendor.id_vendor");
        $data = [
            'title' => 'Detail Bahan Baku - FreshBakery',
            'bahan_baku' => $query->getResult()
        ];
        return view('detail/bahan_baku', $data);
    }

    public function delivery()
    {
        $data = ['title' => 'Detail Delivery - FreshBakery'];
        return view('detail/delivery', $data);
    }

    public function pembelian()
    {
        $data = ['title' => 'Detail Pembelian - FreshBakery'];
        return view('detail/pembelian', $data);
    }

    public function penjualan()
    {
        $data = ['title' => 'Detail Penjualan - FreshBakery'];
        return view('detail/penjualan', $data);
    }
}
