<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Data Vendor</h1>
        <div class="page-breadcrumb">Data Master / Vendor</div>
    </div>
    <div class="header-actions-group">
        <button class="btn-secondary" onclick="exportToExcel('Data_Vendor')">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Vendor
        </button>
    </div>
</div>

<div class="dashboard-panel">
    <div class="panel-header">
        <h2 class="panel-title">Daftar Vendor Material</h2>
    </div>
    <table class="modern-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Vendor</th>
                <th>Nama Sales</th>
                <th>No. Telepon</th>
                <th>Alamat</th>
                <th>Termin Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($vendor)): ?>
                <?php foreach ($vendor as $v): ?>
                    <tr>
                        <td><?= $v->id_vendor ?></td>
                        <td><?= $v->nama_perusahaan ?></td>
                        <td><?= $v->nama_sales ?></td>
                        <td><?= $v->no_telp ?></td>
                        <td><?= $v->alamat_kantor ?></td>
                        <td><?= $v->termin_pembayaran ?></td>
                        <td>
                            <button class="panel-action"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="panel-action" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada data vendor.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
