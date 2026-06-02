<?php
$db = \Config\Database::connect();
$globalDeptList = [];
try { $globalDeptList = array_column($db->table('departemen')->select('nama_dept')->get()->getResultArray(), 'nama_dept'); } catch (\Exception $e) {}
?>
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
                    <!-- <li><a href="<?= base_url('bahan-baku') ?>" class="<?= (url_is('bahan-baku') || url_is('bahan-baku/*')) ? 'active' : '' ?>"><i class="fa-solid fa-seedling"></i> Bahan Baku</a></li> -->
                    <li><a href="<?= base_url('pelanggan') ?>" class="<?= (url_is('pelanggan') || url_is('pelanggan/*')) ? 'active' : '' ?>"><i class="fa-solid fa-users"></i> Pelanggan</a></li>
                    <!-- <li><a href="<?= base_url('daftar-harga') ?>" class="<?= (url_is('daftar-harga') || url_is('daftar-harga/*')) ? 'active' : '' ?>"><i class="fa-solid fa-tags"></i> Daftar Harga</a></li> -->
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
        const globalDropdownOptions = {
            departemen: <?= json_encode($globalDeptList) ?>
        };

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

        // Dynamic Premium PDF Export (Matches layout template perfectly)
        function exportToPDF(reportTitle) {
            const table = document.querySelector('.modern-table');
            if (!table) {
                alert('Tidak ada tabel untuk diekspor!');
                return;
            }
            
            // Find headers, skip Action
            const headers = [];
            let actionIdx = -1;
            table.querySelectorAll('thead th').forEach((th, index) => {
                const text = th.innerText.trim();
                if (text.toLowerCase() === 'aksi' || text.toLowerCase() === 'action') {
                    actionIdx = index;
                } else {
                    headers.push(text);
                }
            });

            // Find rows
            const rows = [];
            table.querySelectorAll('tbody tr').forEach(tr => {
                // Skip empty row placeholder
                if (tr.querySelector('td[colspan]')) return;
                const rowData = [];
                tr.querySelectorAll('td').forEach((td, index) => {
                    if (index !== actionIdx) {
                        rowData.push(td.innerHTML.trim()); // Keep original status badges or stylings
                    }
                });
                if (rowData.length > 0) {
                    rows.push(rowData);
                }
            });

            // Get filter details for the subtitle/period
            let periodText = 'Semua Periode';
            const periodSelect = document.getElementById('periode-select');
            if (periodSelect) {
                const val = periodSelect.value;
                if (val === 'hari') {
                    const tgl = document.querySelector('input[name="tanggal"]')?.value;
                    periodText = tgl ? formatDateIndo(tgl) : 'Harian';
                } else if (val === 'minggu') {
                    const start = document.querySelector('input[name="start_date"]')?.value;
                    const end = document.querySelector('input[name="end_date"]')?.value;
                    periodText = (start && end) ? formatDateIndo(start) + ' s/d ' + formatDateIndo(end) : 'Mingguan';
                } else if (val === 'bulan') {
                    const bln = document.querySelector('select[name="bulan"]')?.value;
                    const thn = document.querySelector('select[name="tahun"]')?.value;
                    periodText = (bln && thn) ? formatMonthYearIndo(bln, thn) : 'Bulanan';
                }
            } else {
                // Fallback: Check if there's a filter parameter in the URL or a dynamic label
                const activeLabel = document.querySelector('.panel-title span');
                if (activeLabel) {
                    periodText = activeLabel.innerText.replace(/[()]/g, '').trim();
                }
            }

            // Get main stat details (last stat card usually contains the grand total/revenue)
            let statTitle = 'Total';
            let statValue = '0';
            const statCards = document.querySelectorAll('.stat-card');
            if (statCards.length > 0) {
                // Take the last stat card which is usually Total Revenue/Expenditure/etc.
                const lastCard = statCards[statCards.length - 1];
                statTitle = lastCard.querySelector('.stat-title')?.innerText.trim() || 'Total';
                statValue = lastCard.querySelector('.stat-value')?.innerText.trim() || '0';
            }

            // Build the Print Template
            const printWindow = window.open('', '_blank', 'width=1100,height=850');
            
            let htmlContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak ${reportTitle}</title>
                <style>
                    @page {
                        size: A4 landscape;
                        margin: 15mm 15mm 15mm 15mm;
                    }
                    body {
                        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                        color: #000;
                        margin: 0;
                        padding: 0;
                        font-size: 11px;
                        line-height: 1.4;
                        background: #fff;
                    }
                    /* Header template style */
                    .header-container {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        margin-bottom: 2px;
                        position: relative;
                    }
                    .header-left {
                        flex: 1;
                    }
                    .company-name {
                        font-size: 22px;
                        font-weight: 800;
                        letter-spacing: 0.5px;
                        margin: 0 0 6px 0;
                        text-transform: uppercase;
                        color: #111;
                    }
                    .company-address {
                        font-size: 10px;
                        color: #444;
                        margin: 0 0 3px 0;
                        line-height: 1.3;
                    }
                    .company-contact {
                        font-size: 10px;
                        color: #444;
                        margin: 0;
                    }
                    .header-right {
                        text-align: right;
                        display: flex;
                        flex-direction: column;
                        align-items: flex-end;
                    }
                    /* Red circles globe bakery logo style matching template */
                    .company-logo-css {
                        width: 50px;
                        height: 50px;
                        background: #dc3545;
                        border-radius: 50%;
                        position: relative;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #fff;
                        font-weight: 900;
                        font-size: 18px;
                        border: 2px solid #000;
                        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
                        letter-spacing: -0.5px;
                    }
                    .company-logo-css::after {
                        content: '';
                        position: absolute;
                        top: -5px;
                        right: -5px;
                        width: 20px;
                        height: 20px;
                        background: rgba(0, 0, 0, 0.15);
                        border-radius: 50%;
                    }
                    .company-web {
                        font-size: 10px;
                        font-weight: bold;
                        color: #000;
                        margin-top: 6px;
                        text-decoration: none;
                    }
                    .double-line {
                        border-top: 3px double #000;
                        margin: 6px 0 20px 0;
                        width: 100%;
                    }
                    
                    /* Report Title & Subtitle */
                    .report-title-container {
                        text-align: center;
                        margin-bottom: 25px;
                    }
                    .report-title {
                        font-size: 20px;
                        font-weight: 800;
                        text-transform: uppercase;
                        margin: 0 0 6px 0;
                        letter-spacing: 1px;
                        color: #000;
                    }
                    .report-subtitle {
                        font-size: 12px;
                        font-weight: bold;
                        color: #333;
                        margin: 0;
                    }

                    /* Stat Summary box on top-right of table */
                    .summary-box-wrapper {
                        display: flex;
                        justify-content: flex-end;
                        margin-bottom: 12px;
                    }
                    .summary-box {
                        display: flex;
                        border: 1.5px solid #000;
                        font-size: 11px;
                        height: 28px;
                        min-width: 250px;
                    }
                    .summary-label {
                        background: #000;
                        color: #fff;
                        padding: 0 15px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: bold;
                        text-transform: uppercase;
                        flex: 1;
                    }
                    .summary-value {
                        background: #f2f2f2;
                        color: #000;
                        padding: 0 20px;
                        display: flex;
                        align-items: center;
                        justify-content: flex-end;
                        font-weight: bold;
                        min-width: 120px;
                        font-size: 12px;
                    }

                    /* Main Table */
                    .report-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 30px;
                    }
                    .report-table th, .report-table td {
                        border: 1px solid #000;
                        padding: 6px 8px;
                        vertical-align: middle;
                    }
                    .report-table th {
                        background: #cccccc;
                        color: #000;
                        font-weight: bold;
                        text-align: left;
                        text-transform: uppercase;
                        font-size: 10px;
                    }
                    .report-table tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                    
                    /* Badges styling for print */
                    .status-badge {
                        display: inline-block;
                        padding: 2px 6px;
                        border-radius: 3px;
                        font-size: 9px;
                        font-weight: bold;
                        text-transform: uppercase;
                        border: 1px solid #666;
                        color: #000 !important;
                        background: #eee !important;
                    }
                    .status-badge.status-success {
                        background: #e2f0d9 !important;
                        border-color: #385723;
                    }
                    .status-badge.status-warning {
                        background: #fff2cc !important;
                        border-color: #7f6000;
                    }
                    .status-badge.status-danger {
                        background: #fce4d6 !important;
                        border-color: #c65911;
                    }
                    
                    /* Text alignments */
                    .text-center { text-align: center !important; }
                    .text-right { text-align: right !important; }
                    
                    @media screen {
                        .print-only-container {
                            display: none !important;
                        }
                        body {
                            background: #ffffff !important;
                        }
                    }
                    @media print {
                        .print-only-container {
                            display: block !important;
                        }
                        body {
                            margin: 0;
                            background: #ffffff !important;
                        }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="print-only-container">
                    <!-- Header -->
                    <div class="header-container">
                        <div class="header-left">
                            <h1 class="company-name">Fresh Bakery</h1>
                            <p class="company-address">Jl. Margo Mulyo No. 128, Margahayu | Surabaya | Jawa Timur | Indonesia | 60181</p>
                            <p class="company-contact">Phone: (031) 555-888-999 | Email: finance@freshbakery.com</p>
                        </div>
                        <div class="header-right">
                            <div class="company-logo-css">FB</div>
                            <a href="#" class="company-web" onclick="return false;">www.freshbakery.com</a>
                        </div>
                    </div>
                    
                    <!-- Double line separator -->
                    <div class="double-line"></div>
                    
                    <!-- Title -->
                    <div class="report-title-container">
                        <h2 class="report-title">${reportTitle}</h2>
                        <p class="report-subtitle">${periodText}</p>
                    </div>
                    
                    <!-- Stat box summary -->
                    <div class="summary-box-wrapper">
                        <div class="summary-box">
                            <div class="summary-label">${statTitle}</div>
                            <div class="summary-value">${statValue.replace('Rp ', '')}</div>
                        </div>
                    </div>
                    
                    <!-- Table -->
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th style="width: 35px; text-align: center;">No</th>
                                ${headers.map(h => `<th>${h}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody>
                            ${rows.map((row, idx) => `
                                <tr>
                                    <td class="text-center">${idx + 1}</td>
                                    ${row.map((cell, cIdx) => {
                                        const cleanText = cell.replace(/<[^>]*>/g, '').trim();
                                        const isStatus = cell.includes('status-badge');
                                        const isRupiah = cleanText.startsWith('Rp');
                                        const isNumber = /^[0-9.,]+( Pcs| Orang| Nota| Item| Roti| Paket| Kiriman)?$/.test(cleanText);
                                        
                                        let classes = '';
                                        if (isStatus || cleanText === '-' || (isNumber && cleanText.length < 15)) {
                                            classes = 'class="text-center"';
                                        } else if (isRupiah || (cleanText.includes('Rp') && cleanText.length < 25)) {
                                            classes = 'class="text-right"';
                                        }
                                        
                                        return `<td ${classes}>${cell}</td>`;
                                    }).join('')}
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 500);
                    };
                <\/script>
            </body>
            </html>
            `;
            
            printWindow.document.write(htmlContent);
            printWindow.document.close();
        }

        // Sub-helpers for dates
        function formatDateIndo(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        function formatMonthYearIndo(monthNum, yearNum) {
            const months = {
                '01': 'Januari', '02': 'Februari', '03': 'Maret', '04': 'April',
                '05': 'Mei', '06': 'Juni', '07': 'Juli', '08': 'Agustus',
                '09': 'September', '10': 'Oktober', '11': 'November', '12': 'Desember'
            };
            return months[monthNum] + ' ' + yearNum;
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
                var tableInstance = $('.modern-table').DataTable({
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

                // ==========================================
                // GLOBAL PROTOTYPE CRUD INTERACTIVITY (MOCK)
                // ==========================================
                
                // Append modal HTML if not already exists
                if ($('#global-crud-modal').length === 0) {
                    var modalHtml = `
                    <div class="global-modal-overlay" id="global-crud-modal">
                        <div class="global-modal-content">
                            <div class="global-modal-header">
                                <h3 class="global-modal-title" id="global-modal-title">Form Data</h3>
                                <button class="global-modal-close" id="global-modal-close">&times;</button>
                            </div>
                            <div class="global-modal-body" id="global-modal-body"></div>
                            <div class="global-modal-footer" id="global-modal-footer"></div>
                        </div>
                    </div>
                    <div class="global-toast" id="global-crud-toast">
                        <i class="fa-solid fa-circle-check"></i> <span id="global-toast-message">Berhasil!</span>
                    </div>
                    `;
                    $('body').append(modalHtml);
                }

                // Close modal triggers
                $('body').on('click', '#global-modal-close, .btn-close-modal', function() {
                    closeModal();
                });
                $('body').on('click', '#global-crud-modal', function(e) {
                    if (e.target === this) { closeModal(); }
                });

                function closeModal() {
                    $('#global-crud-modal').removeClass('active');
                    setTimeout(() => { $('#global-crud-modal').css('display', 'none'); }, 250);
                }

                function showToast(message, type = 'success') {
                    var toast = $('#global-crud-toast');
                    toast.removeClass('danger');
                    if (type === 'danger') toast.addClass('danger');
                    toast.find('#global-toast-message').text(message);
                    toast.addClass('active');
                    setTimeout(() => { toast.removeClass('active'); }, 3000);
                }

                // Helper: Get form inputs html
                function getInputHtml(header, value = '') {
                    var lowerHeader = header.toLowerCase();
                    var id = 'modal-input-' + header.replace(/[^a-zA-Z0-9]/g, '-');
                    var label = header;
                    var html = `<div class="form-group-modal"><label for="${id}">${label}</label>`;
                    
                    if (lowerHeader.includes('metode') || lowerHeader.includes('pembayaran') || lowerHeader.includes('bayar')) {
                        html += `<select id="${id}">
                            <option value="Tunai" ${value == 'Tunai' ? 'selected' : ''}>Tunai</option>
                            <option value="Transfer Bank" ${value == 'Transfer Bank' ? 'selected' : ''}>Transfer Bank</option>
                            <option value="Transfer" ${value == 'Transfer' ? 'selected' : ''}>Transfer</option>
                            <option value="Qris" ${value == 'Qris' ? 'selected' : ''}>Qris</option>
                            <option value="E-Wallet" ${value == 'E-Wallet' ? 'selected' : ''}>E-Wallet</option>
                            <option value="COD" ${value == 'COD' ? 'selected' : ''}>COD</option>
                        </select>`;
                    } else if (lowerHeader.includes('status')) {
                        if (window.location.href.includes('absen') || window.location.href.includes('karyawan')) {
                            html += `<select id="${id}">
                                <option value="Aktif" ${value == 'Aktif' ? 'selected' : ''}>Aktif</option>
                                <option value="Cuti" ${value == 'Cuti' ? 'selected' : ''}>Cuti</option>
                                <option value="Keluar" ${value == 'Keluar' ? 'selected' : ''}>Keluar</option>
                            </select>`;
                        } else if (lowerHeader.includes('kirim') || window.location.href.includes('delivery') || window.location.href.includes('pengiriman')) {
                            html += `<select id="${id}">
                                <option value="Pending" ${value == 'Pending' ? 'selected' : ''}>Pending</option>
                                <option value="Proses" ${value == 'Proses' ? 'selected' : ''}>Proses</option>
                                <option value="Terkirim" ${value == 'Terkirim' ? 'selected' : ''}>Terkirim</option>
                            </select>`;
                        } else {
                            html += `<select id="${id}">
                                <option value="Aktif" ${value == 'Aktif' ? 'selected' : ''}>Aktif</option>
                                <option value="Non-Aktif" ${value == 'Non-Aktif' ? 'selected' : ''}>Non-Aktif</option>
                            </select>`;
                        }
                    } else if (lowerHeader.includes('tanggal') || lowerHeader.includes('tgl') || lowerHeader.includes('date')) {
                        var dateVal = value || new Date().toISOString().split('T')[0];
                        html += `<input type="date" id="${id}" value="${dateVal}">`;
                    } else if (lowerHeader.includes('departemen') && window.location.href.includes('karyawan')) {
                        html += `<select id="${id}"><option value="">-- Pilih Departemen --</option>`;
                        globalDropdownOptions.departemen.forEach(opt => {
                            html += `<option value="${opt}" ${value == opt ? 'selected' : ''}>${opt}</option>`;
                        });
                        html += `</select>`;
                    } else {
                        html += `<input type="text" id="${id}" value="${value}" placeholder="Masukkan ${label.toLowerCase()}...">`;
                    }
                    
                    html += `</div>`;
                    return html;
                }

                // Helper: Format cell when displaying in DataTable
                function formatCell(header, value) {
                    var lowerHeader = header.toLowerCase();
                    var trimVal = value.trim();
                    if (lowerHeader.includes('status')) {
                        var badgeClass = 'status-success';
                        if (trimVal.toLowerCase() === 'cuti' || trimVal.toLowerCase() === 'proses') {
                            badgeClass = 'status-warning';
                        } else if (trimVal.toLowerCase() === 'keluar' || trimVal.toLowerCase() === 'pending' || trimVal.toLowerCase() === 'non-aktif') {
                            badgeClass = 'status-danger';
                        }
                        return `<span class="status-badge ${badgeClass}">${value}</span>`;
                    }
                    if (lowerHeader.includes('harga') || lowerHeader.includes('biaya') || lowerHeader.includes('subtotal') || lowerHeader.includes('diskon') || lowerHeader.includes('total') || lowerHeader.includes('ongkir') || lowerHeader.includes('netto') || lowerHeader.includes('bruto') || lowerHeader.includes('ppn')) {
                        if (!value.startsWith('Rp') && value !== '-') {
                            var num = parseFloat(value.replace(/[^0-9.-]+/g,"")) || 0;
                            return 'Rp ' + num.toLocaleString('id-ID');
                        }
                    }
                    return value;
                }

                // Extract action buttons HTML dynamically
                var actionButtonsHtml = `
                    <button class="panel-action btn-prototype-view"><i class="fa-solid fa-eye"></i></button>
                    <button class="panel-action btn-prototype-edit" style="color: green;"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="panel-action btn-prototype-delete" style="color: #dc3545;"><i class="fa-solid fa-trash"></i></button>
                `;

                // Handle click View (Eye)
                $('body').on('click', '.modern-table tbody tr td button:has(.fa-eye), .modern-table tbody tr td .btn-prototype-view, .modern-table tbody tr td button.panel-action:nth-child(1)', function(e) {
                    e.preventDefault();
                    var tr = $(this).closest('tr');
                    var data = tableInstance.row(tr).data();
                    var headers = [];
                    $('.modern-table th').each(function() { headers.push($(this).text().trim()); });
                    
                    var title = 'Detail Data';
                    if (data && data.length > 0) {
                        var detailsHtml = `<div style="display: flex; flex-direction: column; gap: 16px;">`;
                        for (var i = 0; i < headers.length; i++) {
                            if (headers[i].toLowerCase() === 'aksi' || headers[i].toLowerCase() === 'action') continue;
                            var rawCell = data[i] || '-';
                            var cleanCell = $('<div>').html(rawCell).text().trim(); // Strip HTML
                            detailsHtml += `
                                <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                                    <div style="font-size: 11px; color: var(--text-secondary); text-transform: uppercase; font-weight: 600;">${headers[i]}</div>
                                    <div style="font-size: 14px; font-weight: 500; margin-top: 4px; color: var(--text-primary);">${cleanCell}</div>
                                </div>
                            `;
                        }
                        detailsHtml += `</div>`;
                        
                        $('#global-modal-title').html('<i class="fa-solid fa-eye" style="color: var(--accent-color);"></i> ' + title);
                        $('#global-modal-body').html(detailsHtml);
                        $('#global-modal-footer').html('<button class="btn-secondary btn-close-modal" style="padding: 8px 16px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer;">Tutup</button>');
                        $('#global-crud-modal').css('display', 'flex').addClass('active');
                    }
                });

                // Handle click Delete (Trash)
                var rowToDelete = null;
                $('body').on('click', '.modern-table tbody tr td button:has(.fa-trash), .modern-table tbody tr td .btn-prototype-delete, .modern-table tbody tr td button.panel-action[style*="dc3545"]', function(e) {
                    e.preventDefault();
                    rowToDelete = $(this).closest('tr');
                    var data = tableInstance.row(rowToDelete).data();
                    var label = (data && data.length > 1) ? $('<div>').html(data[1]).text().trim() : 'data ini';

                    $('#global-modal-title').html('<i class="fa-solid fa-triangle-exclamation" style="color: #dc3545;"></i> Konfirmasi Hapus');
                    $('#global-modal-body').html('<p style="font-size: 15px; line-height: 1.5; color: var(--text-primary);">Apakah Anda yakin ingin menghapus <strong>' + label + '</strong>? Data ini hanya akan terhapus dari visual halaman ini.</p>');
                    $('#global-modal-footer').html(`
                        <button class="btn-secondary btn-close-modal" style="padding: 8px 16px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer;">Batal</button>
                        <button id="btn-confirm-delete-action" class="btn-primary" style="background: #dc3545; border-color: #dc3545; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; color: white;">Ya, Hapus</button>
                    `);
                    $('#global-crud-modal').css('display', 'flex').addClass('active');
                });

                $('body').on('click', '#btn-confirm-delete-action', function() {
                    if (rowToDelete) {
                        var data = tableInstance.row(rowToDelete).data();
                        var firstColVal = $('<div>').html(data[0]).text().trim();
                        var url = window.location.pathname.replace(/\/$/, '') + '/delete/' + encodeURIComponent(firstColVal);
                        
                        $.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    tableInstance.row(rowToDelete).remove().draw();
                                    showToast(response.message || 'Data berhasil dihapus!', 'success');
                                    closeModal();
                                    rowToDelete = null;
                                    setTimeout(function() { window.location.reload(); }, 800);
                                } else {
                                    showToast(response.message || 'Gagal menghapus data!', 'danger');
                                }
                            },
                            error: function() {
                                showToast('Koneksi atau server bermasalah!', 'danger');
                            }
                        });
                    }
                });

                // Handle click Edit (Pencil)
                var rowToEdit = null;
                $('body').on('click', '.modern-table tbody tr td button:has(.fa-pen-to-square), .modern-table tbody tr td .btn-prototype-edit, .modern-table tbody tr td button.panel-action[style*="green"]', function(e) {
                    e.preventDefault();
                    rowToEdit = $(this).closest('tr');
                    var data = tableInstance.row(rowToEdit).data();
                    var headers = [];
                    $('.modern-table th').each(function() { headers.push($(this).text().trim()); });

                    var formHtml = `<div style="display: flex; flex-direction: column; gap: 12px;">`;
                    for (var i = 0; i < headers.length; i++) {
                        if (headers[i].toLowerCase() === 'aksi' || headers[i].toLowerCase() === 'action') continue;
                        var rawCell = data[i] || '-';
                        var cleanCell = $('<div>').html(rawCell).text().trim(); // Strip HTML
                        formHtml += getInputHtml(headers[i], cleanCell);
                    }
                    formHtml += `</div>`;

                    $('#global-modal-title').html('<i class="fa-solid fa-pen-to-square" style="color: #198754;"></i> Edit Data');
                    $('#global-modal-body').html(formHtml);
                    $('#global-modal-footer').html(`
                        <button class="btn-secondary btn-close-modal" style="padding: 8px 16px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer;">Batal</button>
                        <button id="btn-save-edit-action" class="btn-primary" style="padding: 8px 16px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer;">Simpan Perubahan</button>
                    `);
                    $('#global-crud-modal').css('display', 'flex').addClass('active');
                });

                $('body').on('click', '#btn-save-edit-action', function() {
                    if (rowToEdit) {
                        var headers = [];
                        $('.modern-table th').each(function() { headers.push($(this).text().trim()); });
                        var data = tableInstance.row(rowToEdit).data();
                        var firstColVal = $('<div>').html(data[0]).text().trim();
                        var url = window.location.pathname.replace(/\/$/, '') + '/update/' + encodeURIComponent(firstColVal);
                        
                        var postData = {};
                        for (var i = 0; i < headers.length; i++) {
                            if (headers[i].toLowerCase() === 'aksi' || headers[i].toLowerCase() === 'action') continue;
                            var id = 'modal-input-' + headers[i].replace(/[^a-zA-Z0-9]/g, '-');
                            var val = $('#' + id).val() || '';
                            postData[headers[i]] = val;
                        }

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: postData,
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    var newData = [];
                                    for (var i = 0; i < headers.length; i++) {
                                        if (headers[i].toLowerCase() === 'aksi' || headers[i].toLowerCase() === 'action') {
                                            newData.push(actionButtonsHtml);
                                        } else {
                                            var id = 'modal-input-' + headers[i].replace(/[^a-zA-Z0-9]/g, '-');
                                            var val = $('#' + id).val() || '';
                                            newData.push(formatCell(headers[i], val));
                                        }
                                    }
                                    tableInstance.row(rowToEdit).data(newData).draw(false);
                                    showToast(response.message || 'Data berhasil disimpan!', 'success');
                                    closeModal();
                                    rowToEdit = null;
                                    setTimeout(function() { window.location.reload(); }, 800);
                                } else {
                                    showToast(response.message || 'Gagal memperbarui data!', 'danger');
                                }
                            },
                            error: function() {
                                showToast('Koneksi atau server bermasalah!', 'danger');
                            }
                        });
                    }
                });

                // Handle click Add New Button (Plus)
                $('body').on('click', '.btn-primary:has(.fa-plus), button:has(.fa-plus), .btn-primary:contains("Tambah"), .btn-primary:contains("Baru"), .header-actions-group button.btn-primary', function(e) {
                    if ($(this).attr('onclick') && $(this).attr('onclick').includes('export')) {
                        return; // Let the export functions run normally
                    }
                    if ($('.modern-table').length === 0) return;
                    e.preventDefault();
                    
                    var headers = [];
                    $('.modern-table th').each(function() { headers.push($(this).text().trim()); });

                    var formHtml = `<div style="display: flex; flex-direction: column; gap: 12px;">`;
                    for (var i = 0; i < headers.length; i++) {
                        if (headers[i].toLowerCase() === 'aksi' || headers[i].toLowerCase() === 'action') continue;
                        formHtml += getInputHtml(headers[i]);
                    }
                    formHtml += `</div>`;

                    $('#global-modal-title').html('<i class="fa-solid fa-circle-plus" style="color: var(--accent-color);"></i> Tambah Data Baru');
                    $('#global-modal-body').html(formHtml);
                    $('#global-modal-footer').html(`
                        <button class="btn-secondary btn-close-modal" style="padding: 8px 16px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer;">Batal</button>
                        <button id="btn-save-add-action" class="btn-primary" style="padding: 8px 16px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer;">Simpan</button>
                    `);
                    $('#global-crud-modal').css('display', 'flex').addClass('active');
                });

                $('body').on('click', '#btn-save-add-action', function() {
                    var headers = [];
                    $('.modern-table th').each(function() { headers.push($(this).text().trim()); });
                    var url = window.location.pathname.replace(/\/$/, '') + '/create';
                    
                    var postData = {};
                    for (var i = 0; i < headers.length; i++) {
                        if (headers[i].toLowerCase() === 'aksi' || headers[i].toLowerCase() === 'action') continue;
                        var id = 'modal-input-' + headers[i].replace(/[^a-zA-Z0-9]/g, '-');
                        var val = $('#' + id).val() || '';
                        postData[headers[i]] = val;
                    }

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: postData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                var newData = [];
                                for (var i = 0; i < headers.length; i++) {
                                    if (headers[i].toLowerCase() === 'aksi' || headers[i].toLowerCase() === 'action') {
                                        newData.push(actionButtonsHtml);
                                    } else {
                                        var id = 'modal-input-' + headers[i].replace(/[^a-zA-Z0-9]/g, '-');
                                        var val = $('#' + id).val() || '';
                                        newData.push(formatCell(headers[i], val));
                                    }
                                }
                                tableInstance.row.add(newData).draw(false);
                                showToast(response.message || 'Data baru berhasil disimpan!', 'success');
                                closeModal();
                                setTimeout(function() { window.location.reload(); }, 800);
                            } else {
                                showToast(response.message || 'Gagal menyimpan data baru!', 'danger');
                            }
                        },
                        error: function() {
                            showToast('Koneksi atau server bermasalah!', 'danger');
                        }
                    });
                });
            }
        });
    </script>
    
    <!-- Scripts from specific views -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
