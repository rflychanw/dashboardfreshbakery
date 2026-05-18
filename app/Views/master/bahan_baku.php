<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Data Bahan Baku</h1>
        <div class="page-breadcrumb">Data Master / Bahan Baku</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Bahan_Baku')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Bahan Baku
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Stok Bahan Baku</h2>
    </div>
    <table class="modern-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Perusahaan</th>
                <th>Nama Bahan</th>
                <th>Stok Tersedia</th>
                <th>Satuan</th>
                <th>Harga Beli</th>
                <th>Status Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bahan_baku)): ?>
                <?php foreach ($bahan_baku as $b): ?>
            <tr>
                <td><?= $b->id_bahan ?></td>
                <td><?= $b->nama_perusahaan ?? $b->id_vendor?></td>
                <td><?= $b->nama_bahan ?></td>
                <td><?= $b->stok_saat_ini ?></td>
                <td><?= $b->status_satuan ?></td>
                <td>Rp <?= number_format($b->harga_beli_unit, 0, ',', '.') ?></td>
                <td><?= $b->stok_min ?></td>
                <td>
                    <button class="panel-action"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada data bahan baku.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
