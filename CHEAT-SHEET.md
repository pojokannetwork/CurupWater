# 📋 Curup Water - Cheat Sheet

Quick reference untuk developer Curup Water Management System.

---

## ⚡ Quick Commands

### Most Used (Copy-Paste Ready)

```bash
# Start Everything
start-all.bat

# Control Panel (Menu)
control-panel.bat

# Setup Database First Time
setup-database.bat

# Stop Everything
stop-all.bat

# Check Status
check-services.bat
```

---

## 🔗 Quick URLs

```
Website:        http://localhost:8000
Admin Login:    http://localhost:8000/admin/
phpMyAdmin:     http://localhost/phpmyadmin
Install Check:  http://localhost:8000/install-check.php
```

---

## 🔑 Default Credentials

```
Super Admin:
  Username: admin
  Password: admin123

MySQL:
  Username: root
  Password: (empty)

Database: curupwater
```

---

## 📂 Important Paths

```bash
Project:    C:\xampp\htdocs\CurupWater
MySQL:      C:\xampp\mysql\bin
Apache:     C:\xampp\apache\bin
PHP:        C:\xampp\php
Logs:       C:\xampp\mysql\data (MySQL)
            C:\xampp\apache\logs (Apache)
```

---

## 🎯 Common Tasks

### Add New Admin User
```sql
INSERT INTO admin (username, password, role) 
VALUES ('newuser', MD5('password123'), 'admin');
```

### Reset Admin Password
```sql
UPDATE admin 
SET password = MD5('newpassword') 
WHERE username = 'admin';
```

### Clear All Sales Data
```sql
TRUNCATE TABLE sales;
```

### Export Database
```bash
C:\xampp\mysql\bin\mysqldump -u root curupwater > backup.sql
```

### Import Database
```bash
C:\xampp\mysql\bin\mysql -u root curupwater < backup.sql
```

---

## 🐛 Quick Fixes

### MySQL Won't Start
```bash
netstat -ano | findstr :3306
taskkill /F /PID <pid_number>
```

### Apache Won't Start
```bash
netstat -ano | findstr :80
taskkill /F /PID <pid_number>
```

### PHP Server Port Busy
```bash
netstat -ano | findstr :8000
taskkill /F /PID <pid_number>
```

### Kill All Services
```bash
taskkill /F /IM mysqld.exe
taskkill /F /IM httpd.exe
taskkill /F /IM php.exe
```

---

## 📊 Database Quick Queries

### Total Sales Today
```sql
SELECT SUM(total_price) as revenue, COUNT(*) as transactions
FROM sales 
WHERE DATE(sale_date) = CURDATE();
```

### Low Stock Products
```sql
SELECT p.name, ps.stock_quantity, ps.min_stock
FROM product_stock ps
JOIN products p ON ps.product_id = p.id
WHERE ps.stock_quantity <= ps.min_stock;
```

### Top 5 Products
```sql
SELECT p.name, SUM(s.quantity) as total_sold, SUM(s.total_price) as revenue
FROM sales s
JOIN products p ON s.product_id = p.id
GROUP BY s.product_id
ORDER BY revenue DESC
LIMIT 5;
```

### Monthly Revenue Trend
```sql
SELECT DATE_FORMAT(sale_date, '%Y-%m') as month, 
       SUM(total_price) as revenue,
       COUNT(*) as transactions
FROM sales 
WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY month 
ORDER BY month ASC;
```

---

## 🔧 File Locations

### Configuration
```
Database Config:  config/database.php
Admin CSS:        assets/css/admin.css
Main CSS:         assets/css/style.css
```

### Admin Pages
```
Dashboard Analytics:  admin/pages/app-dashboard.php
User Management:      admin/pages/app-management.php
Content Dashboard:    admin/index.php
Login:                admin/login.php
```

### Public Pages
```
Homepage:     index.php
Contact Form: submit-contact.php
```

---

## 🎨 CSS Classes

### Stat Cards
```html
<div class="stat-card primary">      <!-- Blue -->
<div class="stat-card success">      <!-- Green -->
<div class="stat-card warning">      <!-- Yellow -->
<div class="stat-card info">         <!-- Purple -->
```

### Badges
```html
<span class="badge badge-gold">      <!-- Super Admin -->
<span class="badge badge-orange">    <!-- App Admin -->
<span class="badge badge-blue">      <!-- Admin Website -->
<span class="badge badge-warning">   <!-- Low Stock -->
<span class="badge badge-danger">    <!-- Out of Stock -->
```

---

## 🚀 Development Workflow

### Daily Start
```bash
1. control-panel.bat
2. Select [1] Start All Services
3. Browser opens automatically
4. Login to admin panel
```

### Daily End
```bash
1. control-panel.bat
2. Select [2] Stop All Services
```

### Add New Feature
```bash
1. Create branch: git checkout -b feature-name
2. Code your changes
3. Test locally
4. Commit: git commit -am "Description"
5. Push: git push origin feature-name
```

---

## 📱 Keyboard Shortcuts (Control Panel)

```
1 = Start All
2 = Stop All
3 = Check Status
4 = Setup DB
5 = MySQL Only
6 = phpMyAdmin Only
7 = PHP Server Only
8 = Open App
9 = Open Admin
0 = Open phpMyAdmin
Q = Quit
```

---

## 🔒 Security Checklist

```
✅ Change default admin password
✅ Use environment variables for DB credentials
✅ Enable HTTPS in production
✅ Implement prepared statements (upgrade)
✅ Add CSRF tokens
✅ Sanitize all user inputs
✅ Regular database backups
✅ Keep XAMPP updated
```

---

## 📦 File Upload Limits

```
PHP Settings (php.ini):
- upload_max_filesize = 64M
- post_max_size = 64M
- max_file_uploads = 20

Recommended Image Sizes:
- Hero Slider: 1920x1080px
- Products: 800x800px
- Gallery Photos: 1200x800px
```

---

## 🎓 Role Permissions Quick Reference

| Feature | Super | App | Admin |
|---------|:-----:|:---:|:-----:|
| Analytics Dashboard | ✅ | ✅ | ❌ |
| User Management | ✅ | ✅* | ❌ |
| Sales & Inventory | ✅ | ✅ | ❌ |
| Content Management | ✅ | ❌ | ✅ |
| Hero Slider | ✅ | ❌ | ✅ |
| Products | ✅ | ❌ | ✅ |
| Gallery | ✅ | ❌ | ✅ |
| Messages | ✅ | ❌ | ✅ |

\* App Admin cannot edit Super Admin accounts

---

## 💡 Pro Tips

```
✅ Create desktop shortcut to control-panel.bat
✅ Bookmark admin URLs in browser
✅ Use PowerShell scripts for better logging
✅ Regular database exports (weekly)
✅ Keep backup of uploads folder
✅ Use .gitignore for uploads/
✅ Document custom SQL queries
✅ Test on different browsers
```

---

## 📞 Support Resources

```
Documentation:      See *.md files in project root
Script Help:        SCRIPTS-GUIDE.md
Architecture:       ARCHITECTURE.md
Quick Start:        QUICK-START.md
Full Guide:         README.md
```

---

**Last Updated**: 2025-11-19
**Version**: 1.0

---

**Print this page and keep it near your desk for quick reference! 📌**
