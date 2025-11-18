# CurupWater - Air Minum Dalam Kemasan (AMDK)

Website dan Dashboard Admin untuk CurupWater, produsen air minum dalam kemasan berkualitas tinggi dari mata air alami Curup.

## 🌟 Fitur Utama

### Landing Page
- **Hero Section** - Header menarik dengan gambar background yang dapat dikustomisasi
- **Produk** - Tampilan produk dengan gambar, deskripsi, dan harga
- **Keunggulan** - Highlight fitur-fitur unggulan perusahaan dengan icon
- **Tentang Kami** - Informasi tentang perusahaan
- **Kontak** - Informasi kontak lengkap dengan integrasi WhatsApp
- **Responsive Design** - Tampilan optimal di semua perangkat

### Admin Dashboard
- **Login Admin** - Sistem autentikasi dengan session management
- **Dashboard** - Statistik dan quick actions
- **Manajemen Produk** - CRUD lengkap dengan upload gambar
- **Manajemen Keunggulan** - CRUD untuk fitur-fitur unggulan
- **Edit Tentang Kami** - Update konten about section
- **Edit Info Kontak** - Update informasi kontak dan social media
- **Edit Hero Section** - Customize hero section landing page
- **Security** - Prepared statements untuk mencegah SQL injection

## 🛠️ Teknologi

- **Backend**: PHP Native (OOP)
- **Database**: MySQL
- **Frontend**: Bootstrap 5, Font Awesome 6
- **Security**: PDO dengan Prepared Statements, Password Hashing
- **Architecture**: Object-Oriented Programming

## 📁 Struktur Folder

```
CurupWater/
├── admin/                  # Admin panel
│   ├── includes/          # PHP classes
│   │   ├── Admin.php      # Class untuk autentikasi admin
│   │   ├── Product.php    # Class untuk manajemen produk
│   │   ├── Feature.php    # Class untuk manajemen keunggulan
│   │   ├── About.php      # Class untuk konten about
│   │   ├── Contact.php    # Class untuk info kontak
│   │   └── Hero.php       # Class untuk hero section
│   ├── pages/             # Halaman admin
│   │   ├── products.php   # Manajemen produk
│   │   ├── features.php   # Manajemen keunggulan
│   │   ├── about.php      # Edit tentang kami
│   │   ├── contact.php    # Edit kontak
│   │   └── hero.php       # Edit hero section
│   ├── index.php          # Dashboard admin
│   ├── login.php          # Halaman login
│   └── logout.php         # Script logout
├── config/
│   └── db.php             # Konfigurasi database (OOP)
├── img/
│   ├── products/          # Folder upload gambar produk
│   └── uploads/           # Folder upload gambar lainnya
├── index.php              # Landing page utama
├── setup.sql              # SQL untuk setup database
└── README.md              # Dokumentasi ini
```

## 🚀 Cara Instalasi

### Prasyarat
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)
- phpMyAdmin (opsional, untuk manajemen database)

### Langkah-langkah Instalasi

#### 1. Clone/Download Repository
```bash
git clone https://github.com/pojokannetwork/CurupWater.git
cd CurupWater
```

#### 2. Setup Database

##### Opsi A: Menggunakan phpMyAdmin
1. Buka phpMyAdmin di browser (biasanya `http://localhost/phpmyadmin`)
2. Buat database baru dengan nama `curupwater_db`
3. Import file `setup.sql`:
   - Klik database `curupwater_db`
   - Klik tab "Import"
   - Pilih file `setup.sql`
   - Klik "Go"

##### Opsi B: Menggunakan Command Line
```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE curupwater_db;

# Import SQL file
mysql -u root -p curupwater_db < setup.sql

# Keluar
exit
```

#### 3. Konfigurasi Database
Edit file `config/db.php` sesuai dengan konfigurasi database Anda:

```php
private $host = 'localhost';       // Host database
private $db_name = 'curupwater_db'; // Nama database
private $username = 'root';         // Username MySQL
private $password = '';             // Password MySQL
```

#### 4. Set Permission Folder Upload
Pastikan folder untuk upload memiliki permission yang tepat:

```bash
chmod 755 img/products/
chmod 755 img/uploads/
```

Untuk Windows, pastikan folder tersebut memiliki write permission.

#### 5. Verifikasi Instalasi
Jalankan installation checker untuk memastikan semua setup benar:
```
http://localhost/CurupWater/install-check.php
```

Script ini akan memeriksa:
- PHP version dan extensions
- Struktur folder dan permissions
- Koneksi database
- Keberadaan tabel-tabel

#### 6. Akses Website

##### Landing Page
Buka browser dan akses:
```
http://localhost/CurupWater/
```

##### Admin Panel
Buka browser dan akses:
```
http://localhost/CurupWater/admin/login.php
```

**Login Default:**
- Username: `admin`
- Password: `admin123`

⚠️ **PENTING**: Segera ubah password default setelah login pertama kali!

## 📝 Cara Penggunaan

### Login ke Admin Panel
1. Akses `http://localhost/CurupWater/admin/login.php`
2. Masukkan username dan password
3. Klik tombol "Login"

### Mengelola Produk
1. Dari dashboard, klik "Produk" di sidebar
2. **Tambah Produk**: Isi form dan upload gambar produk
3. **Edit Produk**: Klik tombol edit (icon pensil) pada produk yang ingin diubah
4. **Hapus Produk**: Klik tombol delete (icon trash) dan konfirmasi penghapusan

### Mengelola Keunggulan
1. Dari dashboard, klik "Keunggulan" di sidebar
2. Isi form dengan judul, deskripsi, dan pilih icon
3. Atur urutan tampil (angka kecil tampil lebih dulu)
4. Edit atau hapus sesuai kebutuhan

### Edit Konten Lainnya
- **Tentang Kami**: Update informasi perusahaan
- **Info Kontak**: Update nomor telepon, email, alamat, dan social media
- **Hero Section**: Customize judul, subtitle, tombol, dan background image

## 🔒 Keamanan

### Fitur Keamanan yang Diimplementasikan:
1. **Prepared Statements** - Mencegah SQL Injection
2. **Password Hashing** - Menggunakan `password_hash()` PHP
3. **Session Management** - Login berbasis session
4. **Input Sanitization** - Membersihkan input user dengan `htmlspecialchars()`
5. **File Upload Validation** - Validasi tipe dan ukuran file
6. **Access Control** - Semua halaman admin memerlukan login

### Best Practices:
- ✅ Ubah password default segera setelah instalasi
- ✅ Gunakan HTTPS di production
- ✅ Backup database secara berkala
- ✅ Update PHP dan MySQL ke versi terbaru
- ✅ Jangan commit file `config/db.php` dengan kredensial asli ke repository publik

## 🌐 Deployment ke Shared Hosting

### Langkah-langkah:

1. **Upload Files**
   - Compress semua file ke format ZIP
   - Upload melalui File Manager cPanel atau FTP
   - Extract di folder `public_html` atau `www`

2. **Setup Database**
   - Buat database baru melalui cPanel MySQL Databases
   - Import file `setup.sql` melalui phpMyAdmin
   - Update `config/db.php` dengan kredensial database hosting

3. **Set Permission**
   ```
   img/products/ → 755
   img/uploads/ → 755
   ```

4. **Testing**
   - Akses website: `http://yourdomain.com`
   - Akses admin: `http://yourdomain.com/admin/login.php`

## 🎨 Kustomisasi

### Mengubah Warna Tema
Edit file `index.php` pada section `<style>`:
```css
:root {
    --primary-color: #667eea;    /* Warna utama */
    --secondary-color: #764ba2;  /* Warna sekunder */
}
```

### Mengubah Logo/Icon
Ganti icon di navbar dengan mengubah:
```html
<i class="fas fa-water me-2"></i>
```

## 📊 Default Data

Database sudah terisi dengan data sample:
- **Admin**: 1 akun (admin/admin123)
- **Produk**: 4 produk sample
- **Keunggulan**: 4 fitur unggulan
- **Konten**: About, Contact, dan Hero section

## 🐛 Troubleshooting

### Error: "Connection Error"
- Periksa konfigurasi database di `config/db.php`
- Pastikan MySQL service berjalan
- Cek username dan password database

### Error: "Failed to open stream"
- Periksa permission folder `img/products/` dan `img/uploads/`
- Pastikan path folder benar

### Gambar tidak muncul
- Periksa apakah gambar ter-upload dengan benar
- Cek permission folder upload
- Periksa path gambar di database

### Session/Login tidak bekerja
- Pastikan PHP session enabled
- Cek `session_start()` dipanggil di awal file
- Periksa cookie settings di browser

## 📞 Support

Jika ada pertanyaan atau masalah:
- Buat issue di GitHub repository
- Email: info@curupwater.com

## 📄 License

© 2024 CurupWater. All Rights Reserved.

## 🙏 Credits

- Bootstrap 5: https://getbootstrap.com
- Font Awesome: https://fontawesome.com
- PHP: https://php.net

---

**Dibuat dengan ❤️ untuk CurupWater**