<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Auth Routes
$routes->get('login', 'Auth::login');
$routes->post('login/auth', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');

// Protected Routes
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('provider', 'Provider::index');
    $routes->get('karyawan', 'Karyawan::index');
    $routes->get('departemen', 'Departemen::index');
    $routes->get('vendor', 'Vendor::index');
    $routes->get('produk', 'Produk::index');
    $routes->get('bahan-baku', 'BahanBaku::index');
    $routes->get('pelanggan', 'Pelanggan::index');
    $routes->get('daftar-harga', 'DaftarHarga::index');

    // Transaksi Routes
    $routes->get('pembelian', 'Pembelian::index');
    $routes->get('penjualan', 'Penjualan::index');
    $routes->get('pembayaran', 'Pembayaran::index');
    $routes->get('delivery', 'Delivery::index');
    $routes->get('produksi', 'Produksi::index');

    // Detail Routes
    $routes->get('detail/bahan-baku', 'Detail::bahanBaku');
    $routes->get('detail/delivery', 'Detail::delivery');
    $routes->get('detail/pembelian', 'Detail::pembelian');
    $routes->get('detail/penjualan', 'Detail::penjualan');

    // Laporan Routes
    $routes->get('laporan/produksi', 'LaporanProduksi::index');
    $routes->get('laporan/penjualan', 'LaporanPenjualan::index');
    $routes->get('laporan/pengiriman', 'LaporanPengiriman::index');
    $routes->get('laporan/absen', 'LaporanAbsen::index');
    $routes->get('laporan/pembelian', 'LaporanPembelian::index');
});
