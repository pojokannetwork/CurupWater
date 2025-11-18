<?php
session_start();

require_once __DIR__ . '/../includes/Admin.php';

// Check login
if (!Admin::isLoggedIn()) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../includes/Product.php';

$product = new Product();
$message = '';
$message_type = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $product->id = $_GET['delete'];
    
    // Delete image file if exists
    if ($product->readOne() && !empty($product->image)) {
        $image_path = __DIR__ . '/../../img/products/' . $product->image;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    if ($product->delete()) {
        $message = 'Produk berhasil dihapus!';
        $message_type = 'success';
    } else {
        $message = 'Gagal menghapus produk!';
        $message_type = 'danger';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product->name = $_POST['name'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];
    $product->stock = $_POST['stock'];
    $product->is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle image upload
    $image_name = $_POST['old_image'] ?? '';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $new_filename = uniqid() . '_' . time() . '.' . $filetype;
            $upload_path = __DIR__ . '/../../img/products/' . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Delete old image
                if (!empty($image_name)) {
                    $old_path = __DIR__ . '/../../img/products/' . $image_name;
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                }
                $image_name = $new_filename;
            }
        }
    }
    
    $product->image = $image_name;
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $product->id = $_POST['id'];
        if ($product->update()) {
            $message = 'Produk berhasil diupdate!';
            $message_type = 'success';
        } else {
            $message = 'Gagal mengupdate produk!';
            $message_type = 'danger';
        }
    } else {
        // Create
        if ($product->create()) {
            $message = 'Produk berhasil ditambahkan!';
            $message_type = 'success';
        } else {
            $message = 'Gagal menambahkan produk!';
            $message_type = 'danger';
        }
    }
}

// Get all products
$stmt = $product->read();

// Check if edit mode
$edit_mode = false;
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $product->id = $_GET['edit'];
    if ($product->readOne()) {
        $edit_product = $product;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - CurupWater Admin</title>
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
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
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
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="products.php">
                            <i class="fas fa-box me-2"></i>Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="features.php">
                            <i class="fas fa-star me-2"></i>Keunggulan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">
                            <i class="fas fa-info-circle me-2"></i>Tentang Kami
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">
                            <i class="fas fa-phone me-2"></i>Kontak
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hero.php">
                            <i class="fas fa-image me-2"></i>Hero Section
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link" href="../../index.php" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>Lihat Website
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php" onclick="return confirm('Yakin ingin logout?')">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-box me-2"></i>Manajemen Produk</h2>
                    <a href="../index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Form Add/Edit -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-<?php echo $edit_mode ? 'edit' : 'plus'; ?> me-2"></i>
                            <?php echo $edit_mode ? 'Edit Produk' : 'Tambah Produk Baru'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <?php if ($edit_mode): ?>
                                <input type="hidden" name="id" value="<?php echo $edit_product->id; ?>">
                                <input type="hidden" name="old_image" value="<?php echo $edit_product->image; ?>">
                            <?php endif; ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Produk *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?php echo $edit_mode ? htmlspecialchars($edit_product->name) : ''; ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="price" class="form-label">Harga (Rp) *</label>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01"
                                           value="<?php echo $edit_mode ? $edit_product->price : ''; ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="stock" class="form-label">Stok *</label>
                                    <input type="number" class="form-control" id="stock" name="stock" 
                                           value="<?php echo $edit_mode ? $edit_product->stock : '0'; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo $edit_mode ? htmlspecialchars($edit_product->description) : ''; ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="image" class="form-label">Gambar Produk</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="text-muted">Format: JPG, JPEG, PNG, GIF (Max 2MB)</small>
                                    <?php if ($edit_mode && $edit_product->image): ?>
                                        <div class="mt-2">
                                            <img src="../../img/products/<?php echo $edit_product->image; ?>" alt="Current" class="product-image">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               <?php echo ($edit_mode && $edit_product->is_active) || !$edit_mode ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_active">Aktif</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i><?php echo $edit_mode ? 'Update' : 'Simpan'; ?>
                                </button>
                                <?php if ($edit_mode): ?>
                                    <a href="products.php" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Products List -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $stmt->fetch()): ?>
                                    <tr>
                                        <td>
                                            <?php if ($row['image']): ?>
                                                <img src="../../img/products/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image">
                                            <?php else: ?>
                                                <div class="product-image bg-secondary d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image text-white"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo substr(htmlspecialchars($row['description']), 0, 50) . '...'; ?></td>
                                        <td>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                                        <td><?php echo $row['stock']; ?></td>
                                        <td>
                                            <?php if ($row['is_active']): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="products.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="products.php?delete=<?php echo $row['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
