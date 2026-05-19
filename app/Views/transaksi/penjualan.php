<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Transaksi Penjualan</h1>
        <div class="page-breadcrumb">Transaksi / Penjualan</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Penjualan')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Transaksi Baru
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Data Penjualan Terbaru</h2>
    </div>
    <div class="table-responsive">
        <table class="modern-table" style="min-width: 1400px;">
            <thead>
                <tr>
                    <th>No. Faktur</th>
                    <th>Pelanggan</th>
                    <th>Karyawan</th>
                    <th>Tanggal Transaksi</th>
                    <th>Pajak PPN</th>
                    <th>Nama Produk</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: center;">Jumlah (Qty)</th>
                    <th style="text-align: right;">Diskon</th>
                    <th style="text-align: right;">Sub Total</th>
                    <th>Metode Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($penjualan)): ?>
                <?php foreach ($penjualan as $p): ?>
                    <tr>
                        <td><?= $p->No_faktur ?></td>
                        <td><?= $p->nama_lengkap ?></td>
                        <td><?= $p->nama_staf ?></td>
                        <td><?= $p->tgl_teransaksi ?></td>
                        <td style="color: #6c757d;">Rp <?= number_format((float)($p->pajak_ppn ?? 0), 0, ',', '.') ?></td>
                        <td style="font-weight: 600; color: var(--text-primary);"><?= $p->nama_produk ?? '-' ?></td>
                        <td style="text-align: right;">Rp <?= number_format((float)($p->harga_satuan ?? 0), 0, ',', '.') ?></td>
                        <td style="text-align: center; font-weight: 500;"><?= $p->qty_beli ?? 0 ?> Pcs</td>
                        <td style="text-align: right; color: #dc3545;">Rp <?= number_format((float)($p->diskon_item ?? 0), 0, ',', '.') ?></td>
                        <td style="text-align: right; font-weight: 600; color: #198754;">Rp <?= number_format((float)($p->subtotal ?? 0), 0, ',', '.') ?></td>
                        <td>
                            <span class="status-badge" style="background: #e9ecef; color: #495057; font-weight: 500;">
                                <?= $p->Metode_pembayaran ?>
                            </span>
                        </td>
                        <td>
                            <button class="panel-action"><i class="fa-solid fa-eye"></i></button>
                            <button class="panel-action" style="color: green;"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12" style="text-align: center; padding: 24px 0; color: var(--text-secondary);">Belum ada data penjualan.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
