<?php
session_start();

require_once __DIR__ . '/includes/Admin.php';

// Check login
if (!Admin::isLoggedIn()) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/includes/Product.php';
require_once __DIR__ . '/includes/Feature.php';

// Get statistics
$product = new Product();
$feature = new Feature();

$products_stmt = $product->read();
$total_products = $products_stmt->rowCount();

$features_stmt = $feature->read();
$total_features = $features_stmt->rowCount();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CurupWater</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .stat-card {
            border-radius: 15px;
            padding: 25px;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card i {
            font-size: 3rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar p-3">
                <div class="text-center mb-4">
                    <i class="fas fa-water fa-3x mb-2"></i>
                    <h4>CurupWater</h4>
                    <small>Admin Panel</small>
                </div>
                <hr class="bg-light">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/products.php">
                            <i class="fas fa-box me-2"></i>Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/features.php">
                            <i class="fas fa-star me-2"></i>Keunggulan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/about.php">
                            <i class="fas fa-info-circle me-2"></i>Tentang Kami
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/contact.php">
                            <i class="fas fa-phone me-2"></i>Kontak
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/hero.php">
                            <i class="fas fa-image me-2"></i>Hero Section
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link" href="../index.php" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>Lihat Website
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-chart-line me-2"></i>Dashboard</h2>
                    <div class="text-muted">
                        <i class="fas fa-user me-2"></i><?php echo $_SESSION['admin_username']; ?>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">Total Produk</h6>
                                        <h2 class="mb-0"><?php echo $total_products; ?></h2>
                                    </div>
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">Total Keunggulan</h6>
                                        <h2 class="mb-0"><?php echo $total_features; ?></h2>
                                    </div>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">Status</h6>
                                        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Active</h5>
                                    </div>
                                    <i class="fas fa-circle-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="pages/products.php?action=add" class="btn btn-primary w-100">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Produk
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="pages/features.php?action=add" class="btn btn-success w-100">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Keunggulan
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="pages/about.php" class="btn btn-info w-100 text-white">
                                    <i class="fas fa-edit me-2"></i>Edit Tentang Kami
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="pages/contact.php" class="btn btn-warning w-100">
                                    <i class="fas fa-edit me-2"></i>Edit Kontak
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Welcome Message -->
                <div class="card shadow-sm mt-4">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-water fa-5x text-primary mb-3"></i>
                        <h3>Selamat Datang di Dashboard Admin CurupWater</h3>
                        <p class="text-muted">Kelola konten website Anda dengan mudah melalui panel admin ini</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
