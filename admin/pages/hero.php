<?php
session_start();

require_once __DIR__ . '/../includes/Admin.php';

// Check login
if (!Admin::isLoggedIn()) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../includes/Hero.php';

$hero = new Hero();
$message = '';
$message_type = '';

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hero->id = $_POST['id'];
    $hero->title = $_POST['title'];
    $hero->subtitle = $_POST['subtitle'];
    $hero->button_text = $_POST['button_text'];
    $hero->button_link = $_POST['button_link'];
    
    // Handle image upload
    $image_name = $_POST['old_image'] ?? '';
    
    if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['background_image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $new_filename = 'hero_' . time() . '.' . $filetype;
            $upload_path = __DIR__ . '/../../img/uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['background_image']['tmp_name'], $upload_path)) {
                // Delete old image
                if (!empty($image_name)) {
                    $old_path = __DIR__ . '/../../img/uploads/' . $image_name;
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                }
                $image_name = $new_filename;
            }
        }
    }
    
    $hero->background_image = $image_name;
    
    if ($hero->update()) {
        $message = 'Hero Section berhasil diupdate!';
        $message_type = 'success';
    } else {
        $message = 'Gagal mengupdate Hero Section!';
        $message_type = 'danger';
    }
}

// Get current hero
$hero->read();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Section - CurupWater Admin</title>
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
        .hero-preview {
            width: 100%;
            max-width: 500px;
            height: 250px;
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
                        <a class="nav-link" href="products.php">
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
                        <a class="nav-link active" href="hero.php">
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
                    <h2><i class="fas fa-image me-2"></i>Edit Hero Section</h2>
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

                <!-- Form Edit -->
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Edit Hero Section Landing Page
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $hero->id; ?>">
                            <input type="hidden" name="old_image" value="<?php echo $hero->background_image; ?>">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Utama *</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?php echo htmlspecialchars($hero->title); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subtitle" class="form-label">Sub Judul</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle" 
                                       value="<?php echo htmlspecialchars($hero->subtitle); ?>">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="button_text" class="form-label">Teks Tombol</label>
                                    <input type="text" class="form-control" id="button_text" name="button_text" 
                                           value="<?php echo htmlspecialchars($hero->button_text); ?>" placeholder="Pesan Sekarang">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="button_link" class="form-label">Link Tombol</label>
                                    <input type="text" class="form-control" id="button_link" name="button_link" 
                                           value="<?php echo htmlspecialchars($hero->button_link); ?>" placeholder="#products">
                                    <small class="text-muted">Contoh: #products, #contact, atau URL lengkap</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="background_image" class="form-label">Gambar Background</label>
                                <input type="file" class="form-control" id="background_image" name="background_image" accept="image/*">
                                <small class="text-muted">Format: JPG, JPEG, PNG, GIF (Max 2MB). Resolusi direkomendasikan: 1920x1080px</small>
                                <?php if ($hero->background_image): ?>
                                    <div class="mt-3">
                                        <p class="fw-bold">Preview Background Saat Ini:</p>
                                        <img src="../../img/uploads/<?php echo $hero->background_image; ?>" alt="Hero Background" class="hero-preview">
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-save me-2"></i>Update
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
