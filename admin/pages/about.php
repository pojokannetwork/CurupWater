<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../../config/database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../../assets/img/' . $image);
    }
    
    // Check if about exists
    $check = $conn->query("SELECT id FROM about LIMIT 1");
    
    if ($check->num_rows > 0) {
        // Update existing
        if ($image) {
            $sql = "UPDATE about SET title=?, content=?, image=? WHERE id=1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $title, $content, $image);
        } else {
            $sql = "UPDATE about SET title=?, content=? WHERE id=1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $title, $content);
        }
    } else {
        // Insert new
        $sql = "INSERT INTO about (title, content, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $content, $image);
    }
    
    if ($stmt->execute()) {
        $success = "Informasi berhasil disimpan!";
    } else {
        $error = "Gagal menyimpan informasi!";
    }
}

// Get about info
$about = $conn->query("SELECT * FROM about LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tentang Kami - Admin Curup Water</title>
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
                <a href="products.php" class="nav-item">
                    <i class="fas fa-box"></i>
                    <span>Produk</span>
                </a>
                <a href="about.php" class="nav-item active">
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
                    <h1>Kelola Tentang Kami</h1>
                </div>
                <div class="top-bar-actions">
                    <a href="../../index.php#about" class="btn btn-sm" target="_blank">
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

                <div class="card">
                    <div class="card-header">
                        <h2>Informasi Tentang Perusahaan</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" name="title" value="<?php echo $about['title'] ?? 'Tentang Curup Water'; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Konten</label>
                                <textarea name="content" rows="10" required><?php echo $about['content'] ?? ''; ?></textarea>
                                <small>Gunakan dua baris kosong untuk memisahkan paragraf</small>
                            </div>
                            <div class="form-group">
                                <label>Gambar</label>
                                <input type="file" name="image" accept="image/*">
                                <?php if (isset($about['image']) && $about['image']): ?>
                                <div class="image-preview">
                                    <img src="../../assets/img/<?php echo $about['image']; ?>" alt="Current image">
                                    <small>Gambar saat ini</small>
                                </div>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
