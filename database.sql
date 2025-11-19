-- Database: curupwater
CREATE DATABASE IF NOT EXISTS curupwater;
USE curupwater;

-- Table: admin
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin (username: admin, password: admin123)
INSERT INTO `admin` (`username`, `password`, `email`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@curupwater.com');

-- Table: hero_slides
CREATE TABLE `hero_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `subtitle` text,
  `image` varchar(255) NOT NULL,
  `button_text` varchar(100),
  `button_link` varchar(255),
  `order_num` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample hero slides
INSERT INTO `hero_slides` (`title`, `subtitle`, `image`, `button_text`, `button_link`, `order_num`) VALUES
('Curup Water - Air Mineral Berkualitas', 'Sumber air terbaik dari pegunungan untuk kesehatan keluarga Anda', 'hero1.jpg', 'Lihat Produk', '#products', 1),
('Kesegaran Alami Setiap Tetes', 'Diproduksi dengan teknologi modern dan standar internasional', 'hero2.jpg', 'Tentang Kami', '#about', 2),
('Hidup Sehat Dimulai Dari Air Berkualitas', 'Tersedia dalam berbagai ukuran untuk kebutuhan Anda', 'hero3.jpg', 'Hubungi Kami', '#contact', 3);

-- Table: products
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `size` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `order_num` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample products
INSERT INTO `products` (`name`, `description`, `size`, `price`, `image`, `is_featured`, `order_num`) VALUES
('Curup Water Galon 19L', 'Air mineral galon untuk kebutuhan keluarga. Segel higienis dan kualitas terjamin.', '19 Liter', 25000.00, 'galon-19l.jpg', 1, 1),
('Curup Water Galon 12.8L', 'Air mineral galon ukuran sedang, praktis dan ekonomis.', '12.8 Liter', 18000.00, 'galon-12l.jpg', 1, 2),
('Curup Water Botol 1500ml', 'Botol besar untuk aktivitas seharian. Tutup anti tumpah.', '1.5 Liter', 5000.00, 'botol-1500ml.jpg', 1, 3),
('Curup Water Botol 600ml', 'Ukuran praktis untuk dibawa kemana saja. Desain ergonomis.', '600 ml', 3000.00, 'botol-600ml.jpg', 1, 4),
('Curup Water Gelas 240ml', 'Kemasan gelas untuk kemudahan dan kepraktisan.', '240 ml', 1500.00, 'gelas-240ml.jpg', 0, 5);

-- Table: about
CREATE TABLE `about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255),
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert about content
INSERT INTO `about` (`title`, `content`, `image`) VALUES
('Tentang Curup Water', 'Curup Water adalah produsen air minum dalam kemasan (AMDK) yang berkomitmen menyediakan air mineral berkualitas tinggi untuk masyarakat. Diambil dari sumber mata air pegunungan yang terjaga kemurniannya, setiap tetes Curup Water mengandung mineral alami yang bermanfaat bagi kesehatan.\n\nDengan menggunakan teknologi pemrosesan modern dan standar kebersihan internasional, kami memastikan setiap produk yang sampai ke tangan konsumen adalah yang terbaik. Proses penyaringan bertahap dan sterilisasi dengan sinar UV menjamin air yang aman dan segar.\n\nVisi kami adalah menjadi pilihan utama air minum berkualitas di Indonesia, sementara misi kami adalah menyediakan produk yang tidak hanya menyegarkan, tetapi juga mendukung gaya hidup sehat masyarakat.', 'about.jpg');

-- Table: contact
CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` text NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `whatsapp` varchar(50),
  `facebook` varchar(255),
  `instagram` varchar(255),
  `maps_embed` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert contact info
INSERT INTO `contact` (`address`, `phone`, `email`, `whatsapp`, `facebook`, `instagram`) VALUES
('Jl. Raya Curup No. 123, Rejang Lebong, Bengkulu, Indonesia', '0812-3456-7890', 'info@curupwater.com', '6281234567890', 'https://facebook.com/curupwater', 'https://instagram.com/curupwater');

-- Table: messages
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50),
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
