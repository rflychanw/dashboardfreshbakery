<?php

namespace App\Controllers;
use Config\Database;
class Penjualan extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();
        $query = $db->query("SELECT detail_penjualan.*, penjualan.tgl_teransaksi, penjualan.pajak_ppn, produk.nama_produk, karyawan.nama_staf, pelanggan.nama_lengkap, pembayaran.Metode_pembayaran 
                             FROM detail_penjualan 
                             LEFT JOIN penjualan ON penjualan.No_faktur = detail_penjualan.No_faktur
                             LEFT JOIN produk ON produk.kode_produk = detail_penjualan.kode_produk
                             LEFT JOIN karyawan ON karyawan.nip_karyawan = penjualan.nip_karyawan
                             LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
                             LEFT JOIN pembayaran ON pembayaran.id_pembayaran = penjualan.id_pembayaran");
        $data = [
            'title' => 'Data Penjualan - FreshBakery',
            'penjualan' => $query->getResult()
        ];
        return view('transaksi/penjualan', $data);
    }

    private function cleanNumber($val)
    {
        if (empty($val)) return 0;
        return floatval(preg_replace('/[^0-9.-]/', '', str_replace(['Rp', '.', ' '], ['', '', ''], $val)));
    }

    public function create()
    {
        $db = Database::connect();
        
        $no_faktur = $this->request->getPost('No__Faktur') ?? $this->request->getPost('No. Faktur') ?? $this->request->getPost('No Faktur') ?? ('F' . rand(1000, 9999));
        $pelanggan_input = $this->request->getPost('Pelanggan');
        $karyawan_input = $this->request->getPost('Karyawan');
        $tgl_transaksi = $this->request->getPost('Tanggal_Transaksi') ?? $this->request->getPost('Tanggal Transaksi') ?? date('Y-m-d H:i:s');
        $pajak_ppn_input = $this->request->getPost('Pajak_PPN') ?? $this->request->getPost('Pajak PPN') ?? 0;
        
        $nama_produk_input = $this->request->getPost('Nama_Produk') ?? $this->request->getPost('Nama Produk');
        $harga_satuan_input = $this->request->getPost('Harga_Satuan') ?? $this->request->getPost('Harga Satuan') ?? 0;
        $qty_input = $this->request->getPost('Jumlah__Qty_') ?? $this->request->getPost('Jumlah (Qty)') ?? $this->request->getPost('Jumlah') ?? 1;
        $diskon_input = $this->request->getPost('Diskon') ?? 0;
        $subtotal_input = $this->request->getPost('Sub_Total') ?? $this->request->getPost('Sub Total') ?? 0;
        
        $metode_pembayaran_input = $this->request->getPost('Metode_Pembayaran') ?? $this->request->getPost('Metode Pembayaran') ?? 'Tunai';

        $pajak_ppn = $this->cleanNumber($pajak_ppn_input);
        $harga_satuan = $this->cleanNumber($harga_satuan_input);
        $qty = intval(preg_replace('/[^0-9]/', '', $qty_input));
        $diskon_item = $this->cleanNumber($diskon_input);
        $subtotal = $this->cleanNumber($subtotal_input);

        if ($subtotal == 0) {
            $subtotal = ($harga_satuan * $qty) - $diskon_item;
        }

        // 1. Resolve Pelanggan ID
        $id_pelanggan = 'PL01';
        if (!empty($pelanggan_input)) {
            $pelRow = $db->table('pelanggan')->like('nama_lengkap', $pelanggan_input)->get()->getRowArray();
            if ($pelRow) {
                $id_pelanggan = $pelRow['Id_pelanggan'];
            }
        }

        // 2. Resolve Karyawan NIP
        $nip_karyawan = 1;
        if (!empty($karyawan_input)) {
            $karRow = $db->table('karyawan')->like('nama_staf', $karyawan_input)->get()->getRowArray();
            if ($karRow) {
                $nip_karyawan = $karRow['nip_karyawan'];
            }
        }

        // 3. Resolve Pembayaran ID
        $id_pembayaran = 1;
        if (!empty($metode_pembayaran_input)) {
            $pemRow = $db->table('pembayaran')->like('Metode_pembayaran', $metode_pembayaran_input)->get()->getRowArray();
            if ($pemRow) {
                $id_pembayaran = $pemRow['Id_pembayaran'];
            } else {
                $db->table('pembayaran')->insert([
                    'Metode_pembayaran' => $metode_pembayaran_input,
                    'nip_karyawan' => $nip_karyawan,
                    'id_pelanggan' => $id_pelanggan
                ]);
                $id_pembayaran = $db->insertID();
            }
        }

        // 4. Resolve Produk Kode
        $kode_produk = 'PR01';
        if (!empty($nama_produk_input)) {
            $prodRow = $db->table('produk')->like('nama_produk', $nama_produk_input)->get()->getRowArray();
            if ($prodRow) {
                $kode_produk = $prodRow['kode_produk'];
            }
        }

        // 5. Insert into penjualan
        $db->table('penjualan')->insert([
            'No_faktur' => $no_faktur,
            'id_pelanggan' => $id_pelanggan,
            'nip_karyawan' => $nip_karyawan,
            'id_pembayaran' => $id_pembayaran,
            'tgl_teransaksi' => $tgl_transaksi,
            'total_bruto' => $subtotal,
            'pajak_ppn' => $pajak_ppn,
            'total_netto' => $subtotal + $pajak_ppn
        ]);

        // 6. Insert into detail_penjualan
        $db->table('detail_penjualan')->insert([
            'No_faktur' => $no_faktur,
            'kode_produk' => $kode_produk,
            'harga_satuan' => $harga_satuan,
            'qty_beli' => $qty,
            'diskon_item' => $diskon_item,
            'subtotal' => $subtotal
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transaksi penjualan baru berhasil disimpan!'
        ]);
    }

    public function update($id)
    {
        $db = Database::connect();
        
        $pelanggan_input = $this->request->getPost('Pelanggan');
        $karyawan_input = $this->request->getPost('Karyawan');
        $tgl_transaksi = $this->request->getPost('Tanggal_Transaksi') ?? $this->request->getPost('Tanggal Transaksi') ?? date('Y-m-d H:i:s');
        $pajak_ppn_input = $this->request->getPost('Pajak_PPN') ?? $this->request->getPost('Pajak PPN') ?? 0;
        
        $nama_produk_input = $this->request->getPost('Nama_Produk') ?? $this->request->getPost('Nama Produk');
        $harga_satuan_input = $this->request->getPost('Harga_Satuan') ?? $this->request->getPost('Harga Satuan') ?? 0;
        $qty_input = $this->request->getPost('Jumlah__Qty_') ?? $this->request->getPost('Jumlah (Qty)') ?? $this->request->getPost('Jumlah') ?? 1;
        $diskon_input = $this->request->getPost('Diskon') ?? 0;
        $subtotal_input = $this->request->getPost('Sub_Total') ?? $this->request->getPost('Sub Total') ?? 0;
        
        $metode_pembayaran_input = $this->request->getPost('Metode_Pembayaran') ?? $this->request->getPost('Metode Pembayaran') ?? 'Tunai';

        $pajak_ppn = $this->cleanNumber($pajak_ppn_input);
        $harga_satuan = $this->cleanNumber($harga_satuan_input);
        $qty = intval(preg_replace('/[^0-9]/', '', $qty_input));
        $diskon_item = $this->cleanNumber($diskon_input);
        $subtotal = $this->cleanNumber($subtotal_input);

        if ($subtotal == 0) {
            $subtotal = ($harga_satuan * $qty) - $diskon_item;
        }

        // 1. Resolve Pelanggan ID
        $id_pelanggan = 'PL01';
        if (!empty($pelanggan_input)) {
            $pelRow = $db->table('pelanggan')->like('nama_lengkap', $pelanggan_input)->get()->getRowArray();
            if ($pelRow) {
                $id_pelanggan = $pelRow['Id_pelanggan'];
            }
        }

        // 2. Resolve Karyawan NIP
        $nip_karyawan = 1;
        if (!empty($karyawan_input)) {
            $karRow = $db->table('karyawan')->like('nama_staf', $karyawan_input)->get()->getRowArray();
            if ($karRow) {
                $nip_karyawan = $karRow['nip_karyawan'];
            }
        }

        // 3. Resolve Pembayaran ID
        $id_pembayaran = 1;
        if (!empty($metode_pembayaran_input)) {
            $pemRow = $db->table('pembayaran')->like('Metode_pembayaran', $metode_pembayaran_input)->get()->getRowArray();
            if ($pemRow) {
                $id_pembayaran = $pemRow['Id_pembayaran'];
            } else {
                $db->table('pembayaran')->insert([
                    'Metode_pembayaran' => $metode_pembayaran_input,
                    'nip_karyawan' => $nip_karyawan,
                    'id_pelanggan' => $id_pelanggan
                ]);
                $id_pembayaran = $db->insertID();
            }
        }

        // 4. Resolve Produk Kode
        $kode_produk = 'PR01';
        if (!empty($nama_produk_input)) {
            $prodRow = $db->table('produk')->like('nama_produk', $nama_produk_input)->get()->getRowArray();
            if ($prodRow) {
                $kode_produk = $prodRow['kode_produk'];
            }
        }

        // 5. Update penjualan
        $db->table('penjualan')->where('No_faktur', $id)->update([
            'id_pelanggan' => $id_pelanggan,
            'nip_karyawan' => $nip_karyawan,
            'id_pembayaran' => $id_pembayaran,
            'tgl_teransaksi' => $tgl_transaksi,
            'total_bruto' => $subtotal,
            'pajak_ppn' => $pajak_ppn,
            'total_netto' => $subtotal + $pajak_ppn
        ]);

        // 6. Update detail_penjualan
        $db->table('detail_penjualan')->where('No_faktur', $id)->update([
            'kode_produk' => $kode_produk,
            'harga_satuan' => $harga_satuan,
            'qty_beli' => $qty,
            'diskon_item' => $diskon_item,
            'subtotal' => $subtotal
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transaksi penjualan berhasil diperbarui!'
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();
        
        $db->table('detail_penjualan')->where('No_faktur', $id)->delete();
        $db->table('penjualan')->where('No_faktur', $id)->delete();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transaksi penjualan berhasil dihapus!'
        ]);
    }
}

