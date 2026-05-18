<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Proses Produksi</h1>
        <div class="page-breadcrumb">Transaksi / Produksi</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Produksi')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Buat Surat Perintah Kerja
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Surat Perintah Kerja (SPK) Produksi</h2>
    </div>
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>No. SPK</th>
                    <th>Tanggal Produksi</th>
                    <th>PIC</th>
                    <th>Bahan</th>
                    <th>Hasil Produk</th>
                    <th>Varian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($produksi)): ?>
                <?php foreach ($produksi as $pi): ?>
                    <tr>
                        <td><?= $pi->no_spk ?></td>
                        <td><?= $pi->tgl_produksi ?></td>
                        <td><?= $pi->nama_staf ?></td>
                        <td><?= $pi->nama_bahan ?></td>
                        <td><?= $pi->hasil_produk ?></td>
                        <td><?= $pi->varian ?></td>
                        <?php
                            $statusClass = 'status-pending'; // Default biru
                            $statusText = strtolower(trim($pi->status));
                            if (strpos($statusText, 'proses') !== false) {
                                $statusClass = 'status-warning';
                            } elseif (strpos($statusText, 'selesai') !== false) {
                                $statusClass = 'status-success';
                            }
                        ?>
                        <td><span class="status-badge <?= $statusClass ?>"><?= $pi->status ?></span></td>
                        <td>
                            <button class="panel-action"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada data produksi.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
