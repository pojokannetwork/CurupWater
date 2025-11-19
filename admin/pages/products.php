<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../../config/database.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = $conn->real_escape_string($_POST['name']);
            $description = $conn->real_escape_string($_POST['description']);
            $size = $conn->real_escape_string($_POST['size']);
            $price = (float)$_POST['price'];
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $order_num = (int)$_POST['order_num'];
            
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $image = time() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], '../../assets/img/products/' . $image);
            }
            
            $sql = "INSERT INTO products (name, description, size, price, image, is_featured, is_active, order_num) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdssii", $name, $description, $size, $price, $image, $is_featured, $is_active, $order_num);
            
            if ($stmt->execute()) {
                $success = "Produk berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan produk!";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = (int)$_POST['id'];
            $conn->query("DELETE FROM products WHERE id = $id");
            $success = "Produk berhasil dihapus!";
        }
    }
}

// Get all products
$products = $conn->query("SELECT * FROM products ORDER BY order_num ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin Curup Water</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../assets/img/logo.svg" alt="Curup Water" class="sidebar-logo">
                <h2>CURUP WATER</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="../index.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="hero.php" class="nav-item">
                    <i class="fas fa-images"></i>
                    <span>Hero Slides</span>
                </a>
                <a href="products.php" class="nav-item active">
                    <i class="fas fa-box"></i>
                    <span>Produk</span>
                </a>
                <a href="about.php" class="nav-item">
                    <i class="fas fa-info-circle"></i>
                    <span>Tentang Kami</span>
                </a>
                <a href="contact.php" class="nav-item">
                    <i class="fas fa-address-book"></i>
                    <span>Kontak</span>
                </a>
                <a href="messages.php" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    <span>Pesan</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="page-title">
                    <h1>Kelola Produk</h1>
                </div>
                <div class="top-bar-actions">
                    <a href="../../index.php#products" class="btn btn-sm" target="_blank">
                        <i class="fas fa-eye"></i> Lihat Website
                    </a>
                    <a href="../logout.php" class="btn btn-sm btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Add New Product -->
                <div class="card">
                    <div class="card-header">
                        <h2>Tambah Produk Baru</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add">
                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Ukuran</label>
                                <input type="text" name="size" placeholder="19 Liter" required>
                            </div>
                            <div class="form-group">
                                <label>Harga (Rp)</label>
                                <input type="number" name="price" min="0" step="100" required>
                            </div>
                            <div class="form-group">
                                <label>Gambar</label>
                                <input type="file" name="image" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="order_num" value="0">
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" name="is_featured">
                                    <label>Produk Unggulan</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" checked>
                                    <label>Aktif</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Products List -->
                <div class="card">
                    <div class="card-header">
                        <h2>Daftar Produk</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($products->num_rows > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Urutan</th>
                                    <th>Gambar</th>
                                    <th>Nama</th>
                                    <th>Ukuran</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($product = $products->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $product['order_num']; ?></td>
                                    <td>
                                        <?php if($product['image']): ?>
                                        <img src="../../assets/img/products/<?php echo $product['image']; ?>" style="height: 40px; border-radius: 5px;">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($product['name']); ?>
                                        <?php if($product['is_featured']): ?>
                                        <span class="badge badge-warning">Unggulan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['size']); ?></td>
                                    <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                    <td>
                                        <?php if($product['is_active']): ?>
                                        <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-buttons">
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Hapus produk ini?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger btn-icon">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p class="text-center text-muted">Belum ada produk</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
