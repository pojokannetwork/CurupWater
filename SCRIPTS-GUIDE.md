# 📜 Script Documentation - Curup Water

Dokumentasi lengkap semua script otomatis untuk Curup Water Management System.

---

## 📋 Daftar Script

### 🎛️ Control Panel (Recommended!)

| Script | Type | Fungsi |
|--------|------|--------|
| `control-panel.bat` | Interactive Menu | **Menu interaktif untuk semua fungsi** |

**REKOMENDASI**: Untuk pemula atau yang suka GUI sederhana, gunakan `control-panel.bat` yang menyediakan menu interaktif untuk semua fungsi!

### 🚀 Startup Scripts

| Script | Type | Fungsi |
|--------|------|--------|
| `start-all.bat` | Batch | Start MySQL + Apache + PHP Server + Browser |
| `start-all.ps1` | PowerShell | Sama seperti .bat tapi dengan error handling lebih baik |
| `start-mysql.bat` | Batch | Start MySQL Server saja |
| `start-phpmyadmin.bat` | Batch | Start Apache + buka phpMyAdmin |
| `start-server.bat` | Batch | Start PHP Development Server saja (port 8000) |

### 🛑 Stop Scripts

| Script | Type | Fungsi |
|--------|------|--------|
| `stop-all.bat` | Batch | Stop semua service (MySQL, Apache, PHP) |
| `stop-all.ps1` | PowerShell | Stop dengan konfirmasi dan status |

### 🔍 Monitoring Scripts

| Script | Type | Fungsi |
|--------|------|--------|
| `check-services.bat` | Batch | Cek status service running/stopped |
| `check-services.ps1` | PowerShell | Status detail + port monitoring |

### 💾 Database Scripts

| Script | Type | Fungsi |
|--------|------|--------|
| `setup-database.bat` | Batch | Auto create database + import schema |
| `setup-database.ps1` | PowerShell | Sama dengan .bat + error handling |

---

## 📖 Penjelasan Detail

### start-all.bat / start-all.ps1

**Fungsi**: Script paling sering digunakan untuk development sehari-hari.

**Yang Dilakukan**:
1. Cek apakah XAMPP terinstall
2. Stop proses lama yang mungkin masih berjalan
3. Start MySQL Server (port 3306)
4. Start Apache Server untuk phpMyAdmin (port 80/443)
5. Start PHP Dev Server untuk aplikasi (port 8000)
6. Buka browser otomatis ke:
   - http://localhost:8000 (website)
   - http://localhost/phpmyadmin (database manager)

**Cara Pakai**:
```bash
# Double-click file atau via command line:
start-all.bat

# PowerShell (lebih verbose):
.\start-all.ps1
```

**Output**:
```
========================================
 Starting Curup Water Development Server
========================================

[1/4] Starting MySQL Server...
[2/4] Starting Apache Server (for phpMyAdmin)...
[3/4] Starting PHP Development Server (Port 8000)...
[4/4] Opening Application...

========================================
 All Services Started Successfully!
========================================

 Application:  http://localhost:8000
 phpMyAdmin:   http://localhost/phpmyadmin
 Admin Panel:  http://localhost:8000/admin/
```

**Troubleshooting**:
- Jika MySQL gagal start → Port 3306 digunakan aplikasi lain
- Jika Apache gagal start → Port 80 digunakan (Skype/IIS/Apache lain)
- Jika PHP gagal start → Port 8000 digunakan atau PHP tidak ditemukan

---

### stop-all.bat / stop-all.ps1

**Fungsi**: Stop semua service yang berjalan.

**Yang Dilakukan**:
1. Kill process MySQL (mysqld.exe)
2. Kill process Apache (httpd.exe)
3. Kill process PHP (php.exe)
4. Tampilkan konfirmasi

**Cara Pakai**:
```bash
stop-all.bat
# atau
.\stop-all.ps1
```

**Kapan Digunakan**:
- Selesai development
- Mau restart semua service
- Sebelum shutdown komputer
- Troubleshooting (force stop)

---

### check-services.bat / check-services.ps1

**Fungsi**: Monitoring status service tanpa start/stop.

**Yang Dilakukan**:
1. Cek apakah MySQL running
2. Cek apakah Apache running
3. Cek apakah PHP Dev Server running
4. (PS1) Cek status port 3306, 80, 8000

**Output Contoh**:
```
========================================
 Service Status Check
========================================

Checking MySQL...
[RUNNING] MySQL Server

Checking Apache...
[RUNNING] Apache Server (phpMyAdmin)

Checking PHP Dev Server...
[STOPPED] PHP Development Server
```

**Cara Pakai**:
```bash
check-services.bat
# atau untuk detail lebih:
.\check-services.ps1
```

---

### setup-database.bat / setup-database.ps1

**Fungsi**: Auto setup database dari awal (first-time setup).

**Yang Dilakukan**:
1. Cek apakah MySQL running (start jika belum)
2. Buat database 'curupwater' jika belum ada
3. Import file setup.sql
4. Verifikasi tabel sudah terinstall
5. Tampilkan default admin credentials

**Cara Pakai**:
```bash
# First time setup:
setup-database.bat

# PowerShell (recommended):
.\setup-database.ps1
```

**Kapan Digunakan**:
- Instalasi pertama kali
- Reset database ke kondisi awal
- Setelah reinstall XAMPP
- Database corrupt/bermasalah

**Output**:
```
========================================
 Curup Water - Database Setup
========================================

[1/4] Checking MySQL status...
  MySQL is already running
[2/4] Creating database if not exists...
  Database 'curupwater' is ready
[3/4] Checking SQL file...
  SQL file found: C:\xampp\htdocs\CurupWater\setup.sql
[4/4] Importing database structure...
  [SUCCESS] Database imported successfully!

========================================
 Database Setup Complete!
========================================

Default Admin Login:
  Username: admin
  Password: admin123
```

---

### start-mysql.bat

**Fungsi**: Start MySQL Server saja tanpa Apache/PHP.

**Kapan Digunakan**:
- Hanya perlu akses database
- Testing query SQL
- Backup/restore database
- Troubleshooting database

**Cara Pakai**:
```bash
start-mysql.bat
```

---

### start-phpmyadmin.bat

**Fungsi**: Start Apache Server + buka phpMyAdmin di browser.

**Kapan Digunakan**:
- Managemen database via GUI
- Import/export database manual
- Browse tabel dan data
- Run SQL query visual

**Cara Pakai**:
```bash
start-phpmyadmin.bat
```

**Note**: MySQL harus sudah running!

---

### start-server.bat

**Fungsi**: Start PHP Development Server saja (port 8000).

**Kapan Digunakan**:
- MySQL dan Apache sudah running
- Hanya perlu restart PHP server
- Testing perubahan code tanpa restart MySQL/Apache

**Cara Pakai**:
```bash
start-server.bat
```

---

## 🔄 Workflow Rekomendasi

### Development Sehari-hari:

**Mulai Kerja**:
```bash
1. start-all.bat          # Start semua
2. Browser otomatis buka
3. Login ke admin panel
```

**Selesai Kerja**:
```bash
1. stop-all.bat           # Stop semua
2. Selesai!
```

### First Time Setup:

```bash
1. setup-database.bat     # Setup DB otomatis
2. start-all.bat          # Start aplikasi
3. Login dengan admin/admin123
4. Ganti password!
```

### Troubleshooting:

```bash
1. check-services.bat     # Cek status
2. stop-all.bat           # Stop paksa
3. start-all.bat          # Start lagi
```

---

## ⚙️ Konfigurasi

### Ubah Port PHP Dev Server

Edit `start-all.bat` atau `start-server.bat`:
```batch
REM Ganti dari port 8000 ke 8080
php -S localhost:8080
```

### Ubah Path XAMPP

Jika XAMPP tidak di `C:\xampp`, edit setiap script:
```batch
REM Ganti path ini:
set XAMPP_PATH=D:\xampp
```

### Custom MySQL Password

Edit `setup-database.bat`:
```batch
REM Tambahkan password:
mysql -u root -pYOURPASSWORD
```

---

## 🐛 Common Issues

### Issue: "The system cannot find the file php"

**Solusi**: PHP tidak di PATH. Edit script gunakan full path:
```batch
C:\xampp\php\php.exe -S localhost:8000
```

### Issue: MySQL port 3306 already in use

**Solusi**: 
```bash
# Cari process yang pakai port:
netstat -ano | findstr :3306

# Kill process:
taskkill /F /PID <nomor_pid>
```

### Issue: Apache port 80 already in use

**Solusi**: Biasanya Skype atau IIS. Tutup aplikasi tersebut atau:
```bash
# Edit C:\xampp\apache\conf\httpd.conf
Listen 8080  # Ganti dari 80 ke 8080
```

---

## 💡 Tips & Tricks

### 1. Buat Desktop Shortcut
Klik kanan `start-all.bat` → Send to → Desktop (create shortcut)

### 2. Run as Administrator (jika perlu)
Klik kanan script → Run as administrator

### 3. Autostart on Windows Login
Buat shortcut di:
```
C:\Users\<YourName>\AppData\Roaming\Microsoft\Windows\Start Menu\Programs\Startup
```

### 4. Batch vs PowerShell
- **Batch (.bat)**: Cepat, simple, compatible semua Windows
- **PowerShell (.ps1)**: Better error handling, colored output, more info

Untuk PowerShell, mungkin perlu enable execution:
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

---

## 📚 Reference

### Ports Used
- **3306**: MySQL Server
- **80/443**: Apache Server (phpMyAdmin)
- **8000**: PHP Development Server (Aplikasi)

### Process Names
- **mysqld.exe**: MySQL Daemon
- **httpd.exe**: Apache HTTP Server
- **php.exe**: PHP CLI Server

### Key Paths
- XAMPP: `C:\xampp`
- MySQL: `C:\xampp\mysql`
- Apache: `C:\xampp\apache`
- PHP: `C:\xampp\php`
- Project: `C:\xampp\htdocs\CurupWater`

---

**Happy Coding! 🚀**

Last Updated: 2025-11-19
