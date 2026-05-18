<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Data Produk</h1>
        <div class="page-breadcrumb">Data Master / Produk</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Produk')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Katalog Produk Bakery</h2>
    </div>
    <table class="modern-table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th>Harga Diskon</th>
                <th>Stok</th>
                <th>Masa Kadaluarsa</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($produk)): ?>
                <?php foreach ($produk as $p): ?>
                    <tr>
                        <td><?= $p->kode_produk ?></td>
                        <td><?= $p->nama_produk ?></td>
                        <td><?= $p->kategori_roti ?></td>
                        <td>Rp <?= number_format((float)($p->harga_jual ?? 0), 0, ',', '.') ?></td>
                        <td>Rp <?= number_format((float)($p->Harga_diskon ?? 0), 0, ',', '.') ?></td>
                        <td><?= $p->stok ?></td>
                        <td><?= $p->masa_kadaluarsa ?></td>
                        <td>
                            <button class="panel-action"><i class="fa-solid fa-eye"></i></button>
                            <button class="panel-action" style="color: green;"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada data produk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
