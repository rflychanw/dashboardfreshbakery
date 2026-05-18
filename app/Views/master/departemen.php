<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Data Departemen</h1>
        <div class="page-breadcrumb">Data Master / Departemen</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Departemen')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Departemen
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Daftar Departemen</h2>
    </div>
    <table class="modern-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Departemen</th>
                <th>Budget Tahunan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($departemen)): ?>
                <?php foreach ($departemen as $d): ?>
            <tr>
                <td><?= $d->id_dept ?></td>
                <td><?= $d->nama_dept ?></td>
                <td><?= $d->budget_tahunan ?></td>
                <td>
                    <button class="panel-action"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada data departemen.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
