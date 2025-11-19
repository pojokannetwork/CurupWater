<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../../config/database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $video_url = $_POST['video_url'];
            $order_num = $_POST['order_num'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Extract YouTube video ID and generate thumbnail
            $thumbnail = '';
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $video_url, $matches)) {
                $video_id = $matches[1];
                $thumbnail = 'https://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
            }
            
            $stmt = $conn->prepare("INSERT INTO gallery_videos (title, description, video_url, thumbnail, order_num, is_active) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssii", $title, $description, $video_url, $thumbnail, $order_num, $is_active);
            
            if ($stmt->execute()) {
                $success = "Video berhasil ditambahkan!";
            } else {
                $error = "Gagal menyimpan data: " . $stmt->error;
            }
            $stmt->close();
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $video_url = $_POST['video_url'];
            $order_num = $_POST['order_num'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Extract YouTube video ID and generate thumbnail
            $thumbnail = '';
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $video_url, $matches)) {
                $video_id = $matches[1];
                $thumbnail = 'https://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
            }
            
            $stmt = $conn->prepare("UPDATE gallery_videos SET title=?, description=?, video_url=?, thumbnail=?, order_num=?, is_active=? WHERE id=?");
            $stmt->bind_param("sssssii", $title, $description, $video_url, $thumbnail, $order_num, $is_active, $id);
            
            if ($stmt->execute()) {
                $success = "Video berhasil diupdate!";
            } else {
                $error = "Gagal update data: " . $stmt->error;
            }
            $stmt->close();
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            
            $stmt = $conn->prepare("DELETE FROM gallery_videos WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $success = "Video berhasil dihapus!";
            } else {
                $error = "Gagal hapus data: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Get all videos
$videos = $conn->query("SELECT * FROM gallery_videos ORDER BY order_num ASC, created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Video Gallery - Admin Curup Water</title>
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
            </div>
            <nav class="sidebar-nav">
                <a href="../index.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="hero.php"><i class="fas fa-image"></i> Hero Slider</a>
                <a href="products.php"><i class="fas fa-box"></i> Produk</a>
                <a href="about.php"><i class="fas fa-info-circle"></i> Tentang</a>
                <div class="nav-group">
                    <a href="#" class="nav-group-title active"><i class="fas fa-images"></i> Galeri <i class="fas fa-chevron-down"></i></a>
                    <div class="nav-group-content" style="display: block;">
                        <a href="gallery-photos.php"><i class="fas fa-camera"></i> Foto</a>
                        <a href="gallery-videos.php" class="active"><i class="fas fa-video"></i> Video</a>
                    </div>
                </div>
                <a href="contact.php"><i class="fas fa-phone"></i> Kontak</a>
                <a href="messages.php"><i class="fas fa-envelope"></i> Pesan</a>
                <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1>Kelola Video Gallery</h1>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <!-- Add Video Form -->
            <div class="card">
                <div class="card-header">
                    <h3>Tambah Video Baru</h3>
                </div>
                <div class="card-body">
                    <form method="POST" class="form">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="form-group">
                            <label>Judul Video *</label>
                            <input type="text" name="title" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="description" rows="3" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label>URL Video YouTube *</label>
                            <input type="url" name="video_url" required class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                            <small>Masukkan link YouTube video. Thumbnail akan diambil otomatis.</small>
                        </div>

                        <div class="form-group">
                            <label>Urutan</label>
                            <input type="number" name="order_num" value="0" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active" checked> Aktif
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Video
                        </button>
                    </form>
                </div>
            </div>

            <!-- Videos List -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <h3>Daftar Video</h3>
                </div>
                <div class="card-body">
                    <div class="gallery-grid-admin">
                        <?php while ($video = $videos->fetch_assoc()): ?>
                            <div class="gallery-item-admin">
                                <?php if ($video['thumbnail']): ?>
                                    <img src="<?= htmlspecialchars($video['thumbnail']) ?>" alt="<?= htmlspecialchars($video['title']) ?>">
                                <?php else: ?>
                                    <div class="video-placeholder">
                                        <i class="fas fa-video fa-3x"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="gallery-item-info">
                                    <h4><?= htmlspecialchars($video['title']) ?></h4>
                                    <p><?= htmlspecialchars($video['description']) ?></p>
                                    <div class="video-url">
                                        <i class="fab fa-youtube"></i>
                                        <a href="<?= htmlspecialchars($video['video_url']) ?>" target="_blank">Lihat Video</a>
                                    </div>
                                    <div class="gallery-item-meta">
                                        <span class="badge <?= $video['is_active'] ? 'badge-success' : 'badge-secondary' ?>">
                                            <?= $video['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                        <span class="order">Urutan: <?= $video['order_num'] ?></span>
                                    </div>
                                    <div class="gallery-item-actions">
                                        <button class="btn btn-sm btn-primary" onclick="editVideo(<?= $video['id'] ?>, '<?= htmlspecialchars($video['title']) ?>', '<?= htmlspecialchars($video['description']) ?>', '<?= htmlspecialchars($video['video_url']) ?>', <?= $video['order_num'] ?>, <?= $video['is_active'] ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus video ini?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $video['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Video</h2>
            <form method="POST" class="form">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group">
                    <label>Judul Video *</label>
                    <input type="text" name="title" id="edit_title" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>URL Video YouTube *</label>
                    <input type="url" name="video_url" id="edit_video_url" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Urutan</label>
                    <input type="number" name="order_num" id="edit_order_num" class="form-control">
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" id="edit_is_active"> Aktif
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <style>
        .gallery-grid-admin {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .gallery-item-admin {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }
        .gallery-item-admin img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .video-placeholder {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            color: #999;
        }
        .gallery-item-info {
            padding: 1rem;
        }
        .gallery-item-info h4 {
            margin: 0 0 0.5rem 0;
            color: #1e3a5f;
        }
        .gallery-item-info p {
            margin: 0 0 0.5rem 0;
            color: #666;
            font-size: 0.9rem;
        }
        .video-url {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.5rem 0;
            color: #ff0000;
        }
        .video-url a {
            color: #1e3a5f;
            text-decoration: none;
        }
        .video-url a:hover {
            text-decoration: underline;
        }
        .gallery-item-meta {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin: 0.5rem 0;
        }
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .badge-success { background: #28a745; color: white; }
        .badge-secondary { background: #6c757d; color: white; }
        .order {
            font-size: 0.85rem;
            color: #666;
        }
        .gallery-item-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
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
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover { color: #000; }
        .nav-group-content {
            padding-left: 1rem;
        }
        .nav-group-content a {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    </style>

    <script>
        function editVideo(id, title, description, video_url, order_num, is_active) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_video_url').value = video_url;
            document.getElementById('edit_order_num').value = order_num;
            document.getElementById('edit_is_active').checked = is_active == 1;
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
