<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Delivery & Pengiriman</h1>
        <div class="page-breadcrumb">Transaksi / Delivery</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Delivery')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Jadwal Pengiriman
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Jadwal Pengiriman Hari Ini</h2>
    </div>
    <div class="table-responsive">
        <table class="modern-table" style="min-width: 1300px;">
            <thead>
                <tr>
                    <th>No. Pengiriman</th>
                    <th>PIC</th>
                    <th>Penerima</th>
                    <th>Alamat Penerima</th>
                    <th>Jenis Pembayaran</th>
                    <th>Provider</th>
                    <th>Layanan</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($delivery)): ?>
                <?php foreach ($delivery as $d): ?>
                    <tr>
                        <td><?= $d->kode_pengiriman ?></td>
                        <td><?= $d->nama_staf ?></td>
                        <td><?= $d->penerima ?></td>
                        <td><?= $d->alamat ?></td>
                        <td><?= $d->Jenis_pembayaran ?></td>
                        <td><?= $d->nama_provider ?></td>
                        <td><?= $d->jenis_layanan ?></td>
                        <td><?= $d->nama_produk ?></td>
                        <td><?= $d->jumlah ?></td>
                        <td><?= $d->status ?></td>
                        <td>
                            <button class="panel-action"><i class="fa-solid fa-eye"></i></button>
                            <button class="panel-action" style="color: green;"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" style="text-align: center;">Belum ada data delivery.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
