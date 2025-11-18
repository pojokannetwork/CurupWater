<?php
session_start();

require_once __DIR__ . '/../includes/Admin.php';

// Check login
if (!Admin::isLoggedIn()) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../includes/Feature.php';

$feature = new Feature();
$message = '';
$message_type = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $feature->id = $_GET['delete'];
    if ($feature->delete()) {
        $message = 'Keunggulan berhasil dihapus!';
        $message_type = 'success';
    } else {
        $message = 'Gagal menghapus keunggulan!';
        $message_type = 'danger';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $feature->title = $_POST['title'];
    $feature->description = $_POST['description'];
    $feature->icon = $_POST['icon'];
    $feature->display_order = $_POST['display_order'];
    $feature->is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $feature->id = $_POST['id'];
        if ($feature->update()) {
            $message = 'Keunggulan berhasil diupdate!';
            $message_type = 'success';
        } else {
            $message = 'Gagal mengupdate keunggulan!';
            $message_type = 'danger';
        }
    } else {
        // Create
        if ($feature->create()) {
            $message = 'Keunggulan berhasil ditambahkan!';
            $message_type = 'success';
        } else {
            $message = 'Gagal menambahkan keunggulan!';
            $message_type = 'danger';
        }
    }
}

// Get all features
$stmt = $feature->read();

// Check if edit mode
$edit_mode = false;
$edit_feature = null;
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $feature->id = $_GET['edit'];
    if ($feature->readOne()) {
        $edit_feature = $feature;
    }
}

// FontAwesome icons yang tersedia
$icons = [
    'fa-mountain' => 'Mountain',
    'fa-shield-alt' => 'Shield',
    'fa-tag' => 'Tag',
    'fa-heart' => 'Heart',
    'fa-check-circle' => 'Check Circle',
    'fa-star' => 'Star',
    'fa-trophy' => 'Trophy',
    'fa-thumbs-up' => 'Thumbs Up',
    'fa-leaf' => 'Leaf',
    'fa-water' => 'Water',
    'fa-award' => 'Award',
    'fa-certificate' => 'Certificate',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Keunggulan - CurupWater Admin</title>
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
        .icon-preview {
            font-size: 2rem;
            margin-right: 10px;
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
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box me-2"></i>Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="features.php">
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
                    <h2><i class="fas fa-star me-2"></i>Manajemen Keunggulan</h2>
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
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-<?php echo $edit_mode ? 'edit' : 'plus'; ?> me-2"></i>
                            <?php echo $edit_mode ? 'Edit Keunggulan' : 'Tambah Keunggulan Baru'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if ($edit_mode): ?>
                                <input type="hidden" name="id" value="<?php echo $edit_feature->id; ?>">
                            <?php endif; ?>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="title" class="form-label">Judul Keunggulan *</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?php echo $edit_mode ? htmlspecialchars($edit_feature->title) : ''; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="display_order" class="form-label">Urutan Tampil *</label>
                                    <input type="number" class="form-control" id="display_order" name="display_order" 
                                           value="<?php echo $edit_mode ? $edit_feature->display_order : '0'; ?>" required>
                                    <small class="text-muted">Semakin kecil angka, semakin awal ditampilkan</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi *</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $edit_mode ? htmlspecialchars($edit_feature->description) : ''; ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="icon" class="form-label">Icon *</label>
                                    <select class="form-select" id="icon" name="icon" required>
                                        <?php foreach ($icons as $icon_class => $icon_name): ?>
                                            <option value="<?php echo $icon_class; ?>" 
                                                    <?php echo ($edit_mode && $edit_feature->icon == $icon_class) ? 'selected' : ''; ?>>
                                                <?php echo $icon_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="mt-2">
                                        <i class="fas <?php echo $edit_mode ? $edit_feature->icon : 'fa-check-circle'; ?> icon-preview" id="icon-preview"></i>
                                        <span id="icon-name">Preview</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               <?php echo ($edit_mode && $edit_feature->is_active) || !$edit_mode ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_active">Aktif</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i><?php echo $edit_mode ? 'Update' : 'Simpan'; ?>
                                </button>
                                <?php if ($edit_mode): ?>
                                    <a href="features.php" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Features List -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Keunggulan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Icon</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Urutan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $stmt->fetch()): ?>
                                    <tr>
                                        <td><i class="fas <?php echo $row['icon']; ?> fa-2x text-primary"></i></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                                        <td><?php echo $row['display_order']; ?></td>
                                        <td>
                                            <?php if ($row['is_active']): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="features.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="features.php?delete=<?php echo $row['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Yakin ingin menghapus keunggulan ini?')">
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
    <script>
        // Icon preview
        document.getElementById('icon').addEventListener('change', function() {
            const iconClass = this.value;
            const preview = document.getElementById('icon-preview');
            preview.className = 'fas ' + iconClass + ' icon-preview';
            document.getElementById('icon-name').textContent = this.options[this.selectedIndex].text;
        });
    </script>
</body>
</html>
