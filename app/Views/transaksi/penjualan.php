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
        <table class="modern-table">
            <thead>
                <tr>
                    <th>No. Faktur</th>
                    <th>Pelanggan</th>
                    <th>Karyawan</th>
                    <th>Tanggal Transaksi</th>
                    <th>Pajak PPN</th>
                    <th>Total Bruto</th>
                    <th>Total Netto</th>
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
                        <td>Rp <?= number_format((float)($p->pajak_ppn ?? 0), 0, ',', '.') ?></td>
                        <td>Rp <?= number_format((float)($p->total_bruto ?? 0), 0, ',', '.') ?></td>
                        <td>Rp <?= number_format((float)($p->total_netto ?? 0), 0, ',', '.') ?></td>
                        <td><?= $p->Metode_pembayaran ?></td>
                        <td>
                            <button class="panel-action"><i class="fa-solid fa-eye"></i></button>
                            <button class="panel-action" style="color: green;"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center;">Belum ada data penjualan.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
