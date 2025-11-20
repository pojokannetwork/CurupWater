# Curup Water - Website Perusahaan Air Minum Dalam Kemasan

Website modern dan responsif untuk Curup Water, perusahaan AMDK lokal dengan sistem manajemen lengkap.

## 🚀 Quick Start (Satu Klik)

### Cara Termudah - Control Panel
```bash
control-panel.bat       # Menu interaktif untuk semua fungsi
```

### Cara Cepat - Auto Start
```bash
start-all.bat           # Langsung start semua service + browser
```

Script akan otomatis:
- ✅ Start MySQL Server
- ✅ Start Apache (phpMyAdmin)
- ✅ Start PHP Dev Server (Port 8000)
- ✅ Buka Application & phpMyAdmin di browser

> 💡 **First Time Setup?** Jalankan `setup-database.bat` dulu untuk import database!

## Fitur Aplikasi

### Website Public
- **Hero Section** dengan slider dinamis
- **Produk Showcase** dengan kategori
- **Galeri Foto & Video**
- **Tentang Kami**
- **Kontak & Form**

### Admin Panel (3-Tier Role System)
- **Super Admin** - Full access semua fitur
- **Admin Aplikasi** - Dashboard analytics + user management
- **Admin Website** - Managemen konten website saja

### Dashboard Analytics
- 📊 Trend penjualan 6 bulan
- 💰 Revenue tracking (hari, bulan, tahun)
- 📦 Inventory & stock alerts
- 🏆 Top selling products
- 📋 Transaction history

## 📥 Instalasi

### Prerequisites
- XAMPP (dengan MySQL dan Apache)
- PHP 7.4 atau lebih tinggi
- Browser modern (Chrome, Firefox, Edge)

### Langkah Instalasi

1. **Install XAMPP**
   - Download dari [apachefriends.org](https://www.apachefriends.org/)
   - Install ke `C:\xampp`

2. **Setup Project**
   ```bash
   # Clone atau copy project ke folder htdocs
   cd C:\xampp\htdocs
   git clone <repository-url> CurupWater
   cd CurupWater
   ```

3. **Import Database**
   - Jalankan MySQL: `start-mysql.bat`
   - Import via command line:
     ```bash
     C:\xampp\mysql\bin\mysql -u root -p curupwater < setup.sql
     ```
   - ATAU via phpMyAdmin:
     ```bash
     start-phpmyadmin.bat
     # Import file setup.sql
     ```

4. **Jalankan Aplikasi**
   ```bash
   start-all.bat
   ```

5. **Akses Aplikasi**
   - Website: http://localhost:8000
   - Admin Panel: http://localhost:8000/admin/
   - phpMyAdmin: http://localhost/phpmyadmin

## 🎮 Script Commands

### All-in-One Commands

**Batch Files (Double-click):**
```bash
start-all.bat       # Start semua service + buka browser
stop-all.bat        # Stop semua service
check-services.bat  # Cek status service
```

**PowerShell (Lebih Advanced):**
```powershell
.\start-all.ps1        # Start dengan error handling & status check
.\stop-all.ps1         # Stop dengan konfirmasi
.\check-services.ps1   # Status detail dengan port monitoring
```

### Individual Services
```bash
start-mysql.bat        # Start MySQL saja
start-phpmyadmin.bat   # Start Apache + buka phpMyAdmin
start-server.bat       # Start PHP dev server saja
```

> 💡 **Tip**: Untuk kemudahan, buat shortcut `start-all.bat` di Desktop Anda!

## Struktur Folder
```
CurupWater/
├── admin/                      # Admin panel
│   ├── pages/                  # Halaman admin
│   │   ├── app-dashboard.php       # Dashboard Analytics (sales/inventory)
│   │   ├── app-management.php      # User Management
│   │   ├── products.php            # Product Management
│   │   ├── hero.php                # Hero Slider Management
│   │   └── ...
│   ├── index.php               # Website Content Dashboard
│   ├── login.php               # Login dengan role system
│   └── logout.php              # Logout
├── assets/                     # Asset statis
│   ├── css/                    # File CSS
│   ├── js/                     # File JavaScript
│   └── img/                    # Gambar
├── config/                     # Konfigurasi
│   └── database.php            # Koneksi database
├── index.php                   # Halaman utama website
├── setup.sql                   # SQL database setup
│
├── Automation Scripts:
├── start-all.bat ⚡            # Start semua service (Batch)
├── start-all.ps1               # Start semua service (PowerShell)
├── stop-all.bat                # Stop semua service (Batch)
├── stop-all.ps1                # Stop semua service (PowerShell)
├── start-mysql.bat             # Start MySQL saja
├── start-phpmyadmin.bat        # Start Apache + phpMyAdmin
├── start-server.bat            # Start PHP dev server saja
├── check-services.bat          # Cek status service (Batch)
├── check-services.ps1          # Cek status service (PowerShell)
├── setup-database.bat ⚡       # Auto setup database (Batch)
└── setup-database.ps1          # Auto setup database (PowerShell)
│
├── Documentation:
├── README.md ⚡                 # Dokumentasi utama (file ini)
├── QUICK-START.md ⚡            # Panduan cepat untuk pemula
├── SCRIPTS-GUIDE.md            # Dokumentasi lengkap automation scripts
├── ARCHITECTURE.md             # System architecture & flow diagrams
├── CHEAT-SHEET.md              # Quick reference untuk developer
├── INSTALLATION-GUIDE.md       # Panduan instalasi detail
└── TESTING.md                  # Panduan testing fitur
```

⚡ = **Recommended files untuk quick start**

## 🔐 Login Admin

Default credentials:
- **Super Admin**
  - Username: `admin`
  - Password: `admin123`
  - Access: Full control semua fitur

- **Admin Aplikasi**
  - Create via Super Admin di User Management
  - Access: Dashboard Analytics + User Management (tidak bisa edit Super Admin)

- **Admin Website**
  - Create via Super Admin/Admin Aplikasi
  - Access: Content management saja (Hero, Products, Gallery, etc)

## 📊 Database Tables

- `admin` - User accounts dengan role system
- `sales` - Transaction records
- `product_stock` - Inventory management
- `products` - Product catalog
- `hero_slides` - Homepage slider
- `gallery_photos` - Photo gallery
- `gallery_videos` - Video gallery
- `messages` - Contact form submissions
- `about` - About page content
- `contact` - Contact information

## 📚 Dokumentasi Lengkap

Untuk informasi lebih detail, lihat file dokumentasi berikut:

| Dokumentasi | Deskripsi |
|-------------|-----------|
| 📖 [README.md](README.md) | Dokumentasi utama (file ini) |
| ⚡ [QUICK-START.md](QUICK-START.md) | **Panduan cepat untuk pemula** |
| 📜 [SCRIPTS-GUIDE.md](SCRIPTS-GUIDE.md) | Dokumentasi semua automation scripts |
| 🏗️ [ARCHITECTURE.md](ARCHITECTURE.md) | Arsitektur sistem dan flow diagram |
| 📥 [INSTALLATION-GUIDE.md](INSTALLATION-GUIDE.md) | Panduan instalasi detail |
| 🧪 [TESTING.md](TESTING.md) | Panduan testing fitur |

---

## 🛠️ Troubleshooting

### MySQL tidak bisa start
```bash
# Cek apakah port 3306 digunakan
netstat -ano | findstr :3306

# Kill proses jika perlu
taskkill /F /PID <process_id>
```

### Apache tidak bisa start
```bash
# Cek port 80 atau 443
netstat -ano | findstr :80
netstat -ano | findstr :443

# Atau ubah port di C:\xampp\apache\conf\httpd.conf
```

### Port 8000 sudah digunakan
```bash
# Cek proses
netstat -ano | findstr :8000

# Kill proses
taskkill /F /PID <process_id>

# Atau ubah port di start-server.bat
php -S localhost:8080  # Gunakan port lain
```

### Database tidak terkoneksi
1. Pastikan MySQL running: `check-services.bat`
2. Cek credentials di `config/database.php`
3. Test koneksi: http://localhost:8000/install-check.php

## Teknologi
- PHP 7.4+
- MySQL/MariaDB
- HTML5, CSS3, JavaScript
- Chart.js (Analytics Dashboard)
- Font Awesome Icons

## Developer
Curup Water Management System
© 2025

