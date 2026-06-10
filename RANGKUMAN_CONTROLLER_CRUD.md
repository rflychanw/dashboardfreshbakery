# 📘 RANGKUMAN FUNGSI CONTROLLER CRUD & QUERY SQL
# Proyek: Dashboard FreshBakery (CodeIgniter 4)

> Dokumen ini merangkum **semua fungsi CRUD** beserta **query SQL / Query Builder** yang
> digunakan di setiap controller proyek FreshBakery agar bisa dipelajari.

---

## DAFTAR ISI

1.  [Pola Umum yang Digunakan](#-pola-umum-yang-digunakan)
2.  [Controller MASTER](#-controller-master-data)
    - Produk
    - Pelanggan
    - Karyawan
    - Vendor
    - Departemen
    - Provider
    - DaftarHarga
3.  [Controller TRANSAKSI](#-controller-transaksi)
    - Penjualan
    - Pembelian
    - Pembayaran
    - Produksi
    - Delivery
4.  [Controller LAPORAN](#-controller-laporan-read-only)
    - LaporanPenjualan
    - LaporanPembelian
    - LaporanProduksi
    - LaporanPengiriman
    - LaporanAbsen
5.  [Cheatsheet Query Builder CI4 ke SQL](#-cheatsheet-query-builder-ci4--sql)
6.  [Diagram Relasi Antar Tabel](#-diagram-relasi-antar-tabel)

---

## 📌 POLA UMUM YANG DIGUNAKAN

### 1. Koneksi Database
Selalu dipanggil di awal setiap fungsi:
```php
$db = Database::connect();
```

### 2. Dua Cara Mengakses Database

| Metode           | Sintaks                                              | Kapan Dipakai                                    |
|------------------|------------------------------------------------------|--------------------------------------------------|
| Raw SQL          | $db->query("SELECT ...")                             | Query sederhana, biasanya di index()             |
| Query Builder    | $db->table('tabel')->select()->where()->get()        | CRUD (insert/update/delete) dan filter dinamis   |

### 3. Helper cleanNumber() — Membersihkan Input Harga
Mengubah format "Rp 25.000" menjadi angka 25000:
```php
private function cleanNumber($val)
{
    if (empty($val)) return 0;
    return floatval(preg_replace('/[^0-9.-]/', '', str_replace(['Rp', '.', ' '], ['', '', ''], $val)));
}
```
Dipakai di: Produk, Penjualan, Pembelian, BahanBaku, DaftarHarga

### 4. Helper resolveId() — Mencari Foreign Key dari Nama
Mencari ID tabel lain berdasarkan input nama atau ID langsung:
```php
$vendorRow = $db->table('vendor')
    ->groupStart()
        ->where('id_vendor', $input)          // coba cocokkan sebagai ID
        ->orLike('nama_perusahaan', $input)   // atau cocokkan sebagai nama
    ->groupEnd()
    ->get()->getRowArray();
```
SQL yang dihasilkan:
```sql
SELECT * FROM vendor WHERE (id_vendor = 'input' OR nama_perusahaan LIKE '%input%')
```
Dipakai di: Karyawan, BahanBaku, Pembelian, Produksi, Delivery, Pembayaran, Penjualan

### 5. Helper nextId() — Auto Increment Manual
Mencari ID terbesar lalu ditambah 1:
```php
$row = $db->table($table)->selectMax($column, 'max_id')->get()->getRowArray();
return ((int) ($row['max_id'] ?? 0)) + 1;
```
SQL yang dihasilkan:
```sql
SELECT MAX(Id_produksi) as max_id FROM produksi;
```
Dipakai di: Produksi, Delivery

---

## 📦 CONTROLLER MASTER DATA

========================================================================
### 1. PRODUK
========================================================================
File    : app/Controllers/Produk.php
Tabel   : produk, daftar_harga
PK      : kode_produk

#### index() — READ (Menampilkan Semua Produk)

Metode: Raw SQL

```sql
SELECT produk.*, daftar_harga.Harga_diskon
FROM produk
LEFT JOIN daftar_harga ON daftar_harga.Id_detail_harga = produk.Id_detail_harga
```
Penjelasan: Mengambil semua data produk + harga diskon dari tabel daftar_harga menggunakan LEFT JOIN.

#### create() — CREATE (Tambah Produk Baru)

Metode: Query Builder (2 langkah insert)

```php
// Langkah 1: Insert ke daftar_harga dulu
$db->table('daftar_harga')->insert([
    'kode_produk'  => $kode_produk,
    'Harga_asli'   => $harga_jual,
    'Harga_diskon' => $harga_diskon
]);
$id_detail_harga = $db->insertID();  // ambil ID yang baru dibuat
```
SQL:
```sql
INSERT INTO daftar_harga (kode_produk, Harga_asli, Harga_diskon) VALUES ('PR001', 15000, 12000);
-- Lalu ambil LAST_INSERT_ID()
```

```php
// Langkah 2: Insert ke produk dengan FK ke daftar_harga
$db->table('produk')->insert([
    'kode_produk'     => $kode_produk,
    'Id_detail_harga' => $id_detail_harga,
    'nama_produk'     => $nama_produk,
    'kategori_roti'   => $kategori_roti,
    'stok'            => $stok,
    'harga_jual'      => $harga_jual,
    'masa_kadaluarsa' => $masa_kadaluarsa
]);
```
SQL:
```sql
INSERT INTO produk (kode_produk, Id_detail_harga, nama_produk, kategori_roti, stok, harga_jual, masa_kadaluarsa)
VALUES ('PR001', 1, 'Roti Coklat', 'Roti', 50, 15000, '2024-12-31');
```

#### update($id) — UPDATE (Edit Produk)

Metode: Query Builder (cari dulu, update 2 tabel)

```php
// Cari produk yang mau diedit
$prod = $db->table('produk')->where('kode_produk', $id)->get()->getRowArray();
```
SQL: `SELECT * FROM produk WHERE kode_produk = 'PR001';`

```php
// Update harga di daftar_harga
$db->table('daftar_harga')->where('Id_detail_harga', $prod['Id_detail_harga'])->update([
    'Harga_asli'   => $harga_jual,
    'Harga_diskon' => $harga_diskon
]);
```
SQL: `UPDATE daftar_harga SET Harga_asli = 15000, Harga_diskon = 12000 WHERE Id_detail_harga = 1;`

```php
// Update data produk
$db->table('produk')->where('kode_produk', $id)->update([
    'nama_produk'     => $nama_produk,
    'kategori_roti'   => $kategori_roti,
    'stok'            => $stok,
    'harga_jual'      => $harga_jual,
    'masa_kadaluarsa' => $masa_kadaluarsa
]);
```
SQL: `UPDATE produk SET nama_produk = 'Roti Coklat', ... WHERE kode_produk = 'PR001';`

#### delete($id) — DELETE (Hapus Produk)

Metode: Query Builder (hapus child dulu, baru parent)

```php
// Hapus harga terkait dulu (child)
$prod = $db->table('produk')->where('kode_produk', $id)->get()->getRowArray();
$db->table('daftar_harga')->where('Id_detail_harga', $prod['Id_detail_harga'])->delete();
// Baru hapus produk (parent)
$db->table('produk')->where('kode_produk', $id)->delete();
```
SQL:
```sql
DELETE FROM daftar_harga WHERE Id_detail_harga = 1;
DELETE FROM produk WHERE kode_produk = 'PR001';
```
PENTING: Urutan hapus → child dulu (daftar_harga), baru parent (produk) agar tidak melanggar FK.


========================================================================
### 2. PELANGGAN
========================================================================
File    : app/Controllers/Pelanggan.php
Tabel   : pelanggan
PK      : Id_pelanggan

#### index() — READ
```sql
SELECT * FROM pelanggan
```

#### create() — CREATE
```php
$db->table('pelanggan')->insert([
    'Id_pelanggan'         => $id_pelanggan,
    'nama_lengkap'         => $nama_lengkap,
    'no_tlp'               => $no_tlp,
    'total_poin_loyalitas' => $total_poin_loyalitas
]);
```
SQL:
```sql
INSERT INTO pelanggan (Id_pelanggan, nama_lengkap, no_tlp, total_poin_loyalitas)
VALUES ('PL01', 'Budi Santoso', '08123456789', 0);
```

#### update($id) — UPDATE
```php
$db->table('pelanggan')->where('Id_pelanggan', $id)->update([
    'nama_lengkap'         => $nama_lengkap,
    'no_tlp'               => $no_tlp,
    'total_poin_loyalitas' => $total_poin_loyalitas
]);
```
SQL:
```sql
UPDATE pelanggan SET nama_lengkap = 'Budi Santoso', no_tlp = '08123456789', total_poin_loyalitas = 10
WHERE Id_pelanggan = 'PL01';
```

#### delete($id) — DELETE
```php
$db->table('pelanggan')->where('Id_pelanggan', $id)->delete();
```
SQL:
```sql
DELETE FROM pelanggan WHERE Id_pelanggan = 'PL01';
```


========================================================================
### 3. KARYAWAN
========================================================================
File    : app/Controllers/Karyawan.php
Tabel   : karyawan, departemen
PK      : nip_karyawan

#### index() — READ (dengan JOIN departemen)
```sql
SELECT karyawan.*, departemen.nama_dept
FROM karyawan
LEFT JOIN departemen ON karyawan.id_dept = departemen.id_dept
```
Penjelasan: Menampilkan data karyawan beserta nama departemen-nya.

#### create() — CREATE (dengan resolve departemen)
```php
// 1. Resolve departemen ID dari input (bisa nama atau ID)
$deptRow = $db->table('departemen')
    ->groupStart()
        ->where('id_dept', $departemen_input)
        ->orLike('nama_dept', $departemen_input)
    ->groupEnd()
    ->get()->getRowArray();
```
SQL: `SELECT * FROM departemen WHERE (id_dept = 'input' OR nama_dept LIKE '%input%');`

```php
// 2. Insert karyawan
$db->table('karyawan')->insert([
    'nip_karyawan' => $nip,
    'id_dept'      => $id_dept,
    'nama_staf'    => $nama_staf,
    'jabatan'      => $jabatan,
    'email_kantor' => $email_kantor,
    'tgl_masuk'    => $tgl_masuk,
    'status_staf'  => $status_staf
]);
```
SQL:
```sql
INSERT INTO karyawan (nip_karyawan, id_dept, nama_staf, jabatan, email_kantor, tgl_masuk, status_staf)
VALUES (123456, 1, 'Siti Aminah', 'Kasir', 'siti@freshbakery.com', '2024-01-15', 'Aktif');
```

#### update($id) — UPDATE
```php
$db->table('karyawan')->where('nip_karyawan', $id)->update([
    'id_dept' => $id_dept, 'nama_staf' => $nama_staf, 'jabatan' => $jabatan,
    'email_kantor' => $email_kantor, 'tgl_masuk' => $tgl_masuk, 'status_staf' => $status_staf
]);
```
SQL:
```sql
UPDATE karyawan SET id_dept = 1, nama_staf = 'Siti', jabatan = 'Kasir', ...
WHERE nip_karyawan = 123456;
```

#### delete($id) — DELETE
```php
$db->table('karyawan')->where('nip_karyawan', $id)->delete();
```
SQL: `DELETE FROM karyawan WHERE nip_karyawan = 123456;`


========================================================================
### 4. VENDOR
========================================================================
File    : app/Controllers/Vendor.php
Tabel   : vendor
PK      : id_vendor

#### index() — READ
```sql
SELECT * FROM vendor
```

#### create() — CREATE
```php
$db->table('vendor')->insert([
    'id_vendor'         => $id_vendor,
    'nama_perusahaan'   => $nama_perusahaan,
    'nama_sales'        => $nama_sales,
    'no_telp'           => $no_telp,
    'alamat_kantor'     => $alamat_kantor,
    'termin_pembayaran' => $termin_pembayaran
]);
```
SQL:
```sql
INSERT INTO vendor (id_vendor, nama_perusahaan, nama_sales, no_telp, alamat_kantor, termin_pembayaran)
VALUES (10, 'PT Tepung Jaya', 'Ahmad', '021-555', 'Jl. Raya No.1', '30 Hari');
```

#### update($id) — UPDATE
```php
$db->table('vendor')->where('id_vendor', $id)->update([
    'nama_perusahaan' => ..., 'nama_sales' => ..., 'no_telp' => ...,
    'alamat_kantor' => ..., 'termin_pembayaran' => ...
]);
```
SQL: `UPDATE vendor SET nama_perusahaan = '...', ... WHERE id_vendor = 10;`

#### delete($id) — DELETE
```php
$db->table('vendor')->where('id_vendor', $id)->delete();
```
SQL: `DELETE FROM vendor WHERE id_vendor = 10;`


========================================================================
### 5. DEPARTEMEN
========================================================================
File    : app/Controllers/Departemen.php
Tabel   : departemen
PK      : id_dept

#### index() — READ
```sql
SELECT * FROM departemen
```

#### create() — CREATE
```php
$db->table('departemen')->insert([
    'id_dept'        => $id_dept,
    'nama_dept'      => $nama_dept,
    'budget_tahunan' => $budget_tahunan
]);
```
SQL: `INSERT INTO departemen (id_dept, nama_dept, budget_tahunan) VALUES (1, 'Produksi', 50000000);`

#### update($id) — UPDATE
```php
$db->table('departemen')->where('id_dept', $id)->update([
    'nama_dept' => $nama_dept, 'budget_tahunan' => $budget_tahunan
]);
```
SQL: `UPDATE departemen SET nama_dept = 'Produksi', budget_tahunan = 50000000 WHERE id_dept = 1;`

#### delete($id) — DELETE
```php
$db->table('departemen')->where('id_dept', $id)->delete();
```
SQL: `DELETE FROM departemen WHERE id_dept = 1;`


========================================================================
### 6. PROVIDER
========================================================================
File    : app/Controllers/Provider.php
Tabel   : provider_pengiriman
PK      : id_provider

#### index() — READ
```sql
SELECT * FROM provider_pengiriman
```

#### create() — CREATE
```php
$db->table('provider_pengiriman')->insert([
    'nama_provider' => $nama_provider,
    'jenis_layanan' => $jenis_layanan,
    'no_telp'       => $no_telp,
    'alamat_kantor' => $alamat_kantor,
    'status_aktif'  => $status_aktif
]);
```
SQL:
```sql
INSERT INTO provider_pengiriman (nama_provider, jenis_layanan, no_telp, alamat_kantor, status_aktif)
VALUES ('JNE', 'Reguler', '021-999', 'Jl. Kirim No.5', 'Aktif');
```

#### update($id) — UPDATE
```php
$db->table('provider_pengiriman')->where('id_provider', $id)->update([...]);
```
SQL: `UPDATE provider_pengiriman SET nama_provider = '...', ... WHERE id_provider = 1;`

#### delete($id) — DELETE
```php
$db->table('provider_pengiriman')->where('id_provider', $id)->delete();
```
SQL: `DELETE FROM provider_pengiriman WHERE id_provider = 1;`


========================================================================
### 7. DAFTAR HARGA
========================================================================
File    : app/Controllers/DaftarHarga.php
Tabel   : daftar_harga
PK      : Id_detail_harga

#### index() — READ
```sql
SELECT * FROM daftar_harga
```

#### create() — CREATE
```php
$db->table('daftar_harga')->insert([
    'kode_produk'  => $kode_produk,
    'Harga_asli'   => $harga_asli,     // sudah di-cleanNumber()
    'Harga_diskon' => $harga_diskon    // sudah di-cleanNumber()
]);
```
SQL: `INSERT INTO daftar_harga (kode_produk, Harga_asli, Harga_diskon) VALUES ('PR001', 15000, 12000);`

#### update($id) — UPDATE
```php
$db->table('daftar_harga')->where('Id_detail_harga', $id)->update([
    'kode_produk' => ..., 'Harga_asli' => ..., 'Harga_diskon' => ...
]);
```
SQL: `UPDATE daftar_harga SET kode_produk = 'PR001', Harga_asli = 15000 WHERE Id_detail_harga = 1;`

#### delete($id) — DELETE
```php
$db->table('daftar_harga')->where('Id_detail_harga', $id)->delete();
```
SQL: `DELETE FROM daftar_harga WHERE Id_detail_harga = 1;`


---

## 💳 CONTROLLER TRANSAKSI

========================================================================
### 8. PENJUALAN (Paling Kompleks — 6 tabel terlibat)
========================================================================
File    : app/Controllers/Penjualan.php
Tabel   : penjualan (header), detail_penjualan (detail item)
Relasi  : pelanggan, karyawan, pembayaran, produk
PK      : No_faktur

#### index() — READ (5 JOIN sekaligus)
```sql
SELECT detail_penjualan.*,
       penjualan.tgl_teransaksi,
       penjualan.pajak_ppn,
       produk.nama_produk,
       karyawan.nama_staf,
       pelanggan.nama_lengkap,
       pembayaran.Metode_pembayaran
FROM detail_penjualan
LEFT JOIN penjualan   ON penjualan.No_faktur     = detail_penjualan.No_faktur
LEFT JOIN produk      ON produk.kode_produk      = detail_penjualan.kode_produk
LEFT JOIN karyawan    ON karyawan.nip_karyawan   = penjualan.nip_karyawan
LEFT JOIN pelanggan   ON pelanggan.id_pelanggan  = penjualan.id_pelanggan
LEFT JOIN pembayaran  ON pembayaran.id_pembayaran = penjualan.id_pembayaran
```
Penjelasan:
- Tabel utama = detail_penjualan (karena menampilkan per-item)
- JOIN penjualan     → ambil tanggal & pajak
- JOIN produk        → ambil nama produk
- JOIN karyawan      → ambil nama kasir
- JOIN pelanggan     → ambil nama pelanggan
- JOIN pembayaran    → ambil metode bayar

#### create() — CREATE (6 Langkah)

LANGKAH 1: Resolve Pelanggan ID (cari dari nama)
```php
$pelRow = $db->table('pelanggan')->like('nama_lengkap', $pelanggan_input)->get()->getRowArray();
```
SQL: `SELECT * FROM pelanggan WHERE nama_lengkap LIKE '%Budi%';`

LANGKAH 2: Resolve Karyawan NIP (cari dari nama)
```php
$karRow = $db->table('karyawan')->like('nama_staf', $karyawan_input)->get()->getRowArray();
```
SQL: `SELECT * FROM karyawan WHERE nama_staf LIKE '%Siti%';`

LANGKAH 3: Resolve Pembayaran ID (cari atau buat baru)
```php
$pemRow = $db->table('pembayaran')->like('Metode_pembayaran', $metode)->get()->getRowArray();
if (!$pemRow) {
    // Jika belum ada, buat record pembayaran baru
    $db->table('pembayaran')->insert([
        'Metode_pembayaran' => $metode,
        'nip_karyawan'      => $nip_karyawan,
        'id_pelanggan'      => $id_pelanggan
    ]);
    $id_pembayaran = $db->insertID();
}
```
SQL:
```sql
SELECT * FROM pembayaran WHERE Metode_pembayaran LIKE '%Tunai%';
-- Jika tidak ada:
INSERT INTO pembayaran (Metode_pembayaran, nip_karyawan, id_pelanggan) VALUES ('Tunai', 1, 'PL01');
```

LANGKAH 4: Resolve Produk Kode (cari dari nama)
```php
$prodRow = $db->table('produk')->like('nama_produk', $nama_produk_input)->get()->getRowArray();
```
SQL: `SELECT * FROM produk WHERE nama_produk LIKE '%Roti Coklat%';`

LANGKAH 5: Insert ke tabel penjualan (HEADER)
```php
$db->table('penjualan')->insert([
    'No_faktur'      => $no_faktur,
    'id_pelanggan'   => $id_pelanggan,
    'nip_karyawan'   => $nip_karyawan,
    'id_pembayaran'  => $id_pembayaran,
    'tgl_teransaksi' => $tgl_transaksi,
    'total_bruto'    => $subtotal,
    'pajak_ppn'      => $pajak_ppn,
    'total_netto'    => $subtotal + $pajak_ppn
]);
```
SQL:
```sql
INSERT INTO penjualan (No_faktur, id_pelanggan, nip_karyawan, id_pembayaran, tgl_teransaksi, total_bruto, pajak_ppn, total_netto)
VALUES ('F1001', 'PL01', 1, 1, '2024-12-01 10:30:00', 45000, 4500, 49500);
```

LANGKAH 6: Insert ke tabel detail_penjualan (DETAIL ITEM)
```php
$db->table('detail_penjualan')->insert([
    'No_faktur'    => $no_faktur,
    'kode_produk'  => $kode_produk,
    'harga_satuan' => $harga_satuan,
    'qty_beli'     => $qty,
    'diskon_item'  => $diskon_item,
    'subtotal'     => $subtotal
]);
```
SQL:
```sql
INSERT INTO detail_penjualan (No_faktur, kode_produk, harga_satuan, qty_beli, diskon_item, subtotal)
VALUES ('F1001', 'PR001', 15000, 3, 0, 45000);
```

CATATAN PENTING:
- Tabel penjualan = HEADER (info siapa, kapan, bayar apa)
- Tabel detail_penjualan = DETAIL (barang apa, berapa banyak, berapa harga)
- Dihubungkan lewat kolom No_faktur

#### update($id) — UPDATE (2 tabel sekaligus)
```php
// Update header (penjualan)
$db->table('penjualan')->where('No_faktur', $id)->update([
    'id_pelanggan' => ..., 'nip_karyawan' => ..., 'id_pembayaran' => ...,
    'tgl_teransaksi' => ..., 'total_bruto' => ..., 'pajak_ppn' => ..., 'total_netto' => ...
]);

// Update detail (detail_penjualan)
$db->table('detail_penjualan')->where('No_faktur', $id)->update([
    'kode_produk' => ..., 'harga_satuan' => ..., 'qty_beli' => ...,
    'diskon_item' => ..., 'subtotal' => ...
]);
```
SQL:
```sql
UPDATE penjualan SET id_pelanggan = 'PL01', ... WHERE No_faktur = 'F1001';
UPDATE detail_penjualan SET kode_produk = 'PR001', ... WHERE No_faktur = 'F1001';
```

#### delete($id) — DELETE (URUTAN PENTING!)
```php
$db->table('detail_penjualan')->where('No_faktur', $id)->delete();  // hapus detail dulu
$db->table('penjualan')->where('No_faktur', $id)->delete();         // baru hapus header
```
SQL:
```sql
DELETE FROM detail_penjualan WHERE No_faktur = 'F1001';  -- child dulu
DELETE FROM penjualan WHERE No_faktur = 'F1001';         -- parent kemudian
```


========================================================================
### 9. PEMBELIAN
========================================================================
File    : app/Controllers/Pembelian.php
Tabel   : pembelian_bahan, vendor
PK      : id_pembelian

#### index() — READ
```sql
SELECT pembelian_bahan.*, vendor.nama_perusahaan
FROM pembelian_bahan
LEFT JOIN vendor ON vendor.id_vendor = pembelian_bahan.id_vendor
```

#### create() — CREATE (dengan resolve vendor)
```php
// Resolve vendor ID dari nama perusahaan
$vendorRow = $db->table('vendor')
    ->groupStart()
        ->where('id_vendor', $vendor_input)
        ->orLike('nama_perusahaan', $vendor_input)
    ->groupEnd()
    ->get()->getRowArray();

// Insert pembelian
$db->table('pembelian_bahan')->insert([
    'id_pembelian'  => $id_pembelian,
    'tgl_pembelian' => $tgl_pembelian,
    'id_vendor'     => $id_vendor,
    'total_bayar'   => $total_bayar,    // sudah di-cleanNumber()
    'metode_bayar'  => $metode_bayar
]);
```
SQL:
```sql
SELECT * FROM vendor WHERE (id_vendor = 'input' OR nama_perusahaan LIKE '%input%');

INSERT INTO pembelian_bahan (id_pembelian, tgl_pembelian, id_vendor, total_bayar, metode_bayar)
VALUES ('PB101', '2024-12-01', 1, 500000, 'Tunai');
```

#### update($id) — UPDATE
```php
$db->table('pembelian_bahan')->where('id_pembelian', $id)->update([
    'tgl_pembelian' => ..., 'id_vendor' => ..., 'total_bayar' => ..., 'metode_bayar' => ...
]);
```
SQL: `UPDATE pembelian_bahan SET tgl_pembelian = '...', ... WHERE id_pembelian = 'PB101';`

#### delete($id) — DELETE
```php
$db->table('pembelian_bahan')->where('id_pembelian', $id)->delete();
```
SQL: `DELETE FROM pembelian_bahan WHERE id_pembelian = 'PB101';`


========================================================================
### 10. BAHAN BAKU
========================================================================
File    : app/Controllers/BahanBaku.php
Tabel   : bahan_baku, vendor
PK      : id_bahan

#### index() — READ
```sql
SELECT bahan_baku.*, vendor.nama_perusahaan
FROM bahan_baku
LEFT JOIN vendor ON bahan_baku.id_vendor = vendor.id_vendor
```

#### create() — CREATE
```php
// Resolve vendor ID terlebih dahulu (sama polanya dengan Pembelian)

$db->table('bahan_baku')->insert([
    'id_bahan'        => $id_bahan,
    'id_vendor'       => $id_vendor,
    'nama_bahan'      => $nama_bahan,
    'stok_saat_ini'   => $stok_saat_ini,
    'status_satuan'   => $status_satuan,
    'harga_beli_unit' => $harga_beli_unit,   // sudah di-cleanNumber()
    'stok_min'        => $stok_min
]);
```
SQL:
```sql
INSERT INTO bahan_baku (id_bahan, id_vendor, nama_bahan, stok_saat_ini, status_satuan, harga_beli_unit, stok_min)
VALUES ('BB10', 1, 'Tepung Terigu', 100, 'kg', 12000, 20);
```

#### update($id) — UPDATE
```php
$db->table('bahan_baku')->where('id_bahan', $id)->update([...]);
```
SQL: `UPDATE bahan_baku SET nama_bahan = '...', stok_saat_ini = 100 WHERE id_bahan = 'BB10';`

#### delete($id) — DELETE
```php
$db->table('bahan_baku')->where('id_bahan', $id)->delete();
```
SQL: `DELETE FROM bahan_baku WHERE id_bahan = 'BB10';`


========================================================================
### 11. PEMBAYARAN
========================================================================
File    : app/Controllers/Pembayaran.php
Tabel   : pembayaran, karyawan, pelanggan
PK      : Id_pembayaran

#### index() — READ
```sql
SELECT pembayaran.*, karyawan.nama_staf, pelanggan.nama_lengkap
FROM pembayaran
LEFT JOIN karyawan  ON karyawan.nip_karyawan = pembayaran.nip_karyawan
LEFT JOIN pelanggan ON pelanggan.id_pelanggan = pembayaran.id_pelanggan
```

#### Helper Khusus: normalizeMetode() — Standarisasi Metode Pembayaran
```php
// Memastikan metode pembayaran konsisten
// Yang diperbolehkan: 'Tunai', 'Transer Bank', 'Kartu Debit', 'Qris', 'E-Wallet', 'Virtual Account (VA)'
private function normalizeMetode(?string $metode): string {
    if ($metode === 'Transfer Bank' || $metode === 'Transfer') {
        return 'Transer Bank';
    }
    $allowed = ['Tunai', 'Transer Bank', 'Kartu Debit', 'Qris', 'E-Wallet', 'Virtual Account (VA)'];
    return in_array($metode, $allowed, true) ? $metode : 'Tunai';
}
```

#### create() — CREATE (resolve karyawan & pelanggan)
```php
$db->table('pembayaran')->insert([
    'Id_pembayaran'     => $id_pembayaran,     // dari nextId()
    'Metode_pembayaran' => $metode,             // dinormalisasi
    'nip_karyawan'      => resolveKaryawan(),   // cari NIP dari nama
    'Id_pelanggan'      => resolvePelanggan()   // cari ID dari nama
]);
```
SQL:
```sql
-- Resolve karyawan:
SELECT * FROM karyawan WHERE (nip_karyawan = 'Siti' OR nama_staf LIKE '%Siti%');
-- Resolve pelanggan:
SELECT * FROM pelanggan WHERE (Id_pelanggan = 'Budi' OR nama_lengkap LIKE '%Budi%');
-- Insert:
INSERT INTO pembayaran (Id_pembayaran, Metode_pembayaran, nip_karyawan, Id_pelanggan)
VALUES (5, 'Tunai', 1, 'PL01');
```

#### update($id) — UPDATE
```php
$db->table('pembayaran')->where('Id_pembayaran', $id)->update([
    'Metode_pembayaran' => ..., 'nip_karyawan' => ..., 'Id_pelanggan' => ...
]);
```
SQL: `UPDATE pembayaran SET Metode_pembayaran = 'Tunai', ... WHERE Id_pembayaran = 5;`

#### delete($id) — DELETE
```php
$db->table('pembayaran')->where('Id_pembayaran', $id)->delete();
```
SQL: `DELETE FROM pembayaran WHERE Id_pembayaran = 5;`


========================================================================
### 12. PRODUKSI
========================================================================
File    : app/Controllers/Produksi.php
Tabel   : produksi, karyawan, bahan_baku
PK      : Id_produksi (update/delete pakai no_spk)

#### index() — READ
```sql
SELECT produksi.*, karyawan.nama_staf, bahan_baku.nama_bahan
FROM produksi
LEFT JOIN karyawan  ON karyawan.nip_karyawan = produksi.nip_karyawan
LEFT JOIN bahan_baku ON bahan_baku.id_bahan = produksi.id_bahan
```

#### create() — CREATE (pakai nextId + resolveId)
```php
$db->table('produksi')->insert([
    'Id_produksi'  => $id_produksi,                                         // dari nextId()
    'nip_karyawan' => resolveId($db, 'karyawan', 'nip_karyawan', ...),     // resolve PIC
    'id_bahan'     => resolveId($db, 'bahan_baku', 'id_bahan', ...),       // resolve bahan
    'no_spk'       => $no_spk,         // contoh: SPK/2024/001
    'tgl_produksi' => $tanggal,
    'hasil_produk' => $hasil_produk,
    'varian'       => $varian,
    'status'       => $status          // 'Proses' atau 'Selesai'
]);
```
SQL:
```sql
-- nextId:
SELECT MAX(Id_produksi) as max_id FROM produksi;

-- resolveId karyawan:
SELECT * FROM karyawan WHERE (nip_karyawan = 'Siti' OR nama_staf LIKE '%Siti%');

-- resolveId bahan:
SELECT * FROM bahan_baku WHERE (id_bahan = 'Tepung' OR nama_bahan LIKE '%Tepung%');

-- Insert:
INSERT INTO produksi (Id_produksi, nip_karyawan, id_bahan, no_spk, tgl_produksi, hasil_produk, varian, status)
VALUES (5, 1, 'BB10', 'SPK/2024/005', '2024-12-01', 'Roti Tawar', 'Original', 'Proses');
```

#### update($id) — UPDATE (pakai no_spk sebagai key)
```php
$db->table('produksi')->where('no_spk', $id)->update([...]);
```
SQL: `UPDATE produksi SET tgl_produksi = '...', status = 'Selesai' WHERE no_spk = 'SPK/2024/005';`

#### delete($id) — DELETE
```php
$db->table('produksi')->where('no_spk', $id)->delete();
```
SQL: `DELETE FROM produksi WHERE no_spk = 'SPK/2024/005';`


========================================================================
### 13. DELIVERY (Pengiriman)
========================================================================
File    : app/Controllers/Delivery.php
Tabel   : delivery (header), detail_delivery (detail)
Relasi  : karyawan, produk, provider_pengiriman
PK      : id_pengiriman (update/delete bisa pakai kode_pengiriman)

#### index() — READ (4 JOIN)
```sql
SELECT delivery.*,
       karyawan.nama_staf,
       produk.nama_produk,
       provider_pengiriman.nama_provider,
       provider_pengiriman.jenis_layanan,
       detail_delivery.biaya,
       detail_delivery.jumlah,
       detail_delivery.status
FROM delivery
LEFT JOIN karyawan            ON karyawan.nip_karyawan         = delivery.nip_karyawan
LEFT JOIN produk              ON produk.kode_produk            = delivery.kode_produk
LEFT JOIN provider_pengiriman ON provider_pengiriman.id_provider = delivery.id_provider
LEFT JOIN detail_delivery     ON detail_delivery.id_detail_kirim = delivery.id_detail_kirim
```

#### create() — CREATE (2 tabel: detail_delivery → delivery)
```php
// 1. Insert detail_delivery dulu (detail kirim)
$db->table('detail_delivery')->insert([
    'Id_detail_kirim' => $id_detail_kirim,    // dari nextId()
    'jumlah'          => $jumlah,
    'biaya'           => 'Rp 25.000',
    'berat'           => '2 kg',
    'Status'          => $status              // 'Pending', 'Proses', 'Terkirim'
]);

// 2. Insert delivery (header) dengan FK ke detail_delivery
$db->table('delivery')->insert([
    'id_pengiriman'    => $id_pengiriman,
    'nip_karyawan'     => resolveId('karyawan', ...),
    'Id_detail_kirim'  => $id_detail_kirim,
    'kode_pengiriman'  => 'DLV/2024/001',
    'penerima'         => $penerima,
    'alamat'           => $alamat,
    'Jenis_pembayaran' => $jenis_pembayaran,
    'id_provider'      => resolveId('provider_pengiriman', ...),
    'kode_produk'      => resolveId('produk', ...)
]);
```
SQL:
```sql
INSERT INTO detail_delivery (Id_detail_kirim, jumlah, biaya, berat, Status)
VALUES (1, 10, 'Rp 25.000', '2 kg', 'Pending');

INSERT INTO delivery (id_pengiriman, nip_karyawan, Id_detail_kirim, kode_pengiriman, penerima, alamat, Jenis_pembayaran, id_provider, kode_produk)
VALUES (1, 1, 1, 'DLV/2024/001', 'Toko ABC', 'Jl. Merdeka No.10', 'Tunai', 1, 'PR001');
```

#### update($id) — UPDATE (cari dulu, update 2 tabel)
```php
// Cari delivery (bisa pakai kode_pengiriman ATAU id_pengiriman)
$delivery = $db->table('delivery')
    ->where('kode_pengiriman', $id)
    ->orWhere('id_pengiriman', $id)
    ->get()->getRowArray();
```
SQL: `SELECT * FROM delivery WHERE kode_pengiriman = 'DLV/2024/001' OR id_pengiriman = 1;`

```php
// Update detail
$db->table('detail_delivery')->where('Id_detail_kirim', $delivery['Id_detail_kirim'])->update([...]);
// Update header
$db->table('delivery')->where('id_pengiriman', $delivery['id_pengiriman'])->update([...]);
```

#### delete($id) — DELETE (cari dulu, hapus keduanya)
```php
$delivery = $db->table('delivery')->where('kode_pengiriman', $id)->orWhere('id_pengiriman', $id)->get()->getRowArray();
$db->table('delivery')->where('id_pengiriman', $delivery['id_pengiriman'])->delete();
$db->table('detail_delivery')->where('Id_detail_kirim', $delivery['Id_detail_kirim'])->delete();
```
SQL:
```sql
DELETE FROM delivery WHERE id_pengiriman = 1;
DELETE FROM detail_delivery WHERE Id_detail_kirim = 1;
```


---

## 📊 CONTROLLER LAPORAN (Read-Only — Tidak ada Create/Update/Delete)

Semua controller laporan hanya punya fungsi index() yang menampilkan data
dengan filter dinamis menggunakan Query Builder.

========================================================================
### 14. LAPORAN PENJUALAN
========================================================================
File    : app/Controllers/LaporanPenjualan.php

Query Builder Utama:
```php
$builder = $db->table('detail_penjualan')
    ->select('detail_penjualan.*, penjualan.tgl_teransaksi, produk.nama_produk,
              pelanggan.nama_lengkap, karyawan.nama_staf, pembayaran.Metode_pembayaran')
    ->join('penjualan',  'penjualan.No_faktur = detail_penjualan.No_faktur', 'left')
    ->join('produk',     'produk.kode_produk = detail_penjualan.kode_produk', 'left')
    ->join('pelanggan',  'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left')
    ->join('karyawan',   'karyawan.nip_karyawan = penjualan.nip_karyawan', 'left')
    ->join('pembayaran', 'pembayaran.id_pembayaran = penjualan.id_pembayaran', 'left');
```

Filter Dinamis Berdasarkan Periode:
```php
if ($periode == 'hari') {
    $builder->where('DATE(penjualan.tgl_teransaksi)', $tanggal);
    // SQL: WHERE DATE(tgl_teransaksi) = '2024-12-01'
}
elseif ($periode == 'minggu') {
    $builder->where("DATE(penjualan.tgl_teransaksi) >=", $start_date);
    $builder->where("DATE(penjualan.tgl_teransaksi) <=", $end_date);
    // SQL: WHERE DATE(tgl_teransaksi) >= '2024-11-25' AND DATE(tgl_teransaksi) <= '2024-12-01'
}
elseif ($periode == 'bulan') {
    $builder->where("MONTH(penjualan.tgl_teransaksi)", $bulan);
    $builder->where("YEAR(penjualan.tgl_teransaksi)", $tahun);
    // SQL: WHERE MONTH(tgl_teransaksi) = 12 AND YEAR(tgl_teransaksi) = 2024
}
// Jika 'semua' → tanpa WHERE, ambil semua data
```

Raw SQL Tambahan:
```sql
-- Ambil tanggal terbaru untuk default filter
SELECT MAX(tgl_teransaksi) as max_date FROM penjualan;

-- Ambil list tahun unik untuk dropdown
SELECT DISTINCT YEAR(tgl_teransaksi) as tahun FROM penjualan ORDER BY tgl_teransaksi DESC;
```


========================================================================
### 15. LAPORAN PEMBELIAN
========================================================================
File    : app/Controllers/LaporanPembelian.php

Query Builder Utama:
```php
$builder = $db->table('detail_pembelian_bahan')
    ->select('detail_pembelian_bahan.*, pembelian_bahan.tgl_pembelian, pembelian_bahan.metode_bayar,
              bahan_baku.nama_bahan, bahan_baku.status_satuan, vendor.nama_perusahaan')
    ->join('pembelian_bahan', 'detail_pembelian_bahan.id_pembelian = pembelian_bahan.id_pembelian', 'left')
    ->join('bahan_baku',      'detail_pembelian_bahan.id_bahan = bahan_baku.id_bahan', 'left')
    ->join('vendor',          'pembelian_bahan.id_vendor = vendor.id_vendor', 'left');
```

Filter Tambahan (selain periode):
```php
// Filter berdasarkan vendor
if ($filterVendor != 'semua') {
    $builder->where('pembelian_bahan.id_vendor', $filterVendor);
    // SQL: WHERE pembelian_bahan.id_vendor = 1
}

// Filter berdasarkan metode bayar
if ($filterMetode != 'semua') {
    $builder->where('pembelian_bahan.metode_bayar', $filterMetode);
    // SQL: WHERE pembelian_bahan.metode_bayar = 'Tunai'
}
```

Raw SQL untuk Dropdown:
```sql
-- Ambil metode bayar unik untuk dropdown filter
SELECT DISTINCT metode_bayar FROM pembelian_bahan WHERE metode_bayar IS NOT NULL AND metode_bayar != '';
```


========================================================================
### 16. LAPORAN PRODUKSI
========================================================================
File    : app/Controllers/LaporanProduksi.php

Query Builder Utama:
```php
$builder = $db->table('produksi')
    ->select('produksi.*, karyawan.nama_staf as pic, bahan_baku.nama_bahan')
    ->join('karyawan',   'karyawan.nip_karyawan = produksi.nip_karyawan', 'left')
    ->join('bahan_baku', 'bahan_baku.id_bahan = produksi.id_bahan', 'left');
```

Filter Periode (sama polanya):
```php
if ($periode == 'hari') {
    $builder->where('produksi.tgl_produksi', $tanggal);
} elseif ($periode == 'minggu') {
    $builder->where("produksi.tgl_produksi >=", $start_date);
    $builder->where("produksi.tgl_produksi <=", $end_date);
} elseif ($periode == 'bulan') {
    $builder->where("MONTH(produksi.tgl_produksi)", $bulan);
    $builder->where("YEAR(produksi.tgl_produksi)", $tahun);
}
```

Raw SQL:
```sql
SELECT MAX(tgl_produksi) as max_date FROM produksi;
SELECT DISTINCT YEAR(tgl_produksi) as tahun FROM produksi ORDER BY tgl_produksi DESC;
```


========================================================================
### 17. LAPORAN PENGIRIMAN
========================================================================
File    : app/Controllers/LaporanPengiriman.php

Query Builder Utama:
```php
$builder = $db->table('delivery')
    ->select('delivery.*, karyawan.nama_staf, produk.nama_produk,
              provider_pengiriman.nama_provider, provider_pengiriman.jenis_layanan,
              detail_delivery.biaya, detail_delivery.jumlah, detail_delivery.berat,
              detail_delivery.status as status_kirim')
    ->join('karyawan',            'karyawan.nip_karyawan = delivery.nip_karyawan', 'left')
    ->join('produk',              'produk.kode_produk = delivery.kode_produk', 'left')
    ->join('provider_pengiriman', 'provider_pengiriman.id_provider = delivery.id_provider', 'left')
    ->join('detail_delivery',     'detail_delivery.id_detail_kirim = delivery.id_detail_kirim', 'left');
```

Filter:
```php
// Filter status pengiriman
if ($filterStatus != 'semua') {
    $builder->where('detail_delivery.status', $filterStatus);
    // SQL: WHERE detail_delivery.status = 'Terkirim'
}

// Filter provider
if ($filterProvider != 'semua') {
    $builder->where('delivery.id_provider', $filterProvider);
    // SQL: WHERE delivery.id_provider = 1
}

// Filter jenis pembayaran
if ($filterBayar != 'semua') {
    $builder->where('delivery.Jenis_pembayaran', $filterBayar);
    // SQL: WHERE delivery.Jenis_pembayaran = 'Tunai'
}
```

Raw SQL:
```sql
SELECT DISTINCT Jenis_pembayaran FROM delivery WHERE Jenis_pembayaran IS NOT NULL AND Jenis_pembayaran != '';
```


========================================================================
### 18. LAPORAN ABSEN KARYAWAN
========================================================================
File    : app/Controllers/LaporanAbsen.php

Query Builder Utama:
```php
$builder = $db->table('karyawan')
    ->select('karyawan.*, departemen.nama_dept')
    ->join('departemen', 'karyawan.id_dept = departemen.id_dept', 'left');
```

Filter:
```php
// Filter status staf
if ($filterStatus != 'semua') {
    $builder->where('karyawan.status_staf', $filterStatus);
    // SQL: WHERE karyawan.status_staf = 'Aktif'
}

// Filter departemen
if ($filterDept != 'semua') {
    $builder->where('karyawan.id_dept', $filterDept);
    // SQL: WHERE karyawan.id_dept = 1
}
```

Statistik (query tambahan untuk hitung semua karyawan):
```php
$totalBuilder = $db->table('karyawan');
$allKaryawan = $totalBuilder->get()->getResult();
// SQL: SELECT * FROM karyawan;
// Lalu dihitung manual di PHP: jumlah Aktif, Cuti, Keluar
```


---

## 📐 CHEATSHEET QUERY BUILDER CI4 → SQL

| Query Builder CI4                           | SQL Equivalen                                      |
|---------------------------------------------|----------------------------------------------------|
| $db->query("SELECT ...")                    | Raw SQL langsung                                   |
| $db->table('produk')                        | FROM produk                                        |
| ->select('nama, harga')                     | SELECT nama, harga                                 |
| ->where('id', 1)                            | WHERE id = 1                                       |
| ->where('harga >=', 1000)                   | WHERE harga >= 1000                                |
| ->orWhere('status', 'aktif')                | OR status = 'aktif'                                |
| ->like('nama', 'roti')                      | WHERE nama LIKE '%roti%'                           |
| ->orLike('nama', 'roti')                    | OR nama LIKE '%roti%'                              |
| ->join('tabel2', 'ON ...', 'left')          | LEFT JOIN tabel2 ON ...                            |
| ->groupStart() ... ->groupEnd()             | WHERE ( ... ) — pengelompokan kondisi              |
| ->insert([...])                             | INSERT INTO ... VALUES (...)                       |
| ->where('id', 1)->update([...])             | UPDATE ... SET ... WHERE id = 1                    |
| ->where('id', 1)->delete()                  | DELETE FROM ... WHERE id = 1                       |
| ->selectMax('id', 'max_id')                 | SELECT MAX(id) as max_id                           |
| ->orderBy('id', 'DESC')                     | ORDER BY id DESC                                   |
| ->get()                                     | Eksekusi query                                     |
| ->getResult()                               | Ambil semua baris → array of objects               |
| ->getRowArray()                             | Ambil 1 baris → associative array                  |
| ->getRow()                                  | Ambil 1 baris → object                             |
| $db->insertID()                             | LAST_INSERT_ID() — ambil ID terakhir yang di-insert|


---

## 🔗 DIAGRAM RELASI ANTAR TABEL

```
    ┌─────────────┐        ┌──────────────────┐
    │ departemen   │        │ vendor            │
    │  - id_dept   │        │  - id_vendor      │
    │  - nama_dept │        │  - nama_perusahaan│
    └──────┬───────┘        └───────┬───────────┘
           │ 1:N                    │ 1:N
    ┌──────▼───────┐        ┌───────▼───────────┐       ┌──────────────────────┐
    │ karyawan      │        │ bahan_baku         │       │ pembelian_bahan       │
    │  - nip_karyawan│       │  - id_bahan        │       │  - id_pembelian       │
    │  - id_dept    │        │  - id_vendor       │       │  - id_vendor          │
    │  - nama_staf  │        │  - nama_bahan      │       │  - tgl_pembelian      │
    └──────┬───────┘        └───────┬───────────┘       └───────┬──────────────┘
           │                        │                           │
           │              ┌─────────▼──────────┐       ┌───────▼──────────────┐
           │              │ produksi            │       │ detail_pembelian_bahan│
           │              │  - nip_karyawan     │       │  - id_pembelian      │
           │              │  - id_bahan         │       │  - id_bahan          │
           │              └────────────────────┘       └──────────────────────┘
           │
    ┌──────▼────────────┐
    │ penjualan          │        ┌────────────────────┐
    │  - No_faktur       │        │ pelanggan           │
    │  - id_pelanggan ───┼───────►│  - Id_pelanggan     │
    │  - nip_karyawan    │        │  - nama_lengkap     │
    │  - id_pembayaran ──┼──┐     └────────────────────┘
    └──────┬─────────────┘  │
           │ 1:N            │     ┌────────────────────┐
    ┌──────▼─────────────┐  └────►│ pembayaran          │
    │ detail_penjualan   │        │  - Id_pembayaran    │
    │  - No_faktur       │        │  - Metode_pembayaran│
    │  - kode_produk ────┼──┐     │  - nip_karyawan     │
    └────────────────────┘  │     │  - id_pelanggan     │
                            │     └────────────────────┘
    ┌───────────────────┐   │
    │ produk            │◄──┘     ┌────────────────────┐
    │  - kode_produk    │         │ daftar_harga        │
    │  - Id_detail_harga├────────►│  - Id_detail_harga  │
    │  - nama_produk    │         │  - kode_produk      │
    └──────┬────────────┘         │  - Harga_asli       │
           │                      │  - Harga_diskon     │
           │                      └────────────────────┘
    ┌──────▼────────────┐
    │ delivery           │        ┌────────────────────┐
    │  - id_pengiriman   │        │ provider_pengiriman │
    │  - nip_karyawan    │        │  - id_provider      │
    │  - kode_produk     │        │  - nama_provider    │
    │  - id_provider ────┼───────►│  - jenis_layanan    │
    │  - Id_detail_kirim─┼──┐     └────────────────────┘
    └────────────────────┘  │
                            │     ┌────────────────────┐
                            └────►│ detail_delivery     │
                                  │  - Id_detail_kirim  │
                                  │  - jumlah           │
                                  │  - biaya            │
                                  │  - status           │
                                  └────────────────────┘
```

KETERANGAN SIMBOL:
- ──► = Foreign Key (relasi antar tabel)
- 1:N = Satu ke banyak (one-to-many)
- PK  = Primary Key (kunci utama tabel)
- FK  = Foreign Key (kunci penghubung ke tabel lain)

---

## 📝 CATATAN PENTING UNTUK DIPELAJARI

1. SEMUA controller menggunakan LEFT JOIN (bukan INNER JOIN).
   Artinya: data tetap tampil meskipun relasi FK-nya kosong/NULL.

2. URUTAN HAPUS penting pada tabel berelasi:
   - Hapus CHILD dulu (detail_penjualan, detail_delivery, daftar_harga)
   - Baru hapus PARENT (penjualan, delivery, produk)
   - Jika terbalik → error foreign key constraint

3. PATTERN HEADER-DETAIL dipakai di 2 transaksi:
   - Penjualan  : penjualan (header) + detail_penjualan (item)
   - Delivery   : delivery (header) + detail_delivery (detail kirim)

4. SEMUA response CRUD (create/update/delete) menggunakan format JSON:
   { "success": true, "message": "..." }

5. TIDAK ADA Model CI4 yang dipakai (folder Models kosong).
   Semua query langsung ditulis di Controller.
