<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Absen & Status Karyawan</h1>
        <div class="page-breadcrumb">Laporan / Absen Karyawan</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Laporan_Absen_Karyawan')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
    </div>
</div>

<!-- Ringkasan Statistik Karyawan -->
<div class="stat-cards" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total Karyawan</div>
            <div class="stat-value"><?= $stats['total_karyawan'] ?> Orang</div>
            <div class="stat-trend up" style="color: #6c757d;">Seluruh terdaftar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Karyawan Aktif</div>
            <div class="stat-value" style="color: #198754;"><?= $stats['total_aktif'] ?> Aktif</div>
            <div class="stat-trend up" style="color: #198754; font-weight: 500;"><i class="fa-solid fa-check"></i> Siap bekerja</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fa-solid fa-user-clock"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Karyawan Cuti</div>
            <div class="stat-value" style="color: #ffc107;"><?= $stats['total_cuti'] ?> Orang</div>
            <div class="stat-trend up" style="color: #ffc107; font-weight: 500;"><i class="fa-solid fa-hourglass-half"></i> Sedang izin/cuti</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="fa-solid fa-user-minus"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Karyawan Keluar</div>
            <div class="stat-value" style="color: #dc3545;"><?= $stats['total_keluar'] ?> Resign</div>
            <div class="stat-trend up" style="color: #dc3545; font-weight: 500;"><i class="fa-solid fa-xmark"></i> Non-aktif / Keluar</div>
        </div>
    </div>
</div>

<!-- Card Filter Laporan -->
<div class="dashboard-panel" style="margin-bottom: 24px; padding: 20px;">
    <div class="panel-header" style="margin-bottom: 16px;">
        <h2 class="panel-title"><i class="fa-solid fa-filter"></i> Filter Parameter Karyawan</h2>
    </div>
    
    <form method="GET" action="" id="filter-form" style="display: flex; gap: 20px; align-items: flex-end; flex-wrap: wrap;">
        <!-- Filter Status -->
        <div style="flex: 1; min-width: 200px; display: flex; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Status Staf / Kehadiran</label>
            <select name="status" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <option value="semua" <?= $filterStatus == 'semua' ? 'selected' : '' ?>>Semua Status</option>
                <option value="Aktif" <?= $filterStatus == 'Aktif' ? 'selected' : '' ?>>Aktif (Bekerja)</option>
                <option value="Cuti" <?= $filterStatus == 'Cuti' ? 'selected' : '' ?>>Cuti (Izin)</option>
                <option value="Keluar" <?= $filterStatus == 'Keluar' ? 'selected' : '' ?>>Keluar (Resign)</option>
            </select>
        </div>

        <!-- Filter Departemen -->
        <div style="flex: 1; min-width: 220px; display: flex; flex-direction: column; gap: 8px;">
            <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Departemen Kerja</label>
            <select name="departemen" class="form-control" style="width: 100%; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; background: var(--bg-color); color: var(--text-primary);">
                <option value="semua" <?= $filterDept == 'semua' ? 'selected' : '' ?>>Semua Departemen</option>
                <?php foreach ($listDept as $d): ?>
                    <option value="<?= $d->id_dept ?>" <?= $filterDept == $d->id_dept ? 'selected' : '' ?>>
                        <?= $d->nama_dept ?>
                    </option>
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
            <i class="fa-solid fa-address-book"></i> Detail Status Absensi & Keanggotaan Karyawan
            <span style="font-size: 13px; font-weight: normal; color: var(--text-secondary); margin-left: 8px;">
                (
                <?php 
                $statusLabels = ['semua' => 'Semua Karyawan', 'Aktif' => 'Hanya Aktif', 'Cuti' => 'Sedang Cuti', 'Keluar' => 'Telah Keluar'];
                echo $statusLabels[$filterStatus];
                ?>
                )
            </span>
        </h2>
    </div>

    <div class="table-responsive">
        <table class="modern-table" style="min-width: 1000px;">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th>Departemen</th>
                    <th>Email Kantor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan)): ?>
                    <?php foreach ($laporan as $k): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--accent-color);"><?= $k->nip_karyawan ?></td>
                            <td style="font-weight: 500; color: var(--text-primary);"><?= $k->nama_staf ?></td>
                            <td><?= $k->jabatan ?></td>
                            <td style="font-weight: 500;"><?= $k->nama_dept ?? '-' ?></td>
                            <td><?= $k->email_kantor ?></td>
                            <td>
                                <?php
                                $statusClass = 'status-success'; // default
                                $status = strtolower(trim($k->status_staf));
                                if ($status == 'cuti') {
                                    $statusClass = 'status-warning';
                                } elseif ($status == 'keluar') {
                                    $statusClass = 'status-danger';
                                }
                                ?>
                                <span class="status-badge <?= $statusClass ?>"><?= $k->status_staf ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 32px 0;">
                            <i class="fa-solid fa-user-slash" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            Tidak ada data karyawan ditemukan pada periode/filter ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
