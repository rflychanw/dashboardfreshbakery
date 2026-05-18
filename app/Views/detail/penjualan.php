<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Item Penjualan</h1>
        <div class="page-breadcrumb">Detail / Penjualan</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Detail_Penjualan')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Data
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Rincian Produk Terjual per Invoice</h2>
    </div>
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga Jual</th>
                    <th>Diskon</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>INV-2605-0992</td>
                    <td>Roti Tawar Spesial</td>
                    <td>10</td>
                    <td>Rp 15.000</td>
                    <td>Rp 0</td>
                    <td>Rp 150.000</td>
                </tr>
                <tr>
                    <td>INV-2605-0992</td>
                    <td>Cookies Coklat</td>
                    <td>5</td>
                    <td>Rp 25.000</td>
                    <td>Rp 5.000</td>
                    <td>Rp 120.000</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

