# CurupWater - Quick Start Guide

## 🚀 Panduan Cepat untuk Memulai

### Langkah 1: Persiapan Environment
```bash
# Pastikan Anda memiliki:
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx web server
- phpMyAdmin (optional)
```

### Langkah 2: Setup Database (5 menit)
```bash
# Opsi 1: Via phpMyAdmin
1. Buka http://localhost/phpmyadmin
2. Klik "New" untuk create database
3. Nama: curupwater_db
4. Klik "Import" tab
5. Pilih file setup.sql
6. Klik "Go"

# Opsi 2: Via Command Line
mysql -u root -p
CREATE DATABASE curupwater_db;
exit
mysql -u root -p curupwater_db < setup.sql
```

### Langkah 3: Konfigurasi Database (2 menit)
```php
// Edit config/db.php
private $host = 'localhost';       // biasanya 'localhost'
private $db_name = 'curupwater_db'; // nama database
private $username = 'root';         // username MySQL Anda
private $password = '';             // password MySQL Anda (kosong untuk XAMPP)
```

### Langkah 4: Set Permissions (Windows: skip step ini)
```bash
chmod 755 img/products/
chmod 755 img/uploads/
```

### Langkah 5: Verifikasi Instalasi
```
Akses: http://localhost/CurupWater/install-check.php
```
Pastikan semua checklist hijau ✓

### Langkah 6: Login & Mulai Menggunakan
```
Landing Page: http://localhost/CurupWater/
Admin Panel:  http://localhost/CurupWater/admin/login.php

Default Login:
Username: admin
Password: admin123
```

## ⚡ Quick Actions Setelah Login

### 1. Ubah Password Default
- Login ke admin panel
- Untuk keamanan, segera ubah password default

### 2. Tambah Produk Pertama
```
1. Klik "Produk" di sidebar
2. Isi form:
   - Nama: CurupWater 600ml
   - Deskripsi: Air minum kemasan ukuran standar
   - Harga: 3000
   - Stok: 100
3. Upload gambar produk
4. Klik "Simpan"
```

### 3. Edit Hero Section
```
1. Klik "Hero Section" di sidebar
2. Ubah judul dan subtitle sesuai keinginan
3. Upload background image (1920x1080px recommended)
4. Klik "Update"
```

### 4. Update Info Kontak
```
1. Klik "Kontak" di sidebar
2. Isi nomor WhatsApp, email, alamat
3. Isi username Instagram & Facebook
4. Klik "Update"
```

### 5. Lihat Hasil di Landing Page
```
Klik "Lihat Website" di sidebar admin
atau buka: http://localhost/CurupWater/
```

## 🎯 Tips & Tricks

### Upload Gambar
- **Format**: JPG, JPEG, PNG, GIF
- **Ukuran**: Maksimal 2MB
- **Resolusi Produk**: 500x500px (square recommended)
- **Resolusi Hero**: 1920x1080px (landscape)

### Icon Selection
Untuk keunggulan/fitur, gunakan icon Font Awesome:
- fa-mountain (gunung)
- fa-shield-alt (perisai)
- fa-heart (hati)
- fa-tag (label harga)
- fa-water (air)
- fa-leaf (daun)

### Urutan Tampil
Untuk fitur/keunggulan, gunakan display_order:
- 1 = tampil paling awal
- 2, 3, 4... = tampil berikutnya
- Angka lebih kecil = posisi lebih awal

## 🔧 Troubleshooting Cepat

### Error: "Connection Error"
```
Solusi:
1. Cek apakah MySQL service running
2. Cek kredensial di config/db.php
3. Pastikan database curupwater_db sudah dibuat
```

### Error: "Failed to open stream"
```
Solusi:
1. Set permission folder:
   chmod 755 img/products/
   chmod 755 img/uploads/
2. Atau via File Manager: klik kanan > Properties > Permissions
```

### Gambar tidak muncul
```
Solusi:
1. Cek apakah file ter-upload di img/products/
2. Cek permission folder (755)
3. Refresh browser dengan Ctrl+F5
```

### Session/Login tidak work
```
Solusi:
1. Hapus cookies browser
2. Cek apakah session_start() enabled di PHP
3. Restart web server
```

## 📱 Testing di Mobile

### Menggunakan Ngrok (optional)
```bash
# Install ngrok: https://ngrok.com/
ngrok http 80

# Akses URL yang diberikan dari smartphone
https://xxxxx.ngrok.io/CurupWater/
```

### Menggunakan IP Lokal
```bash
# Cek IP komputer Anda
ipconfig (Windows)
ifconfig (Linux/Mac)

# Akses dari smartphone (harus 1 jaringan WiFi)
http://192.168.x.x/CurupWater/
```

## 🚀 Deploy ke Hosting

### 1. Persiapan File
```bash
# Compress semua file ke ZIP
# Exclude: .git, .gitignore, install-check.php, TESTING.md
```

### 2. Upload ke Hosting
```
Via cPanel File Manager:
1. Upload ZIP file
2. Extract di public_html atau www
3. Atau upload via FTP (FileZilla)
```

### 3. Setup Database di Hosting
```
1. cPanel > MySQL Databases
2. Create new database
3. Create user dan assign ke database
4. phpMyAdmin > Import setup.sql
5. Update config/db.php dengan kredensial baru
```

### 4. Final Checks
```
✓ Akses website: http://yourdomain.com
✓ Test admin login
✓ Test CRUD operations
✓ Test image upload
✓ Test di mobile browser
```

## 📊 Default Data

Setelah import setup.sql, database terisi dengan:
- **1 Admin Account**: admin/admin123
- **4 Sample Products**: 240ml, 600ml, 1500ml, Galon 19L
- **4 Sample Features**: Sumber alami, Proses higienis, Harga terjangkau, Nutrisi terjaga
- **Default About**: Informasi tentang CurupWater
- **Default Contact**: Contoh informasi kontak
- **Default Hero**: Judul dan subtitle untuk hero section

## 🎨 Customization Ideas

### Warna Tema
Edit di `index.php` section `<style>`:
```css
:root {
    --primary-color: #667eea;    /* Ganti dengan warna brand Anda */
    --secondary-color: #764ba2;
}
```

### Font
Tambahkan Google Fonts di `<head>`:
```html
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
```

### Logo
Ganti icon di navbar:
```html
<i class="fas fa-water me-2"></i> <!-- Ganti dengan logo Anda -->
```

## 📞 Need Help?

- 📧 Email: info@curupwater.com
- 📝 GitHub Issues: Create an issue
- 💬 WhatsApp: Hubungi admin

## ✅ Checklist Sebelum Go Live

```
□ Database sudah di-setup
□ Password admin sudah diubah
□ Semua produk sudah ditambahkan dengan gambar
□ Info kontak sudah diupdate
□ Hero section sudah dikustomisasi
□ About section sudah diedit
□ Testing di berbagai device (desktop, tablet, mobile)
□ Testing di berbagai browser (Chrome, Firefox, Safari)
□ Social media links sudah benar
□ WhatsApp number sudah benar (dengan kode negara)
□ Backup database sudah dibuat
□ File config/db.php tidak ter-commit ke public repo
□ install-check.php sudah dihapus dari production
□ SSL certificate sudah diinstall (HTTPS)
```

## 🎉 Selamat!

Website CurupWater Anda sudah siap digunakan!

---

**Happy Coding! 🚀**
