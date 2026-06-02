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
    
    // Provider Pengiriman
    $routes->get('provider', 'Provider::index');
    $routes->post('provider/create', 'Provider::create');
    $routes->post('provider/update/(:any)', 'Provider::update/$1');
    $routes->post('provider/delete/(:any)', 'Provider::delete/$1');

    // Karyawan
    $routes->get('karyawan', 'Karyawan::index');
    $routes->post('karyawan/create', 'Karyawan::create');
    $routes->post('karyawan/update/(:any)', 'Karyawan::update/$1');
    $routes->post('karyawan/delete/(:any)', 'Karyawan::delete/$1');

    // Departemen
    $routes->get('departemen', 'Departemen::index');
    $routes->post('departemen/create', 'Departemen::create');
    $routes->post('departemen/update/(:any)', 'Departemen::update/$1');
    $routes->post('departemen/delete/(:any)', 'Departemen::delete/$1');

    // Vendor
    $routes->get('vendor', 'Vendor::index');
    $routes->post('vendor/create', 'Vendor::create');
    $routes->post('vendor/update/(:any)', 'Vendor::update/$1');
    $routes->post('vendor/delete/(:any)', 'Vendor::delete/$1');

    // Produk
    $routes->get('produk', 'Produk::index');
    $routes->post('produk/create', 'Produk::create');
    $routes->post('produk/update/(:any)', 'Produk::update/$1');
    $routes->post('produk/delete/(:any)', 'Produk::delete/$1');

    // Bahan Baku
    $routes->get('bahan-baku', 'BahanBaku::index');
    $routes->post('bahan-baku/create', 'BahanBaku::create');
    $routes->post('bahan-baku/update/(:any)', 'BahanBaku::update/$1');
    $routes->post('bahan-baku/delete/(:any)', 'BahanBaku::delete/$1');

    // Pelanggan
    $routes->get('pelanggan', 'Pelanggan::index');
    $routes->post('pelanggan/create', 'Pelanggan::create');
    $routes->post('pelanggan/update/(:any)', 'Pelanggan::update/$1');
    $routes->post('pelanggan/delete/(:any)', 'Pelanggan::delete/$1');

    // Daftar Harga
    $routes->get('daftar-harga', 'DaftarHarga::index');
    $routes->post('daftar-harga/create', 'DaftarHarga::create');
    $routes->post('daftar-harga/update/(:any)', 'DaftarHarga::update/$1');
    $routes->post('daftar-harga/delete/(:any)', 'DaftarHarga::delete/$1');

    // Transaksi Routes
    // Pembelian
    $routes->get('pembelian', 'Pembelian::index');
    $routes->post('pembelian/create', 'Pembelian::create');
    $routes->post('pembelian/update/(:any)', 'Pembelian::update/$1');
    $routes->post('pembelian/delete/(:any)', 'Pembelian::delete/$1');

    // Penjualan
    $routes->get('penjualan', 'Penjualan::index');
    $routes->post('penjualan/create', 'Penjualan::create');
    $routes->post('penjualan/update/(:any)', 'Penjualan::update/$1');
    $routes->post('penjualan/delete/(:any)', 'Penjualan::delete/$1');

    // Pembayaran
    $routes->get('pembayaran', 'Pembayaran::index');
    $routes->post('pembayaran/create', 'Pembayaran::create');
    $routes->post('pembayaran/update/(:any)', 'Pembayaran::update/$1');
    $routes->post('pembayaran/delete/(:any)', 'Pembayaran::delete/$1');

    // Delivery
    $routes->get('delivery', 'Delivery::index');
    $routes->post('delivery/create', 'Delivery::create');
    $routes->post('delivery/update/(:any)', 'Delivery::update/$1');
    $routes->post('delivery/delete/(:any)', 'Delivery::delete/$1');

    // Produksi
    $routes->get('produksi', 'Produksi::index');
    $routes->post('produksi/create', 'Produksi::create');
    $routes->post('produksi/update/(:any)', 'Produksi::update/$1');
    $routes->post('produksi/delete/(:any)', 'Produksi::delete/$1');

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
