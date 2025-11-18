<?php
/**
 * Installation Checker for CurupWater
 * Jalankan file ini untuk memeriksa apakah sistem siap digunakan
 */

echo "<html><head><title>CurupWater Installation Checker</title>";
echo "<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
    .success { color: green; padding: 10px; border-left: 4px solid green; background: #f0fff0; margin: 10px 0; }
    .error { color: red; padding: 10px; border-left: 4px solid red; background: #fff0f0; margin: 10px 0; }
    .warning { color: orange; padding: 10px; border-left: 4px solid orange; background: #fffef0; margin: 10px 0; }
    .info { color: blue; padding: 10px; border-left: 4px solid blue; background: #f0f8ff; margin: 10px 0; }
    h1 { color: #667eea; }
    h2 { color: #764ba2; margin-top: 30px; }
</style></head><body>";

echo "<h1>🔍 CurupWater Installation Checker</h1>";
echo "<p>Memeriksa konfigurasi sistem...</p>";

$errors = 0;
$warnings = 0;

// Check PHP Version
echo "<h2>1. PHP Version</h2>";
$phpVersion = phpversion();
if (version_compare($phpVersion, '7.4.0', '>=')) {
    echo "<div class='success'>✓ PHP Version: $phpVersion (OK)</div>";
} else {
    echo "<div class='error'>✗ PHP Version: $phpVersion (Minimum required: 7.4.0)</div>";
    $errors++;
}

// Check PDO Extension
echo "<h2>2. PHP Extensions</h2>";
if (extension_loaded('pdo')) {
    echo "<div class='success'>✓ PDO Extension: Installed</div>";
} else {
    echo "<div class='error'>✗ PDO Extension: Not installed</div>";
    $errors++;
}

if (extension_loaded('pdo_mysql')) {
    echo "<div class='success'>✓ PDO MySQL Extension: Installed</div>";
} else {
    echo "<div class='error'>✗ PDO MySQL Extension: Not installed</div>";
    $errors++;
}

// Check File Upload
echo "<h2>3. File Upload Configuration</h2>";
$uploadMaxFilesize = ini_get('upload_max_filesize');
$postMaxSize = ini_get('post_max_size');
echo "<div class='info'>ℹ upload_max_filesize: $uploadMaxFilesize</div>";
echo "<div class='info'>ℹ post_max_size: $postMaxSize</div>";

// Check Folders
echo "<h2>4. Folder Structure</h2>";
$folders = [
    'config',
    'admin',
    'admin/includes',
    'admin/pages',
    'img/products',
    'img/uploads'
];

foreach ($folders as $folder) {
    if (is_dir($folder)) {
        echo "<div class='success'>✓ Folder exists: $folder</div>";
        
        // Check write permission for upload folders
        if (in_array($folder, ['img/products', 'img/uploads'])) {
            if (is_writable($folder)) {
                echo "<div class='success'>✓ Folder writable: $folder</div>";
            } else {
                echo "<div class='warning'>⚠ Folder not writable: $folder (Please set permission to 755)</div>";
                $warnings++;
            }
        }
    } else {
        echo "<div class='error'>✗ Folder missing: $folder</div>";
        $errors++;
    }
}

// Check Required Files
echo "<h2>5. Required Files</h2>";
$files = [
    'config/db.php',
    'admin/login.php',
    'admin/index.php',
    'index.php',
    'setup.sql'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "<div class='success'>✓ File exists: $file</div>";
    } else {
        echo "<div class='error'>✗ File missing: $file</div>";
        $errors++;
    }
}

// Check Database Connection
echo "<h2>6. Database Connection</h2>";
if (file_exists('config/db.php')) {
    require_once 'config/db.php';
    try {
        $database = new Database();
        $conn = $database->getConnection();
        if ($conn) {
            echo "<div class='success'>✓ Database connection: Successful</div>";
            
            // Check if tables exist
            $tables = ['admin', 'products', 'features', 'about', 'contact', 'hero'];
            foreach ($tables as $table) {
                $stmt = $conn->prepare("SHOW TABLES LIKE '$table'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    echo "<div class='success'>✓ Table exists: $table</div>";
                } else {
                    echo "<div class='warning'>⚠ Table missing: $table (Run setup.sql to create tables)</div>";
                    $warnings++;
                }
            }
        }
    } catch (Exception $e) {
        echo "<div class='error'>✗ Database connection failed: " . $e->getMessage() . "</div>";
        echo "<div class='warning'>⚠ Please check config/db.php and make sure MySQL is running</div>";
        $errors++;
    }
} else {
    echo "<div class='error'>✗ config/db.php not found</div>";
    $errors++;
}

// Summary
echo "<h2>📊 Summary</h2>";
if ($errors == 0 && $warnings == 0) {
    echo "<div class='success'><strong>🎉 All checks passed! Your installation is ready to use.</strong></div>";
    echo "<div class='info'>
        <p><strong>Next Steps:</strong></p>
        <ul>
            <li>Access Landing Page: <a href='index.php'>index.php</a></li>
            <li>Access Admin Panel: <a href='admin/login.php'>admin/login.php</a></li>
            <li>Default Login: admin / admin123</li>
        </ul>
    </div>";
} elseif ($errors == 0 && $warnings > 0) {
    echo "<div class='warning'><strong>⚠ Installation complete with $warnings warning(s).</strong></div>";
    echo "<div class='info'>You can still use the system, but please fix the warnings for optimal performance.</div>";
} else {
    echo "<div class='error'><strong>✗ Found $errors error(s) and $warnings warning(s).</strong></div>";
    echo "<div class='info'>Please fix the errors above before using the system.</div>";
}

echo "<hr><p style='text-align: center; color: #999;'>CurupWater Installation Checker v1.0</p>";
echo "</body></html>";
?>
