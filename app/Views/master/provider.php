<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Provider Pengiriman</h1>
        <div class="page-breadcrumb">Data Master / Provider Pengiriman</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Provider')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Provider
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Daftar Provider Pengiriman</h2>
        
    </div>
    <table class="modern-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Provider</th>
                <th>Jenis Layanan</th>
                <th>Kontak</th>
                <th>Alamat Kantor</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($provider)): ?>
                <?php foreach ($provider as $pr): ?>
                    <tr>
                        <td><?= $pr->id_provider ?></td>
                        <td><?= $pr->nama_provider  ?></td>
                        <td><?= $pr->jenis_layanan ?></td>
                        <td><?= $pr->no_telp  ?></td>
                        <td><?= $pr->alamat_kantor  ?></td>
                        <?php
                            $statusClass = 'status-success';
                            $statusText = strtolower(trim($pr->status_aktif));
                            if (strpos($statusText, 'nonaktif') !== false) {
                                $statusClass = 'status-danger';
                            } elseif (strpos($statusText, 'aktif') !== false) {
                                $statusClass = 'status-success';
                            }
                        ?>
                        <td><span class="status-badge <?= $statusClass ?>"><?= $pr->status_aktif ?></span></td>
                        <td>
                            <button class="panel-action btn-prototype-edit" style="color: green;"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="panel-action btn-prototype-delete" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada data provider pengiriman.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
