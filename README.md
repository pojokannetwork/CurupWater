# Curup Water

Website AMDK (Air Minum Dalam Kemasan) - Curup Water

## Fitur
- Hero slider dengan animasi
- Katalog produk
- Halaman tentang perusahaan
- Form kontak
- Admin panel untuk mengelola konten

## Instalasi

1. Import database:
```sql
mysql -u root -p < database.sql
```

2. Konfigurasi database di `config/database.php`

3. Login admin:
- URL: http://localhost/CurupWater/admin/
- Username: admin
- Password: admin123

4. Jalankan server:
```bash
php -S localhost:8000
```

## Struktur Folder
```
CurupWater/
├── admin/              # Admin panel
│   ├── pages/          # Halaman admin
│   ├── index.php       # Dashboard
│   ├── login.php       # Login admin
│   └── logout.php      # Logout
├── assets/             # Asset statis
│   ├── css/            # File CSS
│   ├── js/             # File JavaScript
│   └── img/            # Gambar
├── config/             # Konfigurasi
│   └── database.php    # Koneksi database
├── index.php           # Halaman utama
├── submit-contact.php  # Handler form kontak
└── database.sql        # SQL database
```

## Teknologi
- PHP 7.4+
- MySQL/MariaDB
- HTML5, CSS3, JavaScript
- Font Awesome Icons

## Developer
Curup Water Management System
© 2025
