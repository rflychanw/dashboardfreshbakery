<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'FreshBakery Dashboard' ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS (Vanilla) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <!-- Additional CSS -->
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <span>Fresh Bakery</span>
                </div>
            </div>
            
            <div class="sidebar-menu">
                <ul class="menu-list">
                    <li><a href="<?= base_url('/') ?>" class="<?= url_is('/') ? 'active' : '' ?>"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                </ul>

                <p class="menu-label">Data Master</p>
                <ul class="menu-list">
                    <li><a href="<?= base_url('provider') ?>" class="<?= (url_is('provider') || url_is('provider/*')) ? 'active' : '' ?>"><i class="fa-solid fa-truck"></i> Provider Pengiriman</a></li>
                    <li><a href="<?= base_url('karyawan') ?>" class="<?= (url_is('karyawan') || url_is('karyawan/*')) ? 'active' : '' ?>"><i class="fa-solid fa-user-tie"></i> Karyawan</a></li>
                    <li><a href="<?= base_url('departemen') ?>" class="<?= (url_is('departemen') || url_is('departemen/*')) ? 'active' : '' ?>"><i class="fa-solid fa-building"></i> Departemen</a></li>
                    <li><a href="<?= base_url('vendor') ?>" class="<?= (url_is('vendor') || url_is('vendor/*')) ? 'active' : '' ?>"><i class="fa-solid fa-store"></i> Vendor</a></li>
                    <li><a href="<?= base_url('produk') ?>" class="<?= (url_is('produk') || url_is('produk/*')) ? 'active' : '' ?>"><i class="fa-solid fa-box"></i> Produk</a></li>
                    <li><a href="<?= base_url('bahan-baku') ?>" class="<?= (url_is('bahan-baku') || url_is('bahan-baku/*')) ? 'active' : '' ?>"><i class="fa-solid fa-seedling"></i> Bahan Baku</a></li>
                    <li><a href="<?= base_url('pelanggan') ?>" class="<?= (url_is('pelanggan') || url_is('pelanggan/*')) ? 'active' : '' ?>"><i class="fa-solid fa-users"></i> Pelanggan</a></li>
                    <li><a href="<?= base_url('daftar-harga') ?>" class="<?= (url_is('daftar-harga') || url_is('daftar-harga/*')) ? 'active' : '' ?>"><i class="fa-solid fa-tags"></i> Daftar Harga</a></li>
                </ul>

                <p class="menu-label">Transaksi</p>
                <ul class="menu-list">
                    <li><a href="<?= base_url('pembelian') ?>" class="<?= (url_is('pembelian') || url_is('pembelian/*')) ? 'active' : '' ?>"><i class="fa-solid fa-cart-shopping"></i> Pembelian Bahan</a></li>
                    <li><a href="<?= base_url('penjualan') ?>" class="<?= (url_is('penjualan') || url_is('penjualan/*')) ? 'active' : '' ?>"><i class="fa-solid fa-bag-shopping"></i> Penjualan</a></li>
                    <li><a href="<?= base_url('pembayaran') ?>" class="<?= (url_is('pembayaran') || url_is('pembayaran/*')) ? 'active' : '' ?>"><i class="fa-solid fa-money-bill-wave"></i> Pembayaran</a></li>
                    <li><a href="<?= base_url('delivery') ?>" class="<?= (url_is('delivery') || url_is('delivery/*')) ? 'active' : '' ?>"><i class="fa-solid fa-truck-fast"></i> Delivery/Pengiriman</a></li>
                    <li><a href="<?= base_url('produksi') ?>" class="<?= (url_is('produksi') || url_is('produksi/*')) ? 'active' : '' ?>"><i class="fa-solid fa-industry"></i> Produksi</a></li>
                </ul>

                <!-- <p class="menu-label">Detail</p>
                <ul class="menu-list">
                    <li><a href="<?= base_url('detail/bahan-baku') ?>" class="<?= (url_is('detail/bahan-baku') || url_is('detail/bahan-baku/*')) ? 'active' : '' ?>"><i class="fa-solid fa-layer-group"></i> Bahan Baku</a></li>
                    <li><a href="<?= base_url('detail/delivery') ?>" class="<?= (url_is('detail/delivery') || url_is('detail/delivery/*')) ? 'active' : '' ?>"><i class="fa-solid fa-truck-ramp-box"></i> Detail Delivery</a></li>
                    <li><a href="<?= base_url('detail/pembelian') ?>" class="<?= (url_is('detail/pembelian') || url_is('detail/pembelian/*')) ? 'active' : '' ?>"><i class="fa-solid fa-receipt"></i> Detail Pembelian</a></li>
                    <li><a href="<?= base_url('detail/penjualan') ?>" class="<?= (url_is('detail/penjualan') || url_is('detail/penjualan/*')) ? 'active' : '' ?>"><i class="fa-solid fa-file-invoice-dollar"></i> Detail Penjualan</a></li>
                </ul> -->

                <p class="menu-label">Laporan</p>
                <ul class="menu-list">
                    <li><a href="<?= base_url('laporan/produksi') ?>" class="<?= (url_is('laporan/produksi') || url_is('laporan/produksi/*')) ? 'active' : '' ?>"><i class="fa-solid fa-chart-line"></i> Laporan Produksi</a></li>
                    <li><a href="<?= base_url('laporan/penjualan') ?>" class="<?= (url_is('laporan/penjualan') || url_is('laporan/penjualan/*')) ? 'active' : '' ?>"><i class="fa-solid fa-file-invoice-dollar"></i> Laporan Penjualan</a></li>
                    <li><a href="<?= base_url('laporan/pengiriman') ?>" class="<?= (url_is('laporan/pengiriman') || url_is('laporan/pengiriman/*')) ? 'active' : '' ?>"><i class="fa-solid fa-truck-fast"></i> Laporan Pengiriman</a></li>
                    <li><a href="<?= base_url('laporan/absen') ?>" class="<?= (url_is('laporan/absen') || url_is('laporan/absen/*')) ? 'active' : '' ?>"><i class="fa-solid fa-user-check"></i> Laporan Absen Karyawan</a></li>
                    <li><a href="<?= base_url('laporan/pembelian') ?>" class="<?= (url_is('laporan/pembelian') || url_is('laporan/pembelian/*')) ? 'active' : '' ?>"><i class="fa-solid fa-cart-shopping"></i> Laporan Pembelian Bahan</a></li>
                </ul>

                <hr style="border: none; border-top: 1px solid var(--border-color); margin: 24px 0;">
                <ul class="menu-list">
                    <li><a href="<?= base_url('logout') ?>" style="color: #dc3545;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="top-header">
                <div class="header-left">
                    <button class="mobile-toggle" id="mobile-toggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="search-bar">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="menu-search-input" placeholder="Cari menu atau data...">
                        <div id="search-results" class="search-results-container"></div>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="notification-btn">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge">3</span>
                    </button>
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name=<?= session()->get('username') ?>&background=0d6efd&color=fff" alt="User Avatar">
                        <div class="user-info">
                            <span class="user-name"><?= session()->get('username') ?></span>
                            <span class="user-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content-wrapper">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <!-- Core Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <!-- Global App Scripts -->
    <script>
        async function exportToExcel(filename = 'Data_FreshBakery') {
            const table = document.querySelector('.modern-table');
            if (!table) {
                alert('Tidak ada tabel untuk diekspor!');
                return;
            }
            
            const headers = [];
            const headerCells = table.querySelectorAll('thead th');
            let actionIdx = -1;
            
            headerCells.forEach((th, index) => {
                const title = th.innerText.trim();
                if (title.toLowerCase() === 'aksi') {
                    actionIdx = index;
                } else {
                    headers.push({ name: title, filterButton: true });
                }
            });

            const rows = [];
            const dataRows = table.querySelectorAll('tbody tr');
            
            dataRows.forEach(tr => {
                const rowData = [];
                tr.querySelectorAll('td').forEach((td, index) => {
                    if (index !== actionIdx) {
                        rowData.push(td.innerText.trim());
                    }
                });
                rows.push(rowData);
            });

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Data');

            worksheet.addTable({
                name: 'MainTable',
                ref: 'A1',
                headerRow: true,
                style: { theme: 'TableStyleMedium2', showRowStripes: true },
                columns: headers,
                rows: rows,
            });

            worksheet.columns.forEach(column => { column.width = 20; });
            const buffer = await workbook.xlsx.writeBuffer();
            saveAs(new Blob([buffer]), filename + '.xlsx');
        }

        // Menu Search Logic
        const menuItems = [
            { name: 'Dashboard', url: '<?= base_url('/') ?>', icon: 'fa-house' },
            { name: 'Provider Pengiriman', url: '<?= base_url('provider') ?>', icon: 'fa-truck' },
            { name: 'Karyawan', url: '<?= base_url('karyawan') ?>', icon: 'fa-user-tie' },
            { name: 'Departemen', url: '<?= base_url('departemen') ?>', icon: 'fa-building' },
            { name: 'Vendor', url: '<?= base_url('vendor') ?>', icon: 'fa-store' },
            { name: 'Produk', url: '<?= base_url('produk') ?>', icon: 'fa-box' },
            { name: 'Bahan Baku', url: '<?= base_url('bahan-baku') ?>', icon: 'fa-seedling' },
            { name: 'Pelanggan', url: '<?= base_url('pelanggan') ?>', icon: 'fa-users' },
            { name: 'Daftar Harga', url: '<?= base_url('daftar-harga') ?>', icon: 'fa-tags' },
            { name: 'Pembelian Bahan', url: '<?= base_url('pembelian') ?>', icon: 'fa-cart-shopping' },
            { name: 'Penjualan', url: '<?= base_url('penjualan') ?>', icon: 'fa-bag-shopping' },
            { name: 'Pembayaran', url: '<?= base_url('pembayaran') ?>', icon: 'fa-money-bill-wave' },
            { name: 'Delivery', url: '<?= base_url('delivery') ?>', icon: 'fa-truck-fast' },
            { name: 'Produksi', url: '<?= base_url('produksi') ?>', icon: 'fa-industry' },
            { name: 'Detail Bahan Baku', url: '<?= base_url('detail/bahan-baku') ?>', icon: 'fa-layer-group' },
            { name: 'Detail Delivery', url: '<?= base_url('detail/delivery') ?>', icon: 'fa-truck-ramp-box' },
            { name: 'Detail Pembelian', url: '<?= base_url('detail/pembelian') ?>', icon: 'fa-receipt' },
            { name: 'Detail Penjualan', url: '<?= base_url('detail/penjualan') ?>', icon: 'fa-file-invoice-dollar' },
            { name: 'Laporan Produksi', url: '<?= base_url('laporan/produksi') ?>', icon: 'fa-chart-line' },
            { name: 'Laporan Penjualan', url: '<?= base_url('laporan/penjualan') ?>', icon: 'fa-file-invoice-dollar' },
            { name: 'Laporan Pengiriman', url: '<?= base_url('laporan/pengiriman') ?>', icon: 'fa-truck-fast' },
            { name: 'Laporan Absen Karyawan', url: '<?= base_url('laporan/absen') ?>', icon: 'fa-user-check' },
            { name: 'Laporan Pembelian Bahan', url: '<?= base_url('laporan/pembelian') ?>', icon: 'fa-cart-shopping' },
        ];

        const searchInput = document.getElementById('menu-search-input');
        const resultsContainer = document.getElementById('search-results');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                resultsContainer.innerHTML = '';
                if (query.length < 1) { resultsContainer.style.display = 'none'; return; }
                const filtered = menuItems.filter(item => item.name.toLowerCase().includes(query));
                if (filtered.length > 0) {
                    filtered.forEach(item => {
                        const div = document.createElement('a');
                        div.href = item.url;
                        div.className = 'search-result-item';
                        div.innerHTML = `<i class="fa-solid ${item.icon}"></i> <span>${item.name}</span>`;
                        resultsContainer.appendChild(div);
                    });
                    resultsContainer.style.display = 'block';
                } else { resultsContainer.style.display = 'none'; }
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                    resultsContainer.style.display = 'none';
                }
            });
        }

        document.getElementById('mobile-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.mobile-overlay').classList.toggle('active');
        });
        
        const overlay = document.createElement('div');
        overlay.className = 'mobile-overlay';
        document.body.appendChild(overlay);
        
        overlay.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.remove('active');
            this.classList.remove('active');
        });

        // Global DataTables Initialization
        $(document).ready(function() {
            if ($('.modern-table').length > 0) {
                // 1. Inisialisasi DataTable terlebih dahulu
                $('.modern-table').DataTable({
                    "pageLength": 10,
                    "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                    "language": {
                        "lengthMenu": "Tampilkan _MENU_ data",
                        "search": "Cari Cepat:",
                        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        "paginate": {
                            "next": "Berikutnya",
                            "previous": "Sebelumnya"
                        },
                        "emptyTable": "Tidak ada data yang tersedia",
                        "zeroRecords": "Data tidak ditemukan"
                    },
                    "order": [], // Disable initial sort to keep server-side order
                    "responsive": true
                });

                // 2. Bungkus tabel secara dinamis SETELAH inisialisasi agar controls (pagination & search) tidak ikut tergeser
                $('.modern-table').each(function() {
                    if (!$(this).parent().hasClass('table-scroll-wrapper')) {
                        $(this).wrap('<div class="table-scroll-wrapper" style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin-bottom: 1rem;"></div>');
                    }
                });
            }
        });
    </script>
    
    <!-- Scripts from specific views -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
