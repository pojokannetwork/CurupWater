<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/admin/includes/Product.php';
require_once __DIR__ . '/admin/includes/Feature.php';
require_once __DIR__ . '/admin/includes/About.php';
require_once __DIR__ . '/admin/includes/Contact.php';
require_once __DIR__ . '/admin/includes/Hero.php';

// Load data
$product = new Product();
$products_stmt = $product->readActive();

$feature = new Feature();
$features_stmt = $feature->readActive();

$about = new About();
$about->read();

$contact = new Contact();
$contact->read();

$hero = new Hero();
$hero->read();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CurupWater - Air Minum Berkualitas dari Curup</title>
    <meta name="description" content="CurupWater menyediakan air minum dalam kemasan berkualitas tinggi dari mata air alami Curup">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%),
                        <?php if ($hero->background_image): ?>
                        url('img/uploads/<?php echo $hero->background_image; ?>');
                        <?php else: ?>
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><rect fill="%23667eea" width="1200" height="800"/></svg>');
                        <?php endif; ?>
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .hero-content {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
        }
        
        .btn-hero {
            background: white;
            color: var(--primary-color);
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            background: #f8f9fa;
        }
        
        /* Navbar */
        .navbar {
            background: rgba(255,255,255,0.95) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .nav-link {
            font-weight: 500;
            color: #333 !important;
            margin: 0 10px;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        /* Section Titles */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 2px;
        }
        
        /* Products Section */
        .product-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .product-image {
            height: 250px;
            object-fit: cover;
        }
        
        .product-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        /* Features Section */
        .feature-box {
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            height: 100%;
        }
        
        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        /* About Section */
        .about-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        
        .footer a:hover {
            opacity: 0.8;
        }
        
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            margin: 0 5px;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: white;
            color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            .hero-section p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-water me-2"></i>CurupWater
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Keunggulan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center hero-content">
                    <h1 class="animate__animated animate__fadeInDown">
                        <?php echo htmlspecialchars($hero->title); ?>
                    </h1>
                    <p class="lead animate__animated animate__fadeInUp">
                        <?php echo htmlspecialchars($hero->subtitle); ?>
                    </p>
                    <?php if ($hero->button_text && $hero->button_link): ?>
                        <a href="<?php echo htmlspecialchars($hero->button_link); ?>" class="btn btn-hero">
                            <?php echo htmlspecialchars($hero->button_text); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Produk Kami</h2>
            <div class="row g-4">
                <?php while ($prod = $products_stmt->fetch()): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card product-card">
                        <?php if ($prod['image']): ?>
                            <img src="img/products/<?php echo $prod['image']; ?>" 
                                 class="card-img-top product-image" 
                                 alt="<?php echo htmlspecialchars($prod['name']); ?>">
                        <?php else: ?>
                            <div class="product-image bg-gradient d-flex align-items-center justify-content-center">
                                <i class="fas fa-bottle-water fa-5x text-white"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($prod['name']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($prod['description']); ?></p>
                            <p class="product-price mb-3">Rp <?php echo number_format($prod['price'], 0, ',', '.'); ?></p>
                            <a href="#contact" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center">Keunggulan Kami</h2>
            <div class="row g-4">
                <?php while ($feat = $features_stmt->fetch()): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box text-center">
                        <i class="fas <?php echo $feat['icon']; ?> feature-icon"></i>
                        <h4 class="feature-title"><?php echo htmlspecialchars($feat['title']); ?></h4>
                        <p class="text-muted"><?php echo htmlspecialchars($feat['description']); ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 about-section">
        <div class="container">
            <h2 class="section-title text-center"><?php echo htmlspecialchars($about->title); ?></h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
                            <p class="lead text-center"><?php echo nl2br(htmlspecialchars($about->content)); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Hubungi Kami</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                            <h5>Telepon</h5>
                            <p class="text-muted"><?php echo htmlspecialchars($contact->phone); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                            <h5>Email</h5>
                            <p class="text-muted"><?php echo htmlspecialchars($contact->email); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                            <h5>Alamat</h5>
                            <p class="text-muted"><?php echo htmlspecialchars($contact->address); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $contact->whatsapp); ?>" 
                       class="btn btn-success btn-lg me-2" target="_blank">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-water me-2"></i>CurupWater</h5>
                    <p>Air minum berkualitas dari mata air alami Curup untuk kesehatan keluarga Indonesia.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kontak</h5>
                    <p><i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($contact->phone); ?></p>
                    <p><i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($contact->email); ?></p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Follow Kami</h5>
                    <div class="social-links">
                        <a href="https://instagram.com/<?php echo htmlspecialchars($contact->instagram); ?>" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://facebook.com/<?php echo htmlspecialchars($contact->facebook); ?>" target="_blank">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $contact->whatsapp); ?>" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
            <hr class="bg-light">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> CurupWater. All Rights Reserved.</p>
                    <p class="mt-2 mb-0"><a href="admin/login.php" class="text-white"><i class="fas fa-lock me-1"></i>Admin Login</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255,255,255,0.98)';
            } else {
                navbar.style.background = 'rgba(255,255,255,0.95)';
            }
        });
    </script>
</body>
</html>
