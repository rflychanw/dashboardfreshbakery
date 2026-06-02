<?php

namespace App\Controllers;
use Config\Database;
class Pembelian extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();
        $query = $db->query("SELECT pembelian_bahan.*, vendor.nama_perusahaan
                             FROM pembelian_bahan 
                             LEFT JOIN vendor ON vendor.id_vendor = pembelian_bahan.id_vendor");
        $data = [
            'title' => 'Data Pembelian - FreshBakery',
            'pembelian' => $query->getResult()
        ];
        return view('transaksi/pembelian', $data);
    }

    private function cleanNumber($val)
    {
        if (empty($val)) return 0;
        return floatval(preg_replace('/[^0-9.-]/', '', str_replace(['Rp', '.', ' '], ['', '', ''], $val)));
    }

    public function create()
    {
        $db = Database::connect();
        
        $id_pembelian = $this->request->getPost('ID') ?? $this->request->getPost('Id_Pembelian') ?? $this->request->getPost('Id Pembelian') ?? $this->request->getPost('No_PO') ?? $this->request->getPost('No__PO') ?? ('PB' . rand(100, 999));
        $tgl_pembelian = $this->request->getPost('Tanggal_Pembelian') ?? $this->request->getPost('Tanggal Pembelian') ?? $this->request->getPost('Tanggal') ?? date('Y-m-d');
        $vendor_input = $this->request->getPost('Vendor');
        
        $total_bayar_input = $this->request->getPost('Total_Bayar') ?? $this->request->getPost('Total Bayar') ?? $this->request->getPost('Total Harga') ?? $this->request->getPost('Total_Harga') ?? 0;
        $total_bayar = $this->cleanNumber($total_bayar_input);
        
        $metode_bayar = $this->request->getPost('Metode_Pembayaran') ?? $this->request->getPost('Metode Pembayaran') ?? 'Tunai';

        // Resolve vendor ID
        $id_vendor = 1;
        if (!empty($vendor_input)) {
            $vendorRow = $db->table('vendor')
                ->groupStart()
                    ->where('id_vendor', $vendor_input)
                    ->orLike('nama_perusahaan', $vendor_input)
                ->groupEnd()
                ->get()->getRowArray();
            if ($vendorRow) {
                $id_vendor = $vendorRow['id_vendor'];
            }
        }

        $db->table('pembelian_bahan')->insert([
            'id_pembelian' => $id_pembelian,
            'tgl_pembelian' => $tgl_pembelian,
            'id_vendor' => $id_vendor,
            'total_bayar' => $total_bayar,
            'metode_bayar' => $metode_bayar
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transaksi pembelian baru berhasil ditambahkan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $tgl_pembelian = $this->request->getPost('Tanggal_Pembelian') ?? $this->request->getPost('Tanggal Pembelian') ?? $this->request->getPost('Tanggal') ?? date('Y-m-d');
        $vendor_input = $this->request->getPost('Vendor');
        
        $total_bayar_input = $this->request->getPost('Total_Bayar') ?? $this->request->getPost('Total Bayar') ?? $this->request->getPost('Total Harga') ?? $this->request->getPost('Total_Harga') ?? 0;
        $total_bayar = $this->cleanNumber($total_bayar_input);
        
        $metode_bayar = $this->request->getPost('Metode_Pembayaran') ?? $this->request->getPost('Metode Pembayaran') ?? 'Tunai';

        // Resolve vendor ID
        $id_vendor = 1;
        if (!empty($vendor_input)) {
            $vendorRow = $db->table('vendor')
                ->groupStart()
                    ->where('id_vendor', $vendor_input)
                    ->orLike('nama_perusahaan', $vendor_input)
                ->groupEnd()
                ->get()->getRowArray();
            if ($vendorRow) {
                $id_vendor = $vendorRow['id_vendor'];
            }
        }

        $db->table('pembelian_bahan')->where('id_pembelian', $id)->update([
            'tgl_pembelian' => $tgl_pembelian,
            'id_vendor' => $id_vendor,
            'total_bayar' => $total_bayar,
            'metode_bayar' => $metode_bayar
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data pembelian berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        $db->table('pembelian_bahan')->where('id_pembelian', $id)->delete();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transaksi pembelian berhasil dihapus!'
        ]);
    }
}

