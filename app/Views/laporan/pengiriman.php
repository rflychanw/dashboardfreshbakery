<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Pengiriman (Delivery)</h1>
        <div class="page-breadcrumb">Laporan / Pengiriman</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Laporan_Pengiriman')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
    </div>
</div>

<!-- Ringkasan Statistik Pengiriman -->
<div class="stat-cards" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-truck-fast"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total Pengiriman</div>
            <div class="stat-value"><?= $stats['total_pengiriman'] ?> Kiriman</div>
            <div class="stat-trend up" style="color: #6c757d;">Keseluruhan paket</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Telah Terkirim</div>
            <div class="stat-value" style="color: #198754;"><?= $stats['total_terkirim'] ?> Paket</div>
            <div class="stat-trend up" style="color: #198754; font-weight: 500;"><i class="fa-solid fa-check-double"></i> Sukses diterima</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-boxes-stacked"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total Barang Dikirim</div>
            <div class="stat-value"><?= number_format($stats['total_qty'], 0, ',', '.') ?> Roti</div>
            <div class="stat-trend up" style="color: #712cf9;"><i class="fa-solid fa-box"></i> Kuantitas roti</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink">
            <i class="fa-solid fa-coins"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total Ongkos Kirim</div>
            <div class="stat-value" style="font-size: 18px; color: #d63384;">Rp <?= number_format($stats['total_ongkir'], 0, ',', '.') ?></div>
            <div class="stat-trend up" style="color: #d63384; font-weight: bold;"><i class="fa-solid fa-scale-balanced"></i> Akumulasi ongkir</div>
        </div>
    </div>
</div>

<!-- Card Filter Laporan -->
<div class="dashboard-panel" style="margin-bottom: 24px; padding: 20px;">
    <div class="panel-header" style="margin-bottom: 16px;">
        <h2 class="panel-title"><i class="fa-solid fa-filter"></i> Filter Parameter Pengiriman</h2>
    </div>
    
    <form method="GET" action="" id="filter-form" style="display: flex; gap: 20px; align-items: flex-end; flex-wrap: wrap;">
        <!-- Filter Status -->
        <div style="flex: 1; min-width: 180px; display: flex; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Status Pengiriman</label>
            <select name="status" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <option value="semua" <?= $filterStatus == 'semua' ? 'selected' : '' ?>>Semua Status</option>
                <option value="Pending" <?= $filterStatus == 'Pending' ? 'selected' : '' ?>>Pending (Menunggu)</option>
                <option value="Proses" <?= $filterStatus == 'Proses' ? 'selected' : '' ?>>Proses (Diperjalanan)</option>
                <option value="Terkirim" <?= $filterStatus == 'Terkirim' ? 'selected' : '' ?>>Terkirim (Selesai)</option>
            </select>
        </div>

        <!-- Filter Provider -->
        <div style="flex: 1; min-width: 220px; display: flex; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Provider Pengiriman</label>
            <select name="provider" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <option value="semua" <?= $filterProvider == 'semua' ? 'selected' : '' ?>>Semua Provider</option>
                <?php foreach ($listProvider as $prov): ?>
                    <option value="<?= $prov->id_provider ?>" <?= $filterProvider == $prov->id_provider ? 'selected' : '' ?>>
                        <?= $prov->nama_provider ?> (<?= $prov->jenis_layanan ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Filter Jenis Pembayaran -->
        <div style="flex: 1; min-width: 180px; display: flex; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Metode Pembayaran</label>
            <select name="jenis_pembayaran" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <option value="semua" <?= $filterBayar == 'semua' ? 'selected' : '' ?>>Semua Metode</option>
                <?php foreach ($listBayar as $bayar): ?>
                    <option value="<?= $bayar ?>" <?= $filterBayar == $bayar ? 'selected' : '' ?>><?= $bayar ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tombol Terapkan -->
        <div style="min-width: 120px;">
            <button type="submit" class="btn-primary" style="width: 100%; padding: 10px 16px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fa-solid fa-magnifying-glass"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Panel Tabel Laporan -->
<div class="dashboard-panel">
    <div class="panel-header" style="margin-bottom: 20px;">
        <h2 class="panel-title">
            <i class="fa-solid fa-list-check"></i> Detail Riwayat Transaksi Pengiriman (Delivery)
            <span style="font-size: 13px; font-weight: normal; color: var(--text-secondary); margin-left: 8px;">
                (
                <?php 
                $statusLabels = ['semua' => 'Semua Data', 'Pending' => 'Status Pending', 'Proses' => 'Status Proses', 'Terkirim' => 'Status Terkirim'];
                echo $statusLabels[$filterStatus];
                ?>
                )
            </span>
        </h2>
    </div>

    <div class="table-responsive">
        <table class="modern-table" style="min-width: 1200px;">
            <thead>
                <tr>
                    <th>Kode Pengiriman</th>
                    <th>Penerima</th>
                    <th>Alamat Pengiriman</th>
                    <th>Nama Produk</th>
                    <th style="text-align: center;">Jumlah (Qty)</th>
                    <th>Kurir (Provider)</th>
                    <th style="text-align: right;">Biaya Ongkir</th>
                    <th>Karyawan (Sales/Kasir)</th>
                    <th>Metode Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan)): ?>
                    <?php foreach ($laporan as $p): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--accent-color);"><?= $p->kode_pengiriman ?></td>
                            <td style="font-weight: 500; color: var(--text-primary);"><?= $p->penerima ?></td>
                            <td style="font-size: 12px; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= $p->alamat ?>"><?= $p->alamat ?></td>
                            <td><?= $p->nama_produk ?? '-' ?></td>
                            <td style="text-align: center; font-weight: 500;"><?= $p->jumlah ?? '0' ?> Pcs</td>
                            <td style="font-weight: 500;">
                                <?= $p->nama_provider ?? '-' ?> 
                                <span style="font-size: 11px; color: var(--text-secondary);"> (<?= $p->jenis_layanan ?? '-' ?>)</span>
                            </td>
                            <td style="text-align: right; font-weight: 500;"><?= $p->biaya ?? 'Rp 0' ?></td>
                            <td><?= $p->nama_staf ?? '-' ?></td>
                            <td>
                                <span class="status-badge" style="background: #e9ecef; color: #495057; font-weight: 500;">
                                    <?= $p->Jenis_pembayaran ?? '-' ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $badgeClass = 'status-pending';
                                if ($p->status_kirim == 'Terkirim') {
                                    $badgeClass = 'status-success';
                                } elseif ($p->status_kirim == 'Proses') {
                                    $badgeClass = 'status-warning';
                                }
                                ?>
                                <span class="status-badge <?= $badgeClass ?>">
                                    <?= $p->status_kirim ?? 'Pending' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center; color: var(--text-secondary); padding: 32px 0;">
                            <i class="fa-solid fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            Tidak ada data transaksi pengiriman ditemukan pada periode/filter ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
