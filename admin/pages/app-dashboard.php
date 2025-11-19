<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || ($_SESSION['admin_role'] !== 'super_admin' && $_SESSION['admin_role'] !== 'app_admin')) {
    header('Location: ../login.php');
    exit();
}

require_once '../../config/database.php';

// Get comprehensive statistics
$stats = [];

// Admin statistics
$stats['total_admins'] = $conn->query("SELECT COUNT(*) as count FROM admin")->fetch_assoc()['count'];
$stats['super_admins'] = $conn->query("SELECT COUNT(*) as count FROM admin WHERE role='super_admin'")->fetch_assoc()['count'];
$stats['app_admins'] = $conn->query("SELECT COUNT(*) as count FROM admin WHERE role='app_admin'")->fetch_assoc()['count'];
$stats['website_admins'] = $conn->query("SELECT COUNT(*) as count FROM admin WHERE role='admin'")->fetch_assoc()['count'];

// Content statistics
$stats['total_products'] = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$stats['active_products'] = $conn->query("SELECT COUNT(*) as count FROM products WHERE is_active=1")->fetch_assoc()['count'];
$stats['total_hero_slides'] = $conn->query("SELECT COUNT(*) as count FROM hero_slides")->fetch_assoc()['count'];
$stats['active_hero_slides'] = $conn->query("SELECT COUNT(*) as count FROM hero_slides WHERE is_active=1")->fetch_assoc()['count'];

// Gallery statistics
$stats['total_photos'] = $conn->query("SELECT COUNT(*) as count FROM gallery_photos")->fetch_assoc()['count'];
$stats['active_photos'] = $conn->query("SELECT COUNT(*) as count FROM gallery_photos WHERE is_active=1")->fetch_assoc()['count'];
$stats['total_videos'] = $conn->query("SELECT COUNT(*) as count FROM gallery_videos")->fetch_assoc()['count'];
$stats['active_videos'] = $conn->query("SELECT COUNT(*) as count FROM gallery_videos WHERE is_active=1")->fetch_assoc()['count'];

// Message statistics
$stats['total_messages'] = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
$stats['unread_messages'] = $conn->query("SELECT COUNT(*) as count FROM messages WHERE is_read=0")->fetch_assoc()['count'];
$stats['read_messages'] = $conn->query("SELECT COUNT(*) as count FROM messages WHERE is_read=1")->fetch_assoc()['count'];

// Recent activities
$recent_admins = $conn->query("SELECT username, role, created_at FROM admin ORDER BY created_at DESC LIMIT 5");
$recent_messages = $conn->query("SELECT name, email, created_at FROM messages ORDER BY created_at DESC LIMIT 5");
$recent_products = $conn->query("SELECT name, price, created_at FROM products ORDER BY created_at DESC LIMIT 5");

// Activity by month (messages)
$monthly_messages = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
    FROM messages 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY month 
    ORDER BY month ASC
");

// System info
$db_size_result = $conn->query("
    SELECT 
        ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb
    FROM information_schema.tables 
    WHERE table_schema = 'curupwater'
");
$db_size = $db_size_result->fetch_assoc()['size_mb'];

// Get PHP version and other system info
$php_version = phpversion();
$server_software = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
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
                    <h1><i class="fas fa-chart-line"></i> Dashboard Aplikasi</h1>
                    <p>Statistik & monitoring sistem Curup Water</p>
                </div>
                <div class="header-actions">
                    <span class="last-update"><i class="fas fa-clock"></i> Update: <?= date('d/m/Y H:i') ?></span>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_admins'] ?></h3>
                        <p>Total Administrator</p>
                        <small><?= $stats['super_admins'] ?> Super | <?= $stats['app_admins'] ?> App | <?= $stats['website_admins'] ?> Web</small>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_products'] ?></h3>
                        <p>Total Produk</p>
                        <small><?= $stats['active_products'] ?> Aktif | <?= $stats['total_products'] - $stats['active_products'] ?> Non-aktif</small>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_messages'] ?></h3>
                        <p>Total Pesan</p>
                        <small><?= $stats['unread_messages'] ?> Belum Dibaca</small>
                    </div>
                </div>

                <div class="stat-card info">
                    <div class="stat-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_photos'] + $stats['total_videos'] ?></h3>
                        <p>Media Gallery</p>
                        <small><?= $stats['total_photos'] ?> Foto | <?= $stats['total_videos'] ?> Video</small>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="chart-grid">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-bar"></i> Statistik Konten</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="contentChart" height="80"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-line"></i> Pesan 6 Bulan Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="messagesChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Activity Tables -->
            <div class="activity-grid">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-clock"></i> Admin Terbaru</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($admin = $recent_admins->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($admin['username']) ?></strong></td>
                                    <td>
                                        <?php if ($admin['role'] === 'super_admin'): ?>
                                            <span class="badge badge-gold">Super Admin</span>
                                        <?php elseif ($admin['role'] === 'app_admin'): ?>
                                            <span class="badge badge-orange">Admin Aplikasi</span>
                                        <?php else: ?>
                                            <span class="badge badge-blue">Admin Website</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($admin['created_at'])) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-envelope-open-text"></i> Pesan Terbaru</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($msg = $recent_messages->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($msg['name']) ?></strong></td>
                                    <td><?= htmlspecialchars($msg['email']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-server"></i> Informasi Sistem</h3>
                </div>
                <div class="card-body">
                    <div class="system-info-grid">
                        <div class="info-item">
                            <i class="fas fa-database"></i>
                            <div>
                                <strong>Database Size</strong>
                                <p><?= $db_size ?> MB</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fab fa-php"></i>
                            <div>
                                <strong>PHP Version</strong>
                                <p><?= $php_version ?></p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-server"></i>
                            <div>
                                <strong>Web Server</strong>
                                <p><?= $server_software ?></p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <div>
                                <strong>Server Time</strong>
                                <p><?= date('d M Y, H:i:s') ?></p>
                            </div>
                        </div>
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
    </style>

    <script>
        // Content Statistics Chart
        const contentCtx = document.getElementById('contentChart').getContext('2d');
        new Chart(contentCtx, {
            type: 'bar',
            data: {
                labels: ['Produk', 'Hero Slides', 'Foto Gallery', 'Video Gallery'],
                datasets: [{
                    label: 'Total',
                    data: [<?= $stats['total_products'] ?>, <?= $stats['total_hero_slides'] ?>, <?= $stats['total_photos'] ?>, <?= $stats['total_videos'] ?>],
                    backgroundColor: '#3b82f6'
                }, {
                    label: 'Aktif',
                    data: [<?= $stats['active_products'] ?>, <?= $stats['active_hero_slides'] ?>, <?= $stats['active_photos'] ?>, <?= $stats['active_videos'] ?>],
                    backgroundColor: '#10b981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Messages Chart
        const messagesCtx = document.getElementById('messagesChart').getContext('2d');
        const messageData = [
            <?php 
            $months = [];
            $counts = [];
            while ($row = $monthly_messages->fetch_assoc()) {
                $months[] = "'" . date('M Y', strtotime($row['month'] . '-01')) . "'";
                $counts[] = $row['count'];
            }
            ?>
        ];
        
        new Chart(messagesCtx, {
            type: 'line',
            data: {
                labels: [<?= implode(',', $months) ?>],
                datasets: [{
                    label: 'Pesan Masuk',
                    data: [<?= implode(',', $counts) ?>],
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
