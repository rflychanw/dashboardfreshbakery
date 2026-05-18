<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Daftar Harga</h1>
        <div class="page-breadcrumb">Data Master / Daftar Harga</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Daftar_Harga')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Harga Baru
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Master Harga Jual</h2>
    </div>
    <table class="modern-table">
        <thead>
            <tr>
                <th>SKU Produk</th>
                <th>Harga Asli</th>
                <th>Harga Diskon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($daftar_harga)): ?>
                <?php foreach ($daftar_harga as $dh): ?>
                    <tr>
                        <td><?= $dh->kode_produk ?></td>
                        <td>Rp <?= number_format($dh->Harga_asli, 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($dh->Harga_diskon, 0, ',', '.') ?></td>
                        <td>
                            <button class="panel-action"><i class="fa-solid fa-eye"></i></button>
                            <button class="panel-action" style="color: green;"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada data harga.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
