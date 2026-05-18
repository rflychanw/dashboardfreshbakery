<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Overview</h1>
        <div class="page-breadcrumb">Selamat datang di Sistem Manajemen FreshBakery</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Laporan_Dashboard')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Transaksi Baru
        </button>
    </div>
</div>

<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total Penjualan</div>
            <div class="stat-value">Rp 12.5M</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> +14.5% dari bulan lalu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-truck-fast"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Delivery Aktif</div>
            <div class="stat-value">48</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> +5% dari bulan lalu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Stok Rendah</div>
            <div class="stat-value">12</div>
            <div class="stat-trend down"><i class="fa-solid fa-arrow-down"></i> Perlu restock segera</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-industry"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Produksi Berjalan</div>
            <div class="stat-value">5 Batch</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> Sesuai target</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Panel Kiri: Aktivitas Terbaru -->
    <div class="dashboard-panel">
        <div class="panel-header">
            <h2 class="panel-title">Transaksi Penjualan Terbaru</h2>
            <button class="panel-action">Lihat Semua</button>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#TRX-0992</td>
                    <td>Toko Roti Makmur</td>
                    <td>15 Mei 2026</td>
                    <td><span class="status-badge status-success">Selesai</span></td>
                    <td>Rp 2.450.000</td>
                </tr>
                <tr>
                    <td>#TRX-0991</td>
                    <td>Cafe Senja</td>
                    <td>15 Mei 2026</td>
                    <td><span class="status-badge status-pending">Dikirim</span></td>
                    <td>Rp 1.120.000</td>
                </tr>
                <tr>
                    <td>#TRX-0990</td>
                    <td>Kantin Sehat</td>
                    <td>14 Mei 2026</td>
                    <td><span class="status-badge status-success">Selesai</span></td>
                    <td>Rp 850.000</td>
                </tr>
                <tr>
                    <td>#TRX-0989</td>
                    <td>Pelanggan Retail</td>
                    <td>14 Mei 2026</td>
                    <td><span class="status-badge status-warning">Pending</span></td>
                    <td>Rp 320.000</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Panel Kanan: Status Produksi -->
    <div class="dashboard-panel">
        <div class="panel-header">
            <h2 class="panel-title">Status Produksi</h2>
        </div>
        <div class="production-list">
            <div class="production-item">
                <div class="prod-icon"><i class="fa-solid fa-bread-slice"></i></div>
                <div class="prod-details">
                    <h4>Roti Tawar Spesial</h4>
                    <p>Batch #B-102</p>
                </div>
                <div class="prod-status prod-running">Proses</div>
            </div>
            <div class="production-item">
                <div class="prod-icon"><i class="fa-solid fa-cookie"></i></div>
                <div class="prod-details">
                    <h4>Cookies Coklat</h4>
                    <p>Batch #C-045</p>
                </div>
                <div class="prod-status prod-running">Proses</div>
            </div>
            <div class="production-item">
                <div class="prod-icon"><i class="fa-solid fa-cake-candles"></i></div>
                <div class="prod-details">
                    <h4>Kue Ulang Tahun</h4>
                    <p>Custom Order</p>
                </div>
                <div class="prod-status prod-waiting">Menunggu</div>
            </div>
            <div class="production-item">
                <div class="prod-icon"><i class="fa-solid fa-stroopwafel"></i></div>
                <div class="prod-details">
                    <h4>Croissant Butter</h4>
                    <p>Batch #CR-088</p>
                </div>
                <div class="prod-status prod-done">Selesai</div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
