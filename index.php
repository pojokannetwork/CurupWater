<?php
require_once 'config/database.php';

// Fetch hero slides
$hero_query = "SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY order_num ASC";
$hero_result = $conn->query($hero_query);

// Fetch products
$products_query = "SELECT * FROM products WHERE is_active = 1 ORDER BY order_num ASC";
$products_result = $conn->query($products_query);

// Fetch about
$about_query = "SELECT * FROM about LIMIT 1";
$about_result = $conn->query($about_query);
$about = $about_result->fetch_assoc();

// Fetch contact
$contact_query = "SELECT * FROM contact LIMIT 1";
$contact_result = $conn->query($contact_query);
$contact = $contact_result->fetch_assoc();

// Fetch gallery photos
$photos_query = "SELECT * FROM gallery_photos WHERE is_active = 1 ORDER BY order_num ASC LIMIT 8";
$photos_result = $conn->query($photos_query);

// Fetch gallery videos
$videos_query = "SELECT * FROM gallery_videos WHERE is_active = 1 ORDER BY order_num ASC LIMIT 6";
$videos_result = $conn->query($videos_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curup Water - Air Mineral Berkualitas Tinggi</title>
    <meta name="description" content="Curup Water menyediakan air mineral berkualitas tinggi dari sumber pegunungan alami">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="nav-brand">
                <img src="assets/img/logo.svg" alt="Curup Water" class="logo">
                <span class="brand-name">CURUP WATER</span>
            </div>
            <button class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="#home" class="nav-link active">BERANDA</a></li>
                <li><a href="#products" class="nav-link">PRODUK</a></li>
                <li><a href="#about" class="nav-link">TENTANG KAMI</a></li>
                <li><a href="#gallery" class="nav-link">GALERI</a></li>
                <li><a href="#contact" class="nav-link">KONTAK</a></li>
                <li><a href="admin/" class="nav-link btn-admin">ADMIN</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section with Slider -->
    <section id="home" class="hero">
        <div class="hero-slider">
            <?php if ($hero_result->num_rows > 0): ?>
                <?php while($slide = $hero_result->fetch_assoc()): ?>
                <div class="hero-slide">
                    <div class="hero-bg" style="background-image: url('assets/img/hero/<?php echo $slide['image']; ?>')"></div>
                    <div class="hero-overlay"></div>
                    <div class="container">
                        <div class="hero-content">
                            <h1 class="hero-title animate-fade-up"><?php echo $slide['title']; ?></h1>
                            <p class="hero-subtitle animate-fade-up"><?php echo $slide['subtitle']; ?></p>
                            <?php if($slide['button_text']): ?>
                            <a href="<?php echo $slide['button_link']; ?>" class="btn btn-primary animate-fade-up"><?php echo $slide['button_text']; ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="hero-slide">
                    <div class="hero-bg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)"></div>
                    <div class="hero-overlay"></div>
                    <div class="container">
                        <div class="hero-content">
                            <h1 class="hero-title">Curup Water - Air Mineral Berkualitas</h1>
                            <p class="hero-subtitle">Sumber air terbaik dari pegunungan untuk kesehatan keluarga Anda</p>
                            <a href="#products" class="btn btn-primary">Lihat Produk</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="hero-controls">
            <button class="hero-prev"><i class="fas fa-chevron-left"></i></button>
            <div class="hero-dots"></div>
            <button class="hero-next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Produk Kami</h2>
                <p class="section-subtitle">Berbagai pilihan ukuran untuk kebutuhan Anda</p>
            </div>
            <div class="products-grid">
                <?php if ($products_result->num_rows > 0): ?>
                    <?php while($product = $products_result->fetch_assoc()): ?>
                    <div class="product-card">
                        <div class="product-badge">
                            <?php if($product['is_featured']): ?>
                            <span class="badge-featured">PILIHAN TERBAIK</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-image">
                            <img src="assets/img/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" onerror="this.src='assets/img/placeholder.jpg'">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo $product['name']; ?></h3>
                            <p class="product-size"><?php echo $product['size']; ?></p>
                            <p class="product-description"><?php echo $product['description']; ?></p>
                            <div class="product-footer">
                                <span class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                                <a href="#contact" class="btn btn-small">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">Belum ada produk yang ditampilkan.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-wrapper">
                <div class="about-image">
                    <?php if($about && $about['image']): ?>
                    <img src="assets/img/<?php echo $about['image']; ?>" alt="Tentang Curup Water" onerror="this.src='assets/img/placeholder.jpg'">
                    <?php else: ?>
                    <div class="about-placeholder">
                        <i class="fas fa-water"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="about-content">
                    <h2 class="section-title"><?php echo $about ? $about['title'] : 'Tentang Curup Water'; ?></h2>
                    <?php if($about): ?>
                        <?php 
                        $paragraphs = explode("\n\n", $about['content']);
                        foreach($paragraphs as $paragraph): 
                            if(trim($paragraph)):
                        ?>
                        <p><?php echo nl2br($paragraph); ?></p>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    <?php else: ?>
                    <p>Curup Water adalah produsen air minum dalam kemasan yang berkomitmen menyediakan air mineral berkualitas tinggi untuk masyarakat.</p>
                    <?php endif; ?>
                    <div class="about-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Sumber Air Alami</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Proses Higienis</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Standar Internasional</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="gallery">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Galeri Kami</h2>
                <p class="section-subtitle">Dokumentasi kegiatan dan produk Curup Water</p>
            </div>

            <!-- Gallery Tabs -->
            <div class="gallery-tabs">
                <button class="gallery-tab active" data-tab="photos">
                    <i class="fas fa-image"></i> Foto
                </button>
                <button class="gallery-tab" data-tab="videos">
                    <i class="fas fa-video"></i> Video
                </button>
            </div>

            <!-- Photos Tab -->
            <div class="gallery-content active" id="photos">
                <div class="gallery-grid">
                    <?php if ($photos_result && $photos_result->num_rows > 0): ?>
                        <?php while($photo = $photos_result->fetch_assoc()): ?>
                        <div class="gallery-item">
                            <img src="assets/img/gallery/<?php echo $photo['image']; ?>" alt="<?php echo htmlspecialchars($photo['title']); ?>" onerror="this.src='assets/img/placeholder.jpg'">
                            <div class="gallery-overlay">
                                <h3><?php echo htmlspecialchars($photo['title']); ?></h3>
                                <?php if($photo['description']): ?>
                                <p><?php echo htmlspecialchars($photo['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center" style="grid-column: 1/-1;">Belum ada foto yang ditampilkan.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Videos Tab -->
            <div class="gallery-content" id="videos">
                <div class="gallery-grid video-grid">
                    <?php if ($videos_result && $videos_result->num_rows > 0): ?>
                        <?php while($video = $videos_result->fetch_assoc()): ?>
                        <div class="gallery-video">
                            <div class="video-wrapper">
                                <?php 
                                // Extract YouTube video ID
                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $video['video_url'], $matches);
                                $video_id = $matches[1] ?? '';
                                ?>
                                <?php if($video_id): ?>
                                <iframe src="https://www.youtube.com/embed/<?php echo $video_id; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                <?php else: ?>
                                <video controls>
                                    <source src="<?php echo $video['video_url']; ?>" type="video/mp4">
                                </video>
                                <?php endif; ?>
                            </div>
                            <div class="video-info">
                                <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                                <?php if($video['description']): ?>
                                <p><?php echo htmlspecialchars($video['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center" style="grid-column: 1/-1;">Belum ada video yang ditampilkan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Hubungi Kami</h2>
                <p class="section-subtitle">Kami siap melayani kebutuhan air mineral berkualitas Anda</p>
            </div>
            <div class="contact-wrapper">
                <div class="contact-info">
                    <div class="info-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>Alamat</h3>
                        <p><?php echo $contact ? $contact['address'] : 'Jl. Raya Curup, Bengkulu'; ?></p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-phone"></i>
                        <h3>Telepon</h3>
                        <p><?php echo $contact ? $contact['phone'] : '-'; ?></p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-envelope"></i>
                        <h3>Email</h3>
                        <p><?php echo $contact ? $contact['email'] : '-'; ?></p>
                    </div>
                    <?php if($contact && $contact['whatsapp']): ?>
                    <div class="info-card">
                        <i class="fab fa-whatsapp"></i>
                        <h3>WhatsApp</h3>
                        <p><?php echo $contact['whatsapp']; ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($contact && ($contact['facebook'] || $contact['instagram'])): ?>
                    <div class="social-links">
                        <?php if($contact['facebook']): ?>
                        <a href="<?php echo $contact['facebook']; ?>" target="_blank"><i class="fab fa-facebook"></i></a>
                        <?php endif; ?>
                        <?php if($contact['instagram']): ?>
                        <a href="<?php echo $contact['instagram']; ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="contact-form-wrapper">
                    <form class="contact-form" id="contactForm">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="phone" placeholder="Nomor Telepon">
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" placeholder="Subjek" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" rows="5" placeholder="Pesan Anda" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                    </form>
                    <div id="formMessage"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <img src="assets/img/logo.svg" alt="Curup Water" class="footer-logo">
                    <h3>CURUP WATER</h3>
                    <p>Air mineral berkualitas tinggi dari sumber pegunungan alami</p>
                </div>
                <div class="footer-section">
                    <h4>Menu</h4>
                    <ul>
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#products">Produk</a></li>
                        <li><a href="#about">Tentang Kami</a></li>
                        <li><a href="#gallery">Galeri</a></li>
                        <li><a href="#contact">Kontak</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Kontak</h4>
                    <p><?php echo $contact ? $contact['phone'] : '-'; ?></p>
                    <p><?php echo $contact ? $contact['email'] : '-'; ?></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Curup Water. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollTop" class="scroll-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="assets/js/main.js"></script>
</body>
</html>
