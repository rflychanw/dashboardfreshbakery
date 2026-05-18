<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Penerimaan Pembayaran</h1>
        <div class="page-breadcrumb">Transaksi / Pembayaran</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Pembayaran')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Catat Pembayaran
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Histori Pembayaran</h2>
    </div>
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Metode Pembayaran</th>
                    <th>Nama Kasir</th>
                    <th>Pelanggan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($pembayaran)): ?>
                <?php foreach ($pembayaran as $p): ?>
                    <tr>
                        <td><?= $p->Id_pembayaran ?></td>
                        <td><?= $p->Metode_pembayaran  ?></td>
                        <td><?= $p->nama_staf ?></td>
                        <td><?= $p->nama_lengkap  ?></td>
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
</div>
<?= $this->endSection() ?>
