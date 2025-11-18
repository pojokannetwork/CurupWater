-- Database Setup untuk CurupWater
-- Jalankan script ini untuk membuat database dan tabel-tabel yang diperlukan

CREATE DATABASE IF NOT EXISTS curupwater_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE curupwater_db;

-- Tabel Admin
CREATE TABLE IF NOT EXISTS admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin default (username: admin, password: admin123)
-- Password di-hash dengan PASSWORD_DEFAULT (bcrypt)
INSERT INTO admin (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Tabel Produk
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Keunggulan/Fitur
CREATE TABLE IF NOT EXISTS features (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50) DEFAULT 'fa-check-circle',
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Tentang Kami
CREATE TABLE IF NOT EXISTS about (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default About
INSERT INTO about (title, content) VALUES 
('Tentang CurupWater', 'CurupWater adalah produsen air minum dalam kemasan (AMDK) berkualitas tinggi yang bersumber dari mata air alami Curup. Kami berkomitmen untuk menyediakan air minum yang sehat, segar, dan terjangkau untuk semua kalangan.');

-- Tabel Kontak
CREATE TABLE IF NOT EXISTS contact (
    id INT PRIMARY KEY AUTO_INCREMENT,
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    whatsapp VARCHAR(20),
    instagram VARCHAR(100),
    facebook VARCHAR(100),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default Contact
INSERT INTO contact (phone, email, address, whatsapp, instagram, facebook) VALUES 
('+62 812-3456-7890', 'info@curupwater.com', 'Jl. Raya Curup No. 123, Rejang Lebong, Bengkulu', '+62 812-3456-7890', '@curupwater', 'curupwater.official');

-- Tabel Hero Section
CREATE TABLE IF NOT EXISTS hero (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    subtitle VARCHAR(255),
    button_text VARCHAR(50),
    button_link VARCHAR(255),
    background_image VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default Hero
INSERT INTO hero (title, subtitle, button_text, button_link) VALUES 
('Air Minum Berkualitas dari Curup', 'Kesegaran alami dari mata air pegunungan untuk kesehatan keluarga Indonesia', 'Pesan Sekarang', '#products');

-- Insert sample products
INSERT INTO products (name, description, price, stock) VALUES 
('CurupWater 240ml', 'Kemasan praktis untuk dibawa kemana saja', 1500.00, 1000),
('CurupWater 600ml', 'Ukuran standar untuk konsumsi harian', 3000.00, 800),
('CurupWater 1500ml', 'Kemasan keluarga hemat dan ekonomis', 5000.00, 500),
('CurupWater Galon 19L', 'Air galon untuk rumah dan kantor', 18000.00, 200);

-- Insert sample features
INSERT INTO features (title, description, icon, display_order) VALUES 
('Sumber Air Alami', 'Berasal dari mata air pegunungan Curup yang jernih dan segar', 'fa-mountain', 1),
('Proses Higienis', 'Diproduksi dengan teknologi modern dan standar kebersihan tinggi', 'fa-shield-alt', 2),
('Harga Terjangkau', 'Kualitas terbaik dengan harga yang ramah di kantong', 'fa-tag', 3),
('Nutrisi Terjaga', 'Mengandung mineral alami yang baik untuk kesehatan', 'fa-heart', 4);
