<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../../config/database.php';

// Handle mark as read
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    $conn->query("UPDATE messages SET is_read = 1 WHERE id = $id");
    header('Location: messages.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM messages WHERE id = $id");
    header('Location: messages.php');
    exit;
}

// Get all messages
$messages = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan - Admin Curup Water</title>
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
                <a href="contact.php" class="nav-item">
                    <i class="fas fa-address-book"></i>
                    <span>Kontak</span>
                </a>
                <a href="messages.php" class="nav-item active">
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
                    <h1>Pesan dari Pengunjung</h1>
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
                <div class="card">
                    <div class="card-header">
                        <h2>Daftar Pesan</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($messages->num_rows > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Subjek</th>
                                    <th>Pesan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($msg = $messages->fetch_assoc()): ?>
                                <tr style="<?php echo $msg['is_read'] ? '' : 'background:#fef3c7;'; ?>">
                                    <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                    <td style="max-width:200px;"><?php echo htmlspecialchars(substr($msg['message'], 0, 50)) . '...'; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($msg['created_at'])); ?></td>
                                    <td>
                                        <?php if($msg['is_read']): ?>
                                        <span class="badge badge-success">Sudah Dibaca</span>
                                        <?php else: ?>
                                        <span class="badge badge-warning">Belum Dibaca</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-buttons">
                                        <?php if(!$msg['is_read']): ?>
                                        <a href="?read=<?php echo $msg['id']; ?>" class="btn btn-sm btn-success btn-icon" title="Tandai Sudah Dibaca">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <?php endif; ?>
                                        <a href="?delete=<?php echo $msg['id']; ?>" class="btn btn-sm btn-danger btn-icon" onclick="return confirm('Hapus pesan ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
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
