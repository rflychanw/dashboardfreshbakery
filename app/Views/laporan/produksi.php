<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Produksi Roti</h1>
        <div class="page-breadcrumb">Laporan / Produksi</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Laporan_Produksi')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
    </div>
</div>

<!-- Ringkasan Statistik -->
<div class="stat-cards" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-industry"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total SPK Produksi</div>
            <div class="stat-value"><?= $stats['total'] ?> Batch</div>
            <div class="stat-trend up" style="color: #6c757d;">Periode terpilih</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Produksi Selesai</div>
            <div class="stat-value"><?= $stats['selesai'] ?> Batch</div>
            <div class="stat-trend up" style="color: #198754;"><i class="fa-solid fa-check"></i> Sudah diselesaikan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-spinner"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Dalam Proses</div>
            <div class="stat-value"><?= $stats['proses'] ?> Batch</div>
            <div class="stat-trend up" style="color: #0d6efd;"><i class="fa-solid fa-rotate"></i> Sedang berjalan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink">
            <i class="fa-solid fa-bread-slice"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Varian Terpopuler</div>
            <div class="stat-value" style="font-size: 15px; font-weight: bold; margin-top: 6px; word-break: break-all;"><?= $stats['terpopuler'] ?></div>
            <div class="stat-trend up" style="color: #d63384;">Sering diproduksi</div>
        </div>
    </div>
</div>

<!-- Card Filter Laporan -->
<div class="dashboard-panel" style="margin-bottom: 24px; padding: 20px;">
    <div class="panel-header" style="margin-bottom: 16px;">
        <h2 class="panel-title"><i class="fa-solid fa-filter"></i> Filter Parameter Laporan</h2>
    </div>
    
    <form method="GET" action="" id="filter-form" style="display: flex; gap: 20px; align-items: flex-end; flex-wrap: wrap;">
        <!-- Pilihan Periode -->
        <div style="flex: 1; min-width: 200px; display: flex; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Pilih Periode Laporan</label>
            <select name="periode" id="periode-select" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <option value="hari" <?= $periode == 'hari' ? 'selected' : '' ?>>Harian (Per Hari)</option>
                <option value="minggu" <?= $periode == 'minggu' ? 'selected' : '' ?>>Mingguan (Per Minggu / Rentang)</option>
                <option value="bulan" <?= $periode == 'bulan' ? 'selected' : '' ?>>Bulanan (Per Bulan)</option>
            </select>
        </div>

        <!-- Input Harian -->
        <div id="wrapper-hari" style="flex: 1; min-width: 200px; display: <?= $periode == 'hari' ? 'flex' : 'none' ?>; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Pilih Tanggal</label>
            <input type="date" name="tanggal" value="<?= $tanggal ?>" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
        </div>

        <!-- Input Rentang / Mingguan -->
        <div id="wrapper-minggu-start" style="flex: 1; min-width: 180px; display: <?= $periode == 'minggu' ? 'flex' : 'none' ?>; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Tanggal Mulai</label>
            <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
        </div>
        <div id="wrapper-minggu-end" style="flex: 1; min-width: 180px; display: <?= $periode == 'minggu' ? 'flex' : 'none' ?>; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Tanggal Selesai</label>
            <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
        </div>

        <!-- Input Bulanan -->
        <div id="wrapper-bulan" style="flex: 1; min-width: 150px; display: <?= $periode == 'bulan' ? 'flex' : 'none' ?>; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Pilih Bulan</label>
            <select name="bulan" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <?php
                $namaBulan = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                foreach ($namaBulan as $num => $nama) {
                    $selected = ($bulan == $num) ? 'selected' : '';
                    echo "<option value=\"$num\" $selected>$nama</option>";
                }
                ?>
            </select>
        </div>
        <div id="wrapper-tahun" style="flex: 1; min-width: 120px; display: <?= $periode == 'bulan' ? 'flex' : 'none' ?>; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Pilih Tahun</label>
            <select name="tahun" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <?php foreach ($listTahun as $t): ?>
                    <option value="<?= $t ?>" <?= $tahun == $t ? 'selected' : '' ?>><?= $t ?></option>
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
            <i class="fa-solid fa-list-check"></i> Detail Hasil Produksi Roti
            <span style="font-size: 13px; font-weight: normal; color: var(--text-secondary); margin-left: 8px;">
                (
                <?php 
                if ($periode == 'hari') {
                    echo "Harian: " . date('d F Y', strtotime($tanggal));
                } elseif ($periode == 'minggu') {
                    echo "Mingguan: " . date('d F Y', strtotime($start_date)) . " s/d " . date('d F Y', strtotime($end_date));
                } elseif ($periode == 'bulan') {
                    echo "Bulanan: " . $namaBulan[$bulan] . " " . $tahun;
                }
                ?>
                )
            </span>
        </h2>
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>No. SPK</th>
                    <th>Tanggal Produksi</th>
                    <th>Nama Bahan Utama</th>
                    <th>Nama Roti (Hasil)</th>
                    <th>Varian</th>
                    <th>PIC Produksi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan)): ?>
                    <?php foreach ($laporan as $p): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--accent-color);"><?= $p->no_spk ?></td>
                            <td><?= date('d F Y', strtotime($p->tgl_produksi)) ?></td>
                            <td><?= $p->nama_bahan ?? '-' ?></td>
                            <td style="font-weight: 500;"><?= $p->hasil_produk ?></td>
                            <td><?= $p->varian ?></td>
                            <td><?= $p->pic ?? '-' ?></td>
                            <td>
                                <?php 
                                    $statusClass = '';
                                    $statusLower = strtolower(trim($p->status));
                                    if ($statusLower === 'selesai') {
                                        $statusClass = 'status-success';
                                    } elseif ($statusLower === 'proses') {
                                        $statusClass = 'status-warning';
                                    } else {
                                        $statusClass = 'status-pending';
                                    }
                                ?>
                                <span class="status-badge <?= $statusClass ?>"><?= $p->status ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-secondary); padding: 32px 0;">
                            <i class="fa-solid fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            Tidak ada data produksi roti ditemukan pada periode ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // JS Logic untuk merubah visibilitas filter berdasarkan pilihan periode secara real-time
    document.addEventListener('DOMContentLoaded', function() {
        const periodeSelect = document.getElementById('periode-select');
        const wrapperHari = document.getElementById('wrapper-hari');
        const wrapperMingguStart = document.getElementById('wrapper-minggu-start');
        const wrapperMingguEnd = document.getElementById('wrapper-minggu-end');
        const wrapperBulan = document.getElementById('wrapper-bulan');
        const wrapperTahun = document.getElementById('wrapper-tahun');

        function toggleFilters() {
            const val = periodeSelect.value;
            
            // Sembunyikan semua dulu
            wrapperHari.style.display = 'none';
            wrapperMingguStart.style.display = 'none';
            wrapperMingguEnd.style.display = 'none';
            wrapperBulan.style.display = 'none';
            wrapperTahun.style.display = 'none';

            // Tampilkan yang sesuai
            if (val === 'hari') {
                wrapperHari.style.display = 'flex';
            } else if (val === 'minggu') {
                wrapperMingguStart.style.display = 'flex';
                wrapperMingguEnd.style.display = 'flex';
            } else if (val === 'bulan') {
                wrapperBulan.style.display = 'flex';
                wrapperTahun.style.display = 'flex';
            }
        }

        // Jalankan saat pertama load dan saat pilihan berubah
        periodeSelect.addEventListener('change', toggleFilters);
        toggleFilters();
    });
</script>
<?= $this->endSection() ?>
