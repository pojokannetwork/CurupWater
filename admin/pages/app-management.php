<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || ($_SESSION['admin_role'] !== 'super_admin' && $_SESSION['admin_role'] !== 'app_admin')) {
    header('Location: ../login.php');
    exit();
}

require_once '../../config/database.php';

// Handle Add/Edit/Delete Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'];
            
            // App admin cannot create super admin
            if ($_SESSION['admin_role'] === 'app_admin' && $role === 'super_admin') {
                $error = "Admin Aplikasi tidak dapat membuat Super Admin!";
            } else {
                $stmt = $conn->prepare("INSERT INTO admin (username, password, role) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $password, $role);
                
                if ($stmt->execute()) {
                    $success = "Admin berhasil ditambahkan!";
                } else {
                    $error = "Gagal menambahkan admin: " . $stmt->error;
                }
                $stmt->close();
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $role = $_POST['role'];
            
            // Get current admin data
            $check = $conn->query("SELECT role FROM admin WHERE id = $id");
            $current = $check->fetch_assoc();
            
            // App admin cannot edit super admin
            if ($_SESSION['admin_role'] === 'app_admin' && $current['role'] === 'super_admin') {
                $error = "Admin Aplikasi tidak dapat mengedit Super Admin!";
            } elseif ($_SESSION['admin_role'] === 'app_admin' && $role === 'super_admin') {
                $error = "Admin Aplikasi tidak dapat mengubah role menjadi Super Admin!";
            } else {
                if (!empty($_POST['password'])) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE admin SET username=?, password=?, role=? WHERE id=?");
                    $stmt->bind_param("sssi", $username, $password, $role, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE admin SET username=?, role=? WHERE id=?");
                    $stmt->bind_param("ssi", $username, $role, $id);
                }
                
                if ($stmt->execute()) {
                    $success = "Admin berhasil diupdate!";
                } else {
                    $error = "Gagal update admin: " . $stmt->error;
                }
                $stmt->close();
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            
            // Get admin data to check role
            $check = $conn->query("SELECT role FROM admin WHERE id = $id");
            $target = $check->fetch_assoc();
            
            // Prevent deleting self
            if ($id == $_SESSION['admin_id']) {
                $error = "Tidak dapat menghapus akun sendiri!";
            } elseif ($_SESSION['admin_role'] === 'app_admin' && $target['role'] === 'super_admin') {
                $error = "Admin Aplikasi tidak dapat menghapus Super Admin!";
            } else {
                $stmt = $conn->prepare("DELETE FROM admin WHERE id = ?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    $success = "Admin berhasil dihapus!";
                } else {
                    $error = "Gagal hapus admin: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

// Get all admins
$admins = $conn->query("SELECT * FROM admin ORDER BY created_at DESC");

// Get statistics
$stats = [];
$stats['total_admins'] = $conn->query("SELECT COUNT(*) as count FROM admin")->fetch_assoc()['count'];
$stats['super_admins'] = $conn->query("SELECT COUNT(*) as count FROM admin WHERE role='super_admin'")->fetch_assoc()['count'];
$stats['app_admins'] = $conn->query("SELECT COUNT(*) as count FROM admin WHERE role='app_admin'")->fetch_assoc()['count'];
$stats['regular_admins'] = $conn->query("SELECT COUNT(*) as count FROM admin WHERE role='admin'")->fetch_assoc()['count'];
$stats['total_products'] = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$stats['total_messages'] = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
$stats['total_photos'] = $conn->query("SELECT COUNT(*) as count FROM gallery_photos")->fetch_assoc()['count'];
$stats['total_videos'] = $conn->query("SELECT COUNT(*) as count FROM gallery_videos")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Aplikasi - Super Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <a href="app-management.php" class="active"><i class="fas fa-cogs"></i> Kelola Admin</a>
                <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-cogs"></i> Management Aplikasi</h1>
                <p>Kelola administrator dan pengaturan sistem</p>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <!-- Statistics -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e3f2fd;">
                        <i class="fas fa-users-cog" style="color: #1976d2;"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_admins'] ?></h3>
                        <p>Total Admin</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #f3e5f5;">
                        <i class="fas fa-crown" style="color: #7b1fa2;"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['super_admins'] ?></h3>
                        <p>Super Admin</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #fff3e0;">
                        <i class="fas fa-user-cog" style="color: #f57c00;"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['app_admins'] ?></h3>
                        <p>Admin Aplikasi</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e8f5e9;">
                        <i class="fas fa-user-shield" style="color: #388e3c;"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['regular_admins'] ?></h3>
                        <p>Admin Website</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #fff3e0;">
                        <i class="fas fa-database" style="color: #f57c00;"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_products'] + $stats['total_photos'] + $stats['total_videos'] ?></h3>
                        <p>Total Konten</p>
                    </div>
                </div>
            </div>

            <!-- Add Admin Form -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-plus"></i> Tambah Admin Baru</h3>
                </div>
                <div class="card-body">
                    <form method="POST" class="form">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Username *</label>
                                <input type="text" name="username" required class="form-control" minlength="3">
                            </div>

                            <div class="form-group">
                                <label>Password *</label>
                                <input type="password" name="password" required class="form-control" minlength="6">
                            </div>

                            <div class="form-group">
                                <label>Role *</label>
                                <select name="role" required class="form-control">
                                    <option value="admin">Admin Website</option>
                                    <option value="app_admin">Admin Aplikasi</option>
                                    <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
                                        <option value="super_admin">Super Admin</option>
                                    <?php endif; ?>
                                </select>
                                <small>
                                    <strong>Admin Website:</strong> Kelola konten website<br>
                                    <strong>Admin Aplikasi:</strong> Kelola admin & lihat statistik
                                    <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
                                        <br><strong>Super Admin:</strong> Akses penuh semua fitur
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Admin
                        </button>
                    </form>
                </div>
            </div>

            <!-- Admins List -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <h3><i class="fas fa-users"></i> Daftar Administrator</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($admin = $admins->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $admin['id'] ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($admin['username']) ?></strong>
                                            <?php if ($admin['id'] == $_SESSION['admin_id']): ?>
                                                <span class="badge badge-info">Anda</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($admin['role'] === 'super_admin'): ?>
                                                <span class="role-badge super-admin">
                                                    <i class="fas fa-crown"></i> Super Admin
                                                </span>
                                            <?php elseif ($admin['role'] === 'app_admin'): ?>
                                                <span class="role-badge app-admin">
                                                    <i class="fas fa-user-cog"></i> Admin Aplikasi
                                                </span>
                                            <?php else: ?>
                                                <span class="role-badge admin">
                                                    <i class="fas fa-user"></i> Admin Website
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($admin['created_at'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" 
                                                <?php if ($_SESSION['admin_role'] === 'app_admin' && $admin['role'] === 'super_admin'): ?>
                                                    disabled title="Tidak dapat mengedit Super Admin"
                                                <?php else: ?>
                                                    onclick="editAdmin(<?= $admin['id'] ?>, '<?= htmlspecialchars($admin['username']) ?>', '<?= $admin['role'] ?>')"
                                                <?php endif; ?>>
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <?php if ($admin['id'] != $_SESSION['admin_id'] && !($_SESSION['admin_role'] === 'app_admin' && $admin['role'] === 'super_admin')): ?>
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            <?php endif; ?>
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

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2><i class="fas fa-edit"></i> Edit Admin</h2>
            <form method="POST" class="form">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" name="username" id="edit_username" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Password Baru (kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password" id="edit_password" class="form-control" minlength="6">
                </div>

                <div class="form-group">
                    <label>Role *</label>
                    <select name="role" id="edit_role" required class="form-control">
                        <option value="admin">Admin Website</option>
                        <option value="app_admin">Admin Aplikasi</option>
                        <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
                            <option value="super_admin">Super Admin</option>
                        <?php endif; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <style>
        .role-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .role-badge.super-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .role-badge.admin {
            background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%);
            color: white;
        }
        .sidebar-header .role-badge {
            margin-top: 0.5rem;
            font-size: 0.7rem;
        }
        .nav-divider {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 1px;
            border-top: 1px solid #e2e8f0;
            margin-top: 1rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        .badge-info {
            background: #0ea5e9;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover { color: #000; }
    </style>

    <script>
        function editAdmin(id, username, role) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_password').value = '';
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
