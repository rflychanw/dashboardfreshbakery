<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Stok Bahan Baku</h1>
        <div class="page-breadcrumb">Detail / Bahan Baku</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Detail_Bahan_Baku')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Data
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Kartu Stok & Pergerakan Bahan</h2>
    </div>
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Vendor</th>
                    <th>Nama Bahan</th>
                    <th>Stok Saat Ini</th>
                    <th>Status Satuan</th>
                    <th>Harga Beli Unit</th>
                    <th>Stok Min</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bahan_baku)): ?>
                <?php foreach ($bahan_baku as $bb): ?>
                    <tr>
                        <td><?= $bb->id_bahan ?></td>
                        <td><?= $bb->nama_perusahaan ?></td>
                        <td><?= $bb->nama_bahan ?></td>
                        <td><?= $bb->stok_saat_ini ?></td>
                        <td><?= $bb->status_satuan ?></td>
                        <td><?= $bb->harga_beli_unit ?></td>
                        <td><?= $bb->stok_min ?></td>
                        <td>
                            <button class="panel-action"><i class="fa-solid fa-eye"></i></button>
                            <button class="panel-action" style="color: green;"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Belum ada data bahan baku.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

