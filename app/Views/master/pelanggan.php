<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Data Pelanggan</h1>
        <div class="page-breadcrumb">Data Master / Pelanggan</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Pelanggan')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Pelanggan
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Daftar Pelanggan (Toko & Retail)</h2>
    </div>
    <table class="modern-table" id="table-pelanggan">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pelanggan / Toko</th>
                <th>No. Telepon</th>
                <th>Total Poin Loyalitas</th>
                <th>Aksi</th>
            </tr> 
        </thead>
        <tbody>
            <?php if (!empty($pelanggan)): ?>
                <?php foreach ($pelanggan as $p): ?>
                    <tr>
                        <td><?= $p->Id_pelanggan ?? $p->id ?? '-' ?></td>
                        <td><?= $p->nama_lengkap ?? $p->nama ?? '-' ?></td>
                        <td><?= $p->no_tlp ?? $p->telepon ?? '-' ?></td>
                        <td><?= $p->total_poin_loyalitas ?? '-' ?></td>
                        <td>
                            <button class="panel-action"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada data pelanggan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
