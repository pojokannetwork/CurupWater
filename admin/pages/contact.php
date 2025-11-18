<?php
session_start();

require_once __DIR__ . '/../includes/Admin.php';

// Check login
if (!Admin::isLoggedIn()) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../includes/Contact.php';

$contact = new Contact();
$message = '';
$message_type = '';

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contact->id = $_POST['id'];
    $contact->phone = $_POST['phone'];
    $contact->email = $_POST['email'];
    $contact->address = $_POST['address'];
    $contact->whatsapp = $_POST['whatsapp'];
    $contact->instagram = $_POST['instagram'];
    $contact->facebook = $_POST['facebook'];
    
    if ($contact->update()) {
        $message = 'Info kontak berhasil diupdate!';
        $message_type = 'success';
    } else {
        $message = 'Gagal mengupdate info kontak!';
        $message_type = 'danger';
    }
}

// Get current contact
$contact->read();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Info Kontak - CurupWater Admin</title>
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
                        <a class="nav-link active" href="contact.php">
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
                    <h2><i class="fas fa-phone me-2"></i>Edit Info Kontak</h2>
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
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Edit Informasi Kontak
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $contact->id; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-2"></i>Nomor Telepon
                                    </label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($contact->phone); ?>" placeholder="+62 812-3456-7890">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>Email
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($contact->email); ?>" placeholder="info@curupwater.com">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt me-2"></i>Alamat
                                </label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($contact->address); ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="whatsapp" class="form-label">
                                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                                    </label>
                                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" 
                                           value="<?php echo htmlspecialchars($contact->whatsapp); ?>" placeholder="+62 812-3456-7890">
                                    <small class="text-muted">Format: +62 xxx-xxxx-xxxx</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="instagram" class="form-label">
                                        <i class="fab fa-instagram me-2"></i>Instagram
                                    </label>
                                    <input type="text" class="form-control" id="instagram" name="instagram" 
                                           value="<?php echo htmlspecialchars($contact->instagram); ?>" placeholder="@curupwater">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="facebook" class="form-label">
                                        <i class="fab fa-facebook me-2"></i>Facebook
                                    </label>
                                    <input type="text" class="form-control" id="facebook" name="facebook" 
                                           value="<?php echo htmlspecialchars($contact->facebook); ?>" placeholder="curupwater.official">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-warning">
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
