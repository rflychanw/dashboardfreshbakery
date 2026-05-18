<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Pengiriman (Delivery)</h1>
        <div class="page-breadcrumb">Detail / Delivery</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Detail_Delivery')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Data
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Tracking & Status Pengiriman Rinci</h2>
    </div>
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>No. Resi/DO</th>
                    <th>Pelanggan</th>
                    <th>Waktu Berangkat</th>
                    <th>Waktu Sampai</th>
                    <th>Penerima</th>
                    <th>Status Akhir</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>DO-2605-001</td>
                    <td>Toko Roti Makmur</td>
                    <td>08:00</td>
                    <td>09:30</td>
                    <td>Pak Slamet</td>
                    <td><span class="status-badge status-success">Diterima</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

