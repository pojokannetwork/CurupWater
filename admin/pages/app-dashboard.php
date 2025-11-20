<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || ($_SESSION['admin_role'] !== 'super_admin' && $_SESSION['admin_role'] !== 'app_admin')) {
    header('Location: ../login.php');
    exit();
}

require_once '../../config/database.php';

// Get sales statistics
$stats = [];

// Sales & Revenue
$today_sales = $conn->query("SELECT COALESCE(SUM(total_price), 0) as total FROM sales WHERE DATE(sale_date) = CURDATE()")->fetch_assoc()['total'];
$month_sales = $conn->query("SELECT COALESCE(SUM(total_price), 0) as total FROM sales WHERE MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())")->fetch_assoc()['total'];
$year_sales = $conn->query("SELECT COALESCE(SUM(total_price), 0) as total FROM sales WHERE YEAR(sale_date) = YEAR(CURDATE())")->fetch_assoc()['total'];
$total_sales = $conn->query("SELECT COALESCE(SUM(total_price), 0) as total FROM sales")->fetch_assoc()['total'];

// Transaction count
$today_transactions = $conn->query("SELECT COUNT(*) as count FROM sales WHERE DATE(sale_date) = CURDATE()")->fetch_assoc()['count'];
$month_transactions = $conn->query("SELECT COUNT(*) as count FROM sales WHERE MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())")->fetch_assoc()['count'];
$total_transactions = $conn->query("SELECT COUNT(*) as count FROM sales")->fetch_assoc()['count'];

// Product & Stock
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$low_stock_products = $conn->query("SELECT COUNT(*) as count FROM product_stock WHERE stock_quantity <= min_stock")->fetch_assoc()['count'];
$out_of_stock = $conn->query("SELECT COUNT(*) as count FROM product_stock WHERE stock_quantity = 0")->fetch_assoc()['count'];

// Top selling products
$top_products = $conn->query("
    SELECT p.name, SUM(s.quantity) as total_qty, SUM(s.total_price) as total_sales 
    FROM sales s 
    JOIN products p ON s.product_id = p.id 
    GROUP BY s.product_id 
    ORDER BY total_sales DESC 
    LIMIT 5
");

// Recent sales
$recent_sales = $conn->query("
    SELECT s.*, p.name as product_name 
    FROM sales s 
    JOIN products p ON s.product_id = p.id 
    ORDER BY s.created_at DESC 
    LIMIT 10
");

// Low stock alert
$low_stock_list = $conn->query("
    SELECT p.name, ps.stock_quantity, ps.min_stock 
    FROM product_stock ps 
    JOIN products p ON ps.product_id = p.id 
    WHERE ps.stock_quantity <= ps.min_stock 
    ORDER BY ps.stock_quantity ASC 
    LIMIT 5
");

// Monthly sales trend (last 6 months)
$monthly_sales = $conn->query("
    SELECT DATE_FORMAT(sale_date, '%Y-%m') as month, 
           SUM(total_price) as revenue,
           COUNT(*) as transactions
    FROM sales 
    WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY month 
    ORDER BY month ASC
");

// Sales by product category (if you have categories, otherwise by product)
$sales_by_product = $conn->query("
    SELECT p.name, SUM(s.total_price) as revenue 
    FROM sales s 
    JOIN products p ON s.product_id = p.id 
    WHERE MONTH(s.sale_date) = MONTH(CURDATE()) 
    GROUP BY p.id 
    ORDER BY revenue DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Aplikasi - Curup Water</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../assets/img/logo.svg" alt="Curup Water Logo" class="sidebar-logo">
                <h2>Curup Water</h2>
                <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
                    <span class="role-badge super-admin">SUPER ADMIN</span>
                <?php elseif ($_SESSION['admin_role'] === 'app_admin'): ?>
                    <span class="role-badge app-admin">ADMIN APLIKASI</span>
                <?php endif; ?>
            </div>
            <nav class="sidebar-nav">
                <a href="../index.php"><i class="fas fa-home"></i> Dashboard</a>
                <div class="nav-divider">MANAGEMEN WEBSITE</div>
                <a href="hero.php"><i class="fas fa-image"></i> Hero Slider</a>
                <a href="products.php"><i class="fas fa-box"></i> Produk</a>
                <a href="about.php"><i class="fas fa-info-circle"></i> Tentang</a>
                <div class="nav-group">
                    <a href="#" class="nav-group-title"><i class="fas fa-images"></i> Galeri <i class="fas fa-chevron-down"></i></a>
                    <div class="nav-group-content">
                        <a href="gallery-photos.php"><i class="fas fa-camera"></i> Foto</a>
                        <a href="gallery-videos.php"><i class="fas fa-video"></i> Video</a>
                    </div>
                </div>
                <a href="contact.php"><i class="fas fa-phone"></i> Kontak</a>
                <a href="messages.php"><i class="fas fa-envelope"></i> Pesan</a>
                <div class="nav-divider">MANAGEMEN APLIKASI</div>
                <a href="app-dashboard.php" class="active"><i class="fas fa-chart-line"></i> Dashboard Aplikasi</a>
                <a href="app-management.php"><i class="fas fa-cogs"></i> Kelola Admin</a>
                <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <div>
                    <h1><i class="fas fa-chart-line"></i> Dashboard Analytics</h1>
                    <p>Monitoring penjualan, stok, dan performa bisnis Curup Water</p>
                </div>
                <div class="header-actions">
                    <span class="last-update"><i class="fas fa-sync-alt"></i> Update: <?= date('d/m/Y H:i') ?></span>
                </div>
            </div>

            <!-- Revenue Summary Cards -->
            <div class="stats-grid">
                <div class="stat-card revenue">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Rp <?= number_format($today_sales, 0, ',', '.') ?></h3>
                        <p>Penjualan Hari Ini</p>
                        <small><?= $today_transactions ?> Transaksi</small>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Rp <?= number_format($month_sales, 0, ',', '.') ?></h3>
                        <p>Penjualan Bulan Ini</p>
                        <small><?= $month_transactions ?> Transaksi</small>
                    </div>
                </div>

                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Rp <?= number_format($year_sales, 0, ',', '.') ?></h3>
                        <p>Penjualan Tahun Ini</p>
                        <small><?= date('Y') ?></small>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $total_products ?></h3>
                        <p>Total Produk</p>
                        <small class="text-danger"><?= $low_stock_products ?> Stok Menipis | <?= $out_of_stock ?> Habis</small>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="chart-grid">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-area"></i> Trend Penjualan 6 Bulan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="salesTrendChart" height="80"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-pie"></i> Penjualan Per Produk (Bulan Ini)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="productSalesChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Activity Tables -->
            <div class="activity-grid">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-trophy"></i> Produk Terlaris</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Qty Terjual</th>
                                    <th>Total Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($top_products->num_rows > 0): ?>
                                    <?php while ($product = $top_products->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($product['name']) ?></strong></td>
                                        <td><?= number_format($product['total_qty']) ?> unit</td>
                                        <td><span class="text-success">Rp <?= number_format($product['total_sales'], 0, ',', '.') ?></span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada data penjualan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-exclamation-triangle"></i> Alert Stok Menipis</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Stok</th>
                                    <th>Min. Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($low_stock_list->num_rows > 0): ?>
                                    <?php while ($item = $low_stock_list->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                                        <td>
                                            <span class="badge <?= $item['stock_quantity'] == 0 ? 'badge-danger' : 'badge-warning' ?>">
                                                <?= $item['stock_quantity'] ?> unit
                                            </span>
                                        </td>
                                        <td><?= $item['min_stock'] ?> unit</td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-success">✓ Semua stok aman</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-receipt"></i> Transaksi Terbaru</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Pelanggan</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recent_sales->num_rows > 0): ?>
                                    <?php while ($sale = $recent_sales->fetch_assoc()): ?>
                                    <tr>
                                        <td>#<?= $sale['id'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($sale['sale_date'])) ?></td>
                                        <td><strong><?= htmlspecialchars($sale['product_name']) ?></strong></td>
                                        <td><?= htmlspecialchars($sale['customer_name']) ?></td>
                                        <td><?= $sale['quantity'] ?> unit</td>
                                        <td><span class="text-success">Rp <?= number_format($sale['total_price'], 0, ',', '.') ?></span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada transaksi</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <style>
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
        }
        
        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
        }
        
        .system-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .info-item i {
            font-size: 2rem;
            color: #2563eb;
        }
        
        .info-item strong {
            display: block;
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        
        .info-item p {
            margin: 0;
            color: #1e293b;
            font-weight: 600;
        }
        
        .last-update {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .badge-gold { background: #fbbf24; color: #78350f; }
        .badge-orange { background: #fb923c; color: white; }
        .badge-blue { background: #3b82f6; color: white; }
        .badge-warning { background: #fef3c7; color: #92400e; font-weight: 600; }
        .badge-danger { background: #fee2e2; color: #991b1b; font-weight: 600; }
        
        .stat-card.primary .stat-icon { background: #dbeafe; color: #1e40af; }
        .stat-card.success .stat-icon { background: #d1fae5; color: #065f46; }
        .stat-card.warning .stat-icon { background: #fef3c7; color: #92400e; }
        .stat-card.info .stat-icon { background: #e0e7ff; color: #3730a3; }
        
        .stat-card small {
            display: block;
            margin-top: 0.5rem;
            color: #64748b;
            font-size: 0.85rem;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .text-success {
            color: #10b981;
            font-weight: 600;
        }
        
        .text-muted {
            color: #9ca3af;
        }
        
        .text-center {
            text-align: center;
        }
    </style>

    <script>
        // Sales Trend Chart (6 Months)
        const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
        <?php 
        $months_labels = [];
        $revenue_data = [];
        $transactions_data = [];
        while ($row = $monthly_sales->fetch_assoc()) {
            $months_labels[] = "'" . date('M Y', strtotime($row['month'] . '-01')) . "'";
            $revenue_data[] = $row['revenue'];
            $transactions_data[] = $row['transactions'];
        }
        ?>
        
        new Chart(salesTrendCtx, {
            type: 'line',
            data: {
                labels: [<?= implode(',', $months_labels) ?>],
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: [<?= implode(',', $revenue_data) ?>],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                }, {
                    label: 'Transaksi',
                    data: [<?= implode(',', $transactions_data) ?>],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    if (context.datasetIndex === 0) {
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    } else {
                                        label += context.parsed.y + ' transaksi';
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', {notation: 'compact'}).format(value);
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                    },
                }
            }
        });

        // Product Sales Chart (Doughnut Chart)
        const productSalesCtx = document.getElementById('productSalesChart').getContext('2d');
        <?php 
        $product_labels = [];
        $product_revenues = [];
        while ($row = $sales_by_product->fetch_assoc()) {
            $product_labels[] = "'" . htmlspecialchars($row['name']) . "'";
            $product_revenues[] = $row['revenue'];
        }
        ?>
        
        new Chart(productSalesCtx, {
            type: 'doughnut',
            data: {
                labels: [<?= implode(',', $product_labels) ?>],
                datasets: [{
                    label: 'Penjualan',
                    data: [<?= implode(',', $product_revenues) ?>],
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
