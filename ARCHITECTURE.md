# 🏗️ Arsitektur Sistem - Curup Water

## 📊 System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    USER INTERFACES                           │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Website    │  │ Admin Panel  │  │  phpMyAdmin  │      │
│  │  (Public)    │  │ (Protected)  │  │  (Database)  │      │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘      │
│         │                  │                  │               │
└─────────┼──────────────────┼──────────────────┼──────────────┘
          │                  │                  │
          ▼                  ▼                  ▼
┌─────────────────────────────────────────────────────────────┐
│                  WEB SERVER LAYER                            │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌────────────────────────────────────────────────────┐     │
│  │        PHP Development Server (Port 8000)          │     │
│  │                                                      │     │
│  │  • Routing                                          │     │
│  │  • Session Management                               │     │
│  │  • Authentication & Authorization                   │     │
│  │  • File Upload Handling                             │     │
│  └────────────────┬───────────────────────────────────┘     │
│                   │                                           │
│  ┌────────────────┴───────────────────────────────────┐     │
│  │        Apache Server (Port 80/443)                 │     │
│  │        (for phpMyAdmin only)                        │     │
│  └────────────────────────────────────────────────────┘     │
│                                                               │
└─────────────────────────────┬───────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                  APPLICATION LAYER                           │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────┐  ┌──────────────────┐                │
│  │  Public Module   │  │   Admin Module   │                │
│  ├──────────────────┤  ├──────────────────┤                │
│  │ • Hero Slider    │  │ • Auth System    │                │
│  │ • Product List   │  │ • Role-Based     │                │
│  │ • Gallery        │  │   Access Control │                │
│  │ • About Us       │  │ • Content Mgmt   │                │
│  │ • Contact Form   │  │ • Analytics      │                │
│  └────────┬─────────┘  └─────────┬────────┘                │
│           │                       │                           │
│           └───────────┬───────────┘                           │
│                       │                                       │
└───────────────────────┼───────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────┐
│                  DATABASE LAYER                              │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌────────────────────────────────────────────────────┐     │
│  │         MySQL Server (Port 3306)                   │     │
│  │         Database: curupwater                        │     │
│  ├────────────────────────────────────────────────────┤     │
│  │                                                      │     │
│  │  Content Tables:           Analytics Tables:       │     │
│  │  • admin                   • sales                 │     │
│  │  • products                • product_stock         │     │
│  │  • hero_slides                                      │     │
│  │  • gallery_photos          Communication:          │     │
│  │  • gallery_videos          • messages              │     │
│  │  • about                   • contact               │     │
│  │                                                      │     │
│  └────────────────────────────────────────────────────┘     │
│                                                               │
└───────────────────────────────────────────────────────────────┘
```

---

## 🔐 User Role System

```
┌──────────────────────────────────────────────────────┐
│                   LOGIN PAGE                         │
│              (admin/login.php)                       │
└────────────────────┬─────────────────────────────────┘
                     │
                     ▼
         ┌───────────────────────┐
         │  Validate Credentials  │
         │  Check Role Type       │
         └───────────┬────────────┘
                     │
         ┌───────────┴───────────┐
         │                       │
         ▼                       ▼
┌─────────────────┐    ┌─────────────────┐
│  Super Admin    │    │  Admin Website  │
│  Admin Aplikasi │    │                 │
└────────┬────────┘    └────────┬────────┘
         │                      │
         ▼                      ▼
┌─────────────────┐    ┌─────────────────┐
│ App Dashboard   │    │ Content Dashboard│
│ (Analytics)     │    │ (Website Mgmt)  │
└─────────────────┘    └─────────────────┘

Permissions Matrix:

Feature                    Super    App      Admin
                          Admin    Admin    Website
────────────────────────────────────────────────────
Dashboard Analytics         ✅       ✅        ❌
User Management            ✅       ✅*       ❌
Sales Tracking             ✅       ✅        ❌
Inventory Management       ✅       ✅        ❌
Hero Slider Management     ✅       ❌        ✅
Product Management         ✅       ❌        ✅
Gallery Management         ✅       ❌        ✅
About/Contact Edit         ✅       ❌        ✅
Messages View              ✅       ❌        ✅

* App Admin can manage users but cannot edit Super Admin accounts
```

---

## 🗂️ Database Schema

```
admin
├── id (PK, INT, AUTO_INCREMENT)
├── username (VARCHAR 50, UNIQUE)
├── password (VARCHAR 255, MD5)
├── role (ENUM: super_admin, app_admin, admin)
└── created_at (TIMESTAMP)

products
├── id (PK, INT)
├── name (VARCHAR 100)
├── description (TEXT)
├── price (DECIMAL 10,2)
├── image (VARCHAR 255)
├── is_active (BOOLEAN)
└── created_at (TIMESTAMP)

product_stock (NEW)
├── id (PK, INT)
├── product_id (FK → products.id, UNIQUE)
├── stock_quantity (INT)
├── min_stock (INT, DEFAULT 10)
├── last_restock_date (DATE)
└── updated_at (TIMESTAMP)

sales (NEW)
├── id (PK, INT)
├── product_id (FK → products.id)
├── quantity (INT)
├── total_price (DECIMAL 10,2)
├── sale_date (DATE)
├── customer_name (VARCHAR 100)
├── notes (TEXT)
└── created_at (TIMESTAMP)

hero_slides
├── id (PK, INT)
├── title (VARCHAR 100)
├── subtitle (VARCHAR 200)
├── image (VARCHAR 255)
├── is_active (BOOLEAN)
└── created_at (TIMESTAMP)

gallery_photos
├── id (PK, INT)
├── title (VARCHAR 100)
├── image (VARCHAR 255)
├── is_active (BOOLEAN)
└── created_at (TIMESTAMP)

gallery_videos
├── id (PK, INT)
├── title (VARCHAR 100)
├── youtube_url (VARCHAR 255)
├── is_active (BOOLEAN)
└── created_at (TIMESTAMP)

messages
├── id (PK, INT)
├── name (VARCHAR 100)
├── email (VARCHAR 100)
├── phone (VARCHAR 20)
├── message (TEXT)
└── created_at (TIMESTAMP)

about
├── id (PK, INT, ALWAYS = 1)
├── content (TEXT)
└── updated_at (TIMESTAMP)

contact
├── id (PK, INT, ALWAYS = 1)
├── address (TEXT)
├── phone (VARCHAR 20)
├── email (VARCHAR 100)
├── maps_url (TEXT)
└── updated_at (TIMESTAMP)
```

---

## 🔄 Request Flow

### Public Website Request

```
User Browser
    │
    ▼
http://localhost:8000/index.php
    │
    ▼
PHP Development Server
    │
    ├─→ Read hero_slides (is_active = 1)
    ├─→ Read products (is_active = 1)
    ├─→ Read gallery_photos (is_active = 1)
    ├─→ Read gallery_videos (is_active = 1)
    ├─→ Read about content
    └─→ Read contact info
    │
    ▼
Render HTML with data
    │
    ▼
Send to User Browser
```

### Admin Login Flow

```
User → login.php
    │
    ▼
POST: username + password
    │
    ▼
Validate in admin table
    │
    ├─→ Invalid → Redirect back with error
    │
    └─→ Valid
        │
        ├─→ Check role
        │   ├─→ super_admin/app_admin → app-dashboard.php
        │   └─→ admin → index.php
        │
        ├─→ Create session
        │   ├─→ $_SESSION['admin_logged_in'] = true
        │   ├─→ $_SESSION['admin_username'] = $username
        │   └─→ $_SESSION['admin_role'] = $role
        │
        └─→ Redirect to dashboard
```

### Analytics Dashboard Request

```
User → app-dashboard.php
    │
    ▼
Check session & role
    │ (must be super_admin or app_admin)
    │
    ▼
Query sales data:
    ├─→ Today's sales (SUM, COUNT)
    ├─→ Monthly sales (SUM, COUNT)
    ├─→ Yearly sales (SUM, COUNT)
    ├─→ Top products (GROUP BY product)
    ├─→ Recent transactions (ORDER BY date)
    └─→ Low stock alerts (stock <= min_stock)
    │
    ▼
Generate Chart.js data arrays
    │
    ▼
Render dashboard with:
    ├─→ Revenue stat cards
    ├─→ Sales trend line chart (6 months)
    ├─→ Product sales pie chart
    ├─→ Top products table
    ├─→ Low stock alerts table
    └─→ Recent transactions table
```

---

## 🚀 Startup Sequence

```
User runs: start-all.bat
    │
    ▼
[Step 1] Check XAMPP Installation
    │
    ├─→ Found → Continue
    └─→ Not Found → Error & Exit
    │
    ▼
[Step 2] Start MySQL Server
    │
    ├─→ Run: C:\xampp\mysql\bin\mysqld.exe --console
    ├─→ Wait 3 seconds
    └─→ Verify process running
    │
    ▼
[Step 3] Start Apache Server
    │
    ├─→ Run: C:\xampp\apache\bin\httpd.exe
    ├─→ Wait 3 seconds
    └─→ Verify process running
    │
    ▼
[Step 4] Start PHP Dev Server
    │
    ├─→ CD to project folder
    ├─→ Run: C:\xampp\php\php.exe -S localhost:8000
    └─→ Keep running (blocking process)
    │
    ▼
[Step 5] Open Browsers
    │
    ├─→ Launch: http://localhost:8000
    └─→ Launch: http://localhost/phpmyadmin
    │
    ▼
Ready for Development! 🎉
```

---

## 📦 File Organization

```
Public Assets
├── assets/css/
│   ├── style.css           # Main website styles
│   └── admin.css           # Admin panel styles
├── assets/js/
│   └── script.js           # Frontend interactions
└── assets/img/
    ├── logo.svg            # Company logo
    ├── products/           # Product images
    └── uploads/            # User uploaded images

Admin Backend
├── admin/
│   ├── index.php           # Content Dashboard
│   ├── login.php           # Auth Entry Point
│   ├── logout.php          # Session Destroy
│   ├── includes/           # PHP Classes
│   │   ├── Admin.php       # Admin CRUD
│   │   ├── Product.php     # Product Management
│   │   ├── Hero.php        # Hero Slider
│   │   └── ...
│   └── pages/              # Admin Pages
│       ├── app-dashboard.php   # Analytics
│       ├── app-management.php  # User Mgmt
│       ├── products.php        # Products
│       ├── hero.php            # Hero Slides
│       └── ...

Configuration
├── config/
│   └── database.php        # DB Connection

Database
└── setup.sql               # Schema + Default Data
```

---

## 🔧 Technology Stack

```
Frontend
├── HTML5                   # Structure
├── CSS3                    # Styling
├── JavaScript              # Interactions
├── Chart.js                # Analytics Visualization
└── Font Awesome            # Icons

Backend
├── PHP 7.4+                # Server-side Logic
├── MySQL 8.0+              # Database
└── Session-based Auth      # Security

Development Tools
├── XAMPP                   # Development Stack
├── phpMyAdmin              # Database Management
└── Batch/PowerShell        # Automation Scripts

Server
└── PHP Built-in Server     # Development Server
```

---

## 🛡️ Security Features

```
Authentication
├── MD5 Password Hashing
├── Session-based Login
├── Role-based Access Control
├── Auto-redirect based on role
└── Protected routes check

Input Validation
├── htmlspecialchars() for output
├── Prepared statements (recommended upgrade)
└── File upload validation

Session Management
├── session_start() on protected pages
├── Session regeneration on login
└── Proper session destruction on logout
```

---

**Last Updated**: 2025-11-19
**Version**: 1.0
**Architecture**: Monolithic MVC Pattern
