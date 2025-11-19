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
            $title = $conn->real_escape_string($_POST['title']);
            $subtitle = $conn->real_escape_string($_POST['subtitle']);
            $button_text = $conn->real_escape_string($_POST['button_text']);
            $button_link = $conn->real_escape_string($_POST['button_link']);
            $order_num = (int)$_POST['order_num'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $image = time() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], '../../assets/img/hero/' . $image);
            }
            
            $sql = "INSERT INTO hero_slides (title, subtitle, image, button_text, button_link, order_num, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssii", $title, $subtitle, $image, $button_text, $button_link, $order_num, $is_active);
            
            if ($stmt->execute()) {
                $success = "Slide berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan slide!";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = (int)$_POST['id'];
            $title = $conn->real_escape_string($_POST['title']);
            $subtitle = $conn->real_escape_string($_POST['subtitle']);
            $button_text = $conn->real_escape_string($_POST['button_text']);
            $button_link = $conn->real_escape_string($_POST['button_link']);
            $order_num = (int)$_POST['order_num'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $image = time() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], '../../assets/img/hero/' . $image);
                $sql = "UPDATE hero_slides SET title=?, subtitle=?, image=?, button_text=?, button_link=?, order_num=?, is_active=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssiii", $title, $subtitle, $image, $button_text, $button_link, $order_num, $is_active, $id);
            } else {
                $sql = "UPDATE hero_slides SET title=?, subtitle=?, button_text=?, button_link=?, order_num=?, is_active=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssii", $title, $subtitle, $button_text, $button_link, $order_num, $is_active, $id);
            }
            
            if ($stmt->execute()) {
                $success = "Slide berhasil diupdate!";
            } else {
                $error = "Gagal mengupdate slide!";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = (int)$_POST['id'];
            $sql = "DELETE FROM hero_slides WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $success = "Slide berhasil dihapus!";
            } else {
                $error = "Gagal menghapus slide!";
            }
        }
    }
}

// Get all slides
$slides_result = $conn->query("SELECT * FROM hero_slides ORDER BY order_num ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Hero Slides - Admin Curup Water</title>
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
                <a href="hero.php" class="nav-item active">
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
                    <h1>Kelola Hero Slides</h1>
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

                <!-- Add New Slide -->
                <div class="card">
                    <div class="card-header">
                        <h2>Tambah Slide Baru</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" name="title" required>
                            </div>
                            <div class="form-group">
                                <label>Subtitle</label>
                                <textarea name="subtitle" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Gambar</label>
                                <input type="file" name="image" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Teks Tombol</label>
                                <input type="text" name="button_text">
                            </div>
                            <div class="form-group">
                                <label>Link Tombol</label>
                                <input type="text" name="button_link" placeholder="#products">
                            </div>
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="order_num" value="0">
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

                <!-- Slides List -->
                <div class="card">
                    <div class="card-header">
                        <h2>Daftar Slides</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($slides_result->num_rows > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Urutan</th>
                                    <th>Judul</th>
                                    <th>Gambar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($slide = $slides_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $slide['order_num']; ?></td>
                                    <td><?php echo htmlspecialchars($slide['title']); ?></td>
                                    <td>
                                        <?php if($slide['image']): ?>
                                        <img src="../../assets/img/hero/<?php echo $slide['image']; ?>" style="height: 40px; border-radius: 5px;">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($slide['is_active']): ?>
                                        <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                        <span class="badge badge-warning">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-buttons">
                                        <button onclick="editSlide(<?php echo htmlspecialchars(json_encode($slide)); ?>)" class="btn btn-sm btn-warning btn-icon">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Hapus slide ini?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $slide['id']; ?>">
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
                        <p class="text-center text-muted">Belum ada slide</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function editSlide(slide) {
        // Create edit form dynamically
        const form = document.createElement('form');
        form.method = 'POST';
        form.enctype = 'multipart/form-data';
        form.innerHTML = `
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="${slide.id}">
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="title" value="${slide.title}" required>
            </div>
            <div class="form-group">
                <label>Subtitle</label>
                <textarea name="subtitle" rows="3">${slide.subtitle || ''}</textarea>
            </div>
            <div class="form-group">
                <label>Gambar Baru (opsional)</label>
                <input type="file" name="image" accept="image/*">
                <small>Gambar saat ini: ${slide.image}</small>
            </div>
            <div class="form-group">
                <label>Teks Tombol</label>
                <input type="text" name="button_text" value="${slide.button_text || ''}">
            </div>
            <div class="form-group">
                <label>Link Tombol</label>
                <input type="text" name="button_link" value="${slide.button_link || ''}">
            </div>
            <div class="form-group">
                <label>Urutan</label>
                <input type="number" name="order_num" value="${slide.order_num}">
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_active" ${slide.is_active ? 'checked' : ''}>
                    <label>Aktif</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
            </button>
        `;
        
        // Replace add form with edit form
        const cardBody = document.querySelector('.card .card-body');
        cardBody.innerHTML = '';
        cardBody.appendChild(form);
        
        // Scroll to form
        cardBody.scrollIntoView({ behavior: 'smooth' });
    }
    </script>
</body>
</html>
