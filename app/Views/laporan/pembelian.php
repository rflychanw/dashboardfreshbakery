<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Pembelian Bahan Baku</h1>
        <div class="page-breadcrumb">Laporan / Pembelian Bahan</div>
    </div>
    <div class="header-actions-group" style="display: flex; gap: 10px;">
        <button class="btn-secondary" onclick="exportToExcel('Laporan_Pembelian_Bahan')" style="background: #198754; color: white; border-color: #198754; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-secondary" onclick="exportToPDF('Laporan Pembelian Bahan')" style="background: #dc3545; color: white; border-color: #dc3545; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </button>
    </div>
</div>

<!-- Ringkasan Statistik Pembelian -->
<div class="stat-cards" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total Transaksi</div>
            <div class="stat-value"><?= $stats['total_transaksi'] ?> Nota</div>
            <div class="stat-trend up" style="color: #6c757d;">Faktur pembelian bahan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-seedling"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total Bahan Dibeli</div>
            <div class="stat-value"><?= number_format($stats['total_qty'], 0, ',', '.') ?> Item</div>
            <div class="stat-trend up" style="color: #712cf9;"><i class="fa-solid fa-scale-balanced"></i> Kuantitas total bahan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fa-solid fa-star"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Bahan Terbanyak Dibeli</div>
            <div class="stat-value" style="font-size: 14px; font-weight: 600; color: #ffc107; margin-top: 4px;"><?= $stats['bahan_terpopuler'] ?></div>
            <div class="stat-trend up" style="color: #ffc107; font-weight: 500;"><i class="fa-solid fa-circle-check"></i> Volume pengadaan tertinggi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink">
            <i class="fa-solid fa-wallet"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total Pengeluaran</div>
            <div class="stat-value" style="font-size: 18px; color: #d63384;">Rp <?= number_format($stats['total_pengeluaran'], 0, ',', '.') ?></div>
            <div class="stat-trend up" style="color: #d63384; font-weight: bold;"><i class="fa-solid fa-chart-line"></i> Anggaran belanja bahan</div>
        </div>
    </div>
</div>

<!-- Card Filter Laporan -->
<div class="dashboard-panel" style="margin-bottom: 24px; padding: 20px;">
    <div class="panel-header" style="margin-bottom: 16px;">
        <h2 class="panel-title"><i class="fa-solid fa-filter"></i> Filter Parameter Pembelian</h2>
    </div>
    
    <form method="GET" action="" id="filter-form" style="display: flex; gap: 20px; align-items: flex-end; flex-wrap: wrap;">
        <!-- Pilihan Periode -->
        <div style="flex: 1; min-width: 180px; display: flex; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Pilih Periode</label>
            <select name="periode" id="periode-select" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <option value="semua" <?= $periode == 'semua' ? 'selected' : '' ?>>Semua Data</option>
                <option value="hari" <?= $periode == 'hari' ? 'selected' : '' ?>>Harian (Per Hari)</option>
                <option value="minggu" <?= $periode == 'minggu' ? 'selected' : '' ?>>Mingguan (Rentang)</option>
                <option value="bulan" <?= $periode == 'bulan' ? 'selected' : '' ?>>Bulanan (Per Bulan)</option>
            </select>
        </div>

        <!-- Filter Tanggal Harian -->
        <div id="filter-hari-box" style="flex: 1; min-width: 180px; display: <?= $periode == 'hari' ? 'flex' : 'none' ?>; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Pilih Tanggal</label>
            <input type="date" name="tanggal" value="<?= $tanggal ?>" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
        </div>

        <!-- Filter Tanggal Mingguan (Rentang) -->
        <div id="filter-minggu-box" style="flex: 2; min-width: 320px; display: <?= $periode == 'minggu' ? 'flex' : 'none' ?>; gap: 12px;">
            <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Dari Tanggal</label>
                <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
            </div>
            <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Sampai Tanggal</label>
                <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
            </div>
        </div>

        <!-- Filter Bulanan -->
        <div id="filter-bulan-box" style="flex: 2; min-width: 300px; display: <?= $periode == 'bulan' ? 'flex' : 'none' ?>; gap: 12px;">
            <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Bulan</label>
                <select name="bulan" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                    <?php
                    $namaBulan = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktobe', '11' => 'November', '12' => 'Desember'
                    ];
                    foreach ($namaBulan as $key => $value) {
                        echo "<option value='$key' " . ($bulan == $key ? 'selected' : '') . ">$value</option>";
                    }
                    ?>
                </select>
            </div>
            <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Tahun</label>
                <select name="tahun" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                    <?php
                    $tahunSekarang = date('Y');
                    for ($t = $tahunSekarang; $t >= $tahunSekarang - 5; $t--) {
                        echo "<option value='$t' " . ($tahun == $t ? 'selected' : '') . ">$t</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- Filter Vendor -->
        <div style="flex: 1; min-width: 200px; display: flex; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Vendor</label>
            <select name="vendor" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <option value="semua" <?= $filterVendor == 'semua' ? 'selected' : '' ?>>Semua Vendor</option>
                <?php foreach ($listVendor as $v): ?>
                    <option value="<?= $v->id_vendor ?>" <?= $filterVendor == $v->id_vendor ? 'selected' : '' ?>><?= $v->nama_perusahaan ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Filter Metode Bayar -->
        <div style="flex: 1; min-width: 180px; display: flex; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Metode Bayar</label>
            <select name="metode_bayar" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <option value="semua" <?= $filterMetode == 'semua' ? 'selected' : '' ?>>Semua Metode</option>
                <?php foreach ($listMetode as $m): ?>
                    <option value="<?= $m ?>" <?= $filterMetode == $m ? 'selected' : '' ?>><?= $m ?></option>
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
            <i class="fa-solid fa-clipboard-list"></i> Detail Riwayat Pembelian Bahan Baku
            <span style="font-size: 13px; font-weight: normal; color: var(--text-secondary); margin-left: 8px;">
                (
                <?php 
                if ($periode == 'hari') {
                    echo "Harian: " . date('d F Y', strtotime($tanggal));
                } elseif ($periode == 'minggu') {
                    echo "Rentang: " . date('d F Y', strtotime($start_date)) . " s/d " . date('d F Y', strtotime($end_date));
                } elseif ($periode == 'bulan') {
                    echo "Bulanan: " . $namaBulan[$bulan] . " " . $tahun;
                } else {
                    echo "Semua Data Pembelian";
                }
                ?>
                )
            </span>
        </h2>
    </div>

    <div class="table-responsive">
        <table class="modern-table" style="min-width: 1200px;">
            <thead>
                <tr>
                    <th>ID Pembelian</th>
                    <th>Tanggal Pembelian</th>
                    <th>Nama Bahan Baku</th>
                    <th>Nama Vendor (Supplier)</th>
                    <th style="text-align: center;">Jumlah (Qty)</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: right;">Sub Total</th>
                    <th>Metode Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan)): ?>
                    <?php foreach ($laporan as $p): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--accent-color);"><?= $p->id_pembelian ?></td>
                            <td><?= $p->tgl_pembelian ?></td>
                            <td style="font-weight: 600; color: var(--text-primary);"><?= $p->nama_bahan ?? '-' ?></td>
                            <td><?= $p->nama_perusahaan ?? '-' ?></td>
                            <td style="text-align: center; font-weight: 500;">
                                <?= number_format($p->jumlah, 0, ',', '.') ?> 
                                <span style="font-size: 11px; color: var(--text-secondary);"><?= $p->status_satuan ?? '' ?></span>
                            </td>
                            <td style="text-align: right;">Rp <?= number_format($p->harga_satuan, 0, ',', '.') ?></td>
                            <td style="text-align: right; font-weight: 600; color: #198754;">Rp <?= number_format($p->subtotal, 0, ',', '.') ?></td>
                            <td>
                                <span class="status-badge" style="background: #e9ecef; color: #495057; font-weight: 500;">
                                    <?= $p->metode_bayar ?? '-' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-secondary); padding: 32px 0;">
                            <i class="fa-solid fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            Tidak ada data transaksi pembelian bahan ditemukan pada periode/filter ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Dinamis tampil/sembunyi filter input berdasarkan pilihan periode
    document.getElementById('periode-select').addEventListener('change', function() {
        const val = this.value;
        document.getElementById('filter-hari-box').style.display = (val === 'hari') ? 'flex' : 'none';
        document.getElementById('filter-minggu-box').style.display = (val === 'minggu') ? 'flex' : 'none';
        document.getElementById('filter-bulan-box').style.display = (val === 'bulan') ? 'flex' : 'none';
    });
</script>
<?= $this->endSection() ?>
