<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/database.php';

// Get statistics
$stats = [];
$stats['products'] = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$stats['messages'] = $conn->query("SELECT COUNT(*) as count FROM messages WHERE is_read = 0")->fetch_assoc()['count'];
$stats['hero_slides'] = $conn->query("SELECT COUNT(*) as count FROM hero_slides")->fetch_assoc()['count'];

// Get recent messages
$recent_messages = $conn->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Curup Water</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../assets/img/logo.svg" alt="Curup Water" class="sidebar-logo">
                <h2>CURUP WATER</h2>
                <?php if (isset($_SESSION['admin_role'])): ?>
                    <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
                        <span class="role-badge super-admin">SUPER ADMIN</span>
                    <?php elseif ($_SESSION['admin_role'] === 'app_admin'): ?>
                        <span class="role-badge app-admin">ADMIN APLIKASI</span>
                    <?php else: ?>
                        <span class="role-badge admin">ADMIN</span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <div class="nav-divider">MANAGEMEN WEBSITE</div>
                <a href="pages/hero.php" class="nav-item">
                    <i class="fas fa-images"></i>
                    <span>Hero Slides</span>
                </a>
                <a href="pages/products.php" class="nav-item">
                    <i class="fas fa-box"></i>
                    <span>Produk</span>
                </a>
                <a href="pages/about.php" class="nav-item">
                    <i class="fas fa-info-circle"></i>
                    <span>Tentang Kami</span>
                </a>
                <a href="pages/gallery-photos.php" class="nav-item">
                    <i class="fas fa-camera"></i>
                    <span>Galeri Foto</span>
                </a>
                <a href="pages/gallery-videos.php" class="nav-item">
                    <i class="fas fa-video"></i>
                    <span>Galeri Video</span>
                </a>
                <a href="pages/contact.php" class="nav-item">
                    <i class="fas fa-address-book"></i>
                    <span>Kontak</span>
                </a>
                <a href="pages/messages.php" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    <span>Pesan</span>
                    <?php if($stats['messages'] > 0): ?>
                    <span class="badge"><?php echo $stats['messages']; ?></span>
                    <?php endif; ?>
                </a>
                <?php if (isset($_SESSION['admin_role']) && ($_SESSION['admin_role'] === 'super_admin' || $_SESSION['admin_role'] === 'app_admin')): ?>
                <div class="nav-divider">MANAGEMEN APLIKASI</div>
                <a href="pages/app-management.php" class="nav-item">
                    <i class="fas fa-cogs"></i>
                    <span>Kelola Admin</span>
                </a>
                <?php endif; ?>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="page-title">
                    <h1>Dashboard</h1>
                    <p>Selamat datang, <strong><?= $_SESSION['admin_username'] ?></strong>! 
                    <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
                        <span class="role-badge-small super-admin"><i class="fas fa-crown"></i> Super Admin</span>
                    <?php elseif ($_SESSION['admin_role'] === 'app_admin'): ?>
                        <span class="role-badge-small app-admin"><i class="fas fa-user-cog"></i> Admin Aplikasi</span>
                    <?php else: ?>
                        <span class="role-badge-small admin"><i class="fas fa-user"></i> Admin</span>
                    <?php endif; ?>
                    </p>
                </div>
                <div class="top-bar-actions">
                    <a href="../index.php" class="btn btn-sm" target="_blank">
                        <i class="fas fa-eye"></i> Lihat Website
                    </a>
                    <a href="logout.php" class="btn btn-sm btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #3b82f6;">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['products']; ?></h3>
                            <p>Total Produk</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #10b981;">
                            <i class="fas fa-images"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['hero_slides']; ?></h3>
                            <p>Hero Slides</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #f59e0b;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['messages']; ?></h3>
                            <p>Pesan Baru</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="card">
                    <div class="card-header">
                        <h2>Pesan Terbaru</h2>
                        <a href="pages/messages.php" class="btn btn-sm">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <?php if ($recent_messages->num_rows > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Subjek</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($msg = $recent_messages->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($msg['created_at'])); ?></td>
                                    <td>
                                        <?php if($msg['is_read']): ?>
                                        <span class="badge badge-success">Sudah Dibaca</span>
                                        <?php else: ?>
                                        <span class="badge badge-warning">Belum Dibaca</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p class="text-center text-muted">Belum ada pesan</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
