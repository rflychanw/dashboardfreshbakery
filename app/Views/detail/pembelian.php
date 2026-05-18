<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Item Pembelian</h1>
        <div class="page-breadcrumb">Detail / Pembelian</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Detail_Pembelian_Bahan')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Data
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Rincian Barang per Purchase Order</h2>
    </div>
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>No. PO</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                    <th>Vendor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PO-2605-001</td>
                    <td>Tepung Terigu</td>
                    <td>100 Kg</td>
                    <td>Rp 12.000</td>
                    <td>Rp 1.200.000</td>
                    <td>PT Tepung Jaya</td>
                </tr>
                <tr>
                    <td>PO-2605-001</td>
                    <td>Gula Pasir</td>
                    <td>50 Kg</td>
                    <td>Rp 15.000</td>
                    <td>Rp 750.000</td>
                    <td>PT Tepung Jaya</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

