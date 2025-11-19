<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../../config/database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $whatsapp = $conn->real_escape_string($_POST['whatsapp']);
    $facebook = $conn->real_escape_string($_POST['facebook']);
    $instagram = $conn->real_escape_string($_POST['instagram']);
    
    // Check if contact exists
    $check = $conn->query("SELECT id FROM contact LIMIT 1");
    
    if ($check->num_rows > 0) {
        // Update existing
        $sql = "UPDATE contact SET address=?, phone=?, email=?, whatsapp=?, facebook=?, instagram=? WHERE id=1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $address, $phone, $email, $whatsapp, $facebook, $instagram);
    } else {
        // Insert new
        $sql = "INSERT INTO contact (address, phone, email, whatsapp, facebook, instagram) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $address, $phone, $email, $whatsapp, $facebook, $instagram);
    }
    
    if ($stmt->execute()) {
        $success = "Informasi kontak berhasil disimpan!";
    } else {
        $error = "Gagal menyimpan informasi kontak!";
    }
}

// Get contact info
$contact = $conn->query("SELECT * FROM contact LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kontak - Admin Curup Water</title>
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
                <a href="about.php" class="nav-item">
                    <i class="fas fa-info-circle"></i>
                    <span>Tentang Kami</span>
                </a>
                <a href="contact.php" class="nav-item active">
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
                    <h1>Kelola Informasi Kontak</h1>
                </div>
                <div class="top-bar-actions">
                    <a href="../../index.php" class="btn btn-sm" target="_blank">
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
                        <h2>Informasi Kontak</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea name="address" rows="3" required><?php echo $contact['address'] ?? ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Telepon</label>
                                <input type="text" name="phone" value="<?php echo $contact['phone'] ?? ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="<?php echo $contact['email'] ?? ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>WhatsApp <small>(dengan kode negara, contoh: 6281234567890)</small></label>
                                <input type="text" name="whatsapp" value="<?php echo $contact['whatsapp'] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Facebook URL</label>
                                <input type="url" name="facebook" value="<?php echo $contact['facebook'] ?? ''; ?>" placeholder="https://facebook.com/curupwater">
                            </div>
                            <div class="form-group">
                                <label>Instagram URL</label>
                                <input type="url" name="instagram" value="<?php echo $contact['instagram'] ?? ''; ?>" placeholder="https://instagram.com/curupwater">
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
