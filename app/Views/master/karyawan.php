<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Data Karyawan</h1>
        <div class="page-breadcrumb">Data Master / Karyawan</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Karyawan')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Karyawan
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Daftar Karyawan</h2>
        <div class="search-bar" style="width: 250px;">
            <i class="fa-solid fa-search"></i>
            <input type="text" placeholder="Cari karyawan...">
        </div>
    </div>
    <table class="modern-table">
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama Lengkap</th>
                <th>Jabatan</th>
                <th>Departemen</th>
                <th>Email</th>
                <th>Tanggal Masuk</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($karyawan)): ?>
                <?php foreach ($karyawan as $k): ?>
            <tr>
                <td><?= $k->nip_karyawan ?></td>
                <td><?= $k->nama_staf ?></td>
                <td><?= $k->jabatan ?></td>
                <td><?= $k->nama_dept ?? $k->id_dept ?></td>
                <td><?= $k->email_kantor ?></td>
                <td><?= $k->tgl_masuk?></td>
                <?php
                    $statusClass = 'status-success'; // default
                    $status = strtolower(trim($k->status_staf));
                    if ($status == 'cuti') {
                        $statusClass = 'status-warning';
                    } elseif ($status == 'keluar') {
                        $statusClass = 'status-danger';
                    }
                ?>
                <td><span class="status-badge <?= $statusClass ?>"><?= $k->status_staf ?></span></td>
                <td>
                    <button class="panel-action"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada data karyawan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
