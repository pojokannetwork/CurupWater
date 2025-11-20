# 🚀 QUICK START GUIDE - Curup Water

## Cara Tercepat Memulai (3 Langkah)

### 1️⃣ Pastikan XAMPP Terinstall
- Jika belum, download dari: https://www.apachefriends.org/
- Install ke folder default: `C:\xampp`

### 2️⃣ Import Database (Pilih Salah Satu)

**Opsi A: Otomatis (TERCEPAT! ⚡)**
```bash
# Double-click salah satu:
setup-database.bat    # Batch file version
setup-database.ps1    # PowerShell version (recommended)
```
Script akan otomatis:
- ✅ Start MySQL jika belum running
- ✅ Buat database 'curupwater'
- ✅ Import semua tabel dan data

**Opsi B: Via Command Line (Manual)**
```bash
C:\xampp\mysql\bin\mysql -u root -p
# Setelah masuk MySQL, ketik:
CREATE DATABASE IF NOT EXISTS curupwater;
USE curupwater;
SOURCE C:\xampp\htdocs\CurupWater\setup.sql;
exit;
```

**Opsi C: Via phpMyAdmin (Visual)**
1. Double-click `start-phpmyadmin.bat`
2. Buka browser otomatis ke phpMyAdmin
3. Klik tab "Import"
4. Pilih file `setup.sql`
5. Klik "Go"

### 3️⃣ Jalankan Aplikasi
Double-click file:
```
start-all.bat
```

Browser akan otomatis membuka:
- ✅ Website: http://localhost:8000
- ✅ Admin Panel: http://localhost:8000/admin/
- ✅ phpMyAdmin: http://localhost/phpmyadmin

---

## 🔑 Login Pertama Kali

Gunakan akun Super Admin:
- **URL**: http://localhost:8000/admin/
- **Username**: `admin`
- **Password**: `admin123`

⚠️ **PENTING**: Ganti password setelah login pertama!

---

## 🎮 Perintah Script

| Script | Fungsi |
|--------|--------|
| `start-all.bat` | Jalankan SEMUA service + buka browser |
| `stop-all.bat` | Stop SEMUA service |
| `check-services.bat` | Cek status service |
| `start-mysql.bat` | Start MySQL saja |
| `start-phpmyadmin.bat` | Start phpMyAdmin saja |
| `start-server.bat` | Start website saja |

---

## ⚡ Mulai Kerja Sehari-hari

Setiap kali mau kerja dengan aplikasi:
1. Double-click `start-all.bat`
2. Tunggu browser terbuka otomatis
3. Login ke admin panel
4. Selesai!

Setiap kali selesai kerja:
1. Double-click `stop-all.bat`
2. Tunggu semua service berhenti
3. Selesai!

---

## ❓ Troubleshooting

### Problem: MySQL tidak bisa start
**Solusi**: Port 3306 mungkin dipakai aplikasi lain
```bash
netstat -ano | findstr :3306
# Catat PID yang muncul, lalu:
taskkill /F /PID <nomor_pid>
```

### Problem: Apache tidak bisa start
**Solusi**: Port 80 atau 443 dipakai (biasanya Skype/IIS)
- Tutup aplikasi yang menggunakan port tersebut
- ATAU edit port Apache di `C:\xampp\apache\conf\httpd.conf`

### Problem: Website error/blank
**Solusi**: Cek database sudah diimport atau belum
```bash
# Test koneksi database:
start http://localhost:8000/install-check.php
```

### Problem: Lupa password admin
**Solusi**: Reset via MySQL
```bash
C:\xampp\mysql\bin\mysql -u root -p curupwater
# Ketik:
UPDATE admin SET password = MD5('admin123') WHERE username = 'admin';
```

---

## 📱 Akses Cepat

Bookmark link ini di browser Anda:

- 🌐 **Website**: http://localhost:8000
- 🔐 **Admin Panel**: http://localhost:8000/admin/
- 💾 **phpMyAdmin**: http://localhost/phpmyadmin
- ✅ **Test Install**: http://localhost:8000/install-check.php

---

## 🎯 Next Steps

Setelah berhasil login ke admin panel:

1. **Kelola User** (Super Admin)
   - Buat akun Admin Aplikasi (analytics)
   - Buat akun Admin Website (content)

2. **Setup Content** (Admin Website)
   - Upload Hero Images
   - Tambah Product
   - Update About page
   - Set Contact info

3. **Monitor Sales** (Admin Aplikasi)
   - Lihat dashboard analytics
   - Input transaksi penjualan
   - Pantau inventory/stok

---

## 📚 Dokumentasi Lengkap

Lihat file berikut untuk info lebih detail:
- `README.md` - Dokumentasi lengkap
- `INSTALLATION-GUIDE.md` - Panduan instalasi detail
- `TESTING.md` - Panduan testing fitur

---

💡 **Tips**: Simpan shortcut `start-all.bat` di Desktop untuk akses cepat!

**Happy Coding! 🚀**
