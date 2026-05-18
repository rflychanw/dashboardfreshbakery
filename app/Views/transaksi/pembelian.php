<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Pembelian Bahan Baku</h1>
        <div class="page-breadcrumb">Transaksi / Pembelian Bahan</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Pembelian')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Buat PO Baru
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Riwayat Pembelian (Purchase Orders)</h2>
    </div>
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>No. PO</th>
                    <th>Vendor</th>
                    <th>Tanggal</th>
                    <th>Total Harga</th>
                    <th>Metode Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pembelian)): ?>
                    <?php foreach ($pembelian as $pb): ?>
                        <tr>
                            <td><?= $pb->id_pembelian ?></td>
                            <td><?= $pb->nama_perusahaan ?></td>
                            <td><?= $pb->tgl_pembelian ?></td>
                            <td>Rp <?= number_format($pb->total_bayar, 0, ',', '.') ?></td>
                            <td><?= $pb->metode_bayar ?></td>
                            <td>
                                <button class="panel-action"><i class="fa-solid fa-eye"></i></button>
                                <button class="panel-action" style="color: green;"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada data pembelian.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
