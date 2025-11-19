<?php
require_once 'config/database.php';

echo "<h2>Test Login Admin</h2>";
echo "<hr>";

// Test koneksi database
echo "<h3>1. Test Koneksi Database</h3>";
if ($conn) {
    echo "✅ Koneksi database berhasil!<br>";
} else {
    echo "❌ Koneksi database gagal!<br>";
    exit;
}

// Cek tabel admin
echo "<h3>2. Cek Tabel Admin</h3>";
$check = $conn->query("SHOW TABLES LIKE 'admin'");
if ($check->num_rows > 0) {
    echo "✅ Tabel 'admin' ditemukan!<br>";
} else {
    echo "❌ Tabel 'admin' tidak ditemukan!<br>";
    echo "<p>Jalankan: <code>mysql -u root curupwater < database.sql</code></p>";
    exit;
}

// Cek data admin
echo "<h3>3. Data Admin</h3>";
$result = $conn->query("SELECT id, username, email, password FROM admin");
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Password Hash</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td style='font-size:10px;'>" . substr($row['password'], 0, 30) . "...</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ Tidak ada data admin!<br>";
    echo "<p>Buat admin baru dengan menjalankan reset-admin.php</p>";
}

// Test password
echo "<h3>4. Test Password</h3>";
$test_password = 'admin123';
$result = $conn->query("SELECT password FROM admin WHERE username = 'admin' LIMIT 1");
if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    if (password_verify($test_password, $admin['password'])) {
        echo "✅ Password 'admin123' VALID!<br>";
        echo "<p style='color:green;'><strong>Login dengan:</strong><br>";
        echo "Username: <strong>admin</strong><br>";
        echo "Password: <strong>admin123</strong></p>";
    } else {
        echo "❌ Password 'admin123' TIDAK VALID!<br>";
        echo "<p>Password hash tidak cocok. Jalankan reset-admin.php untuk reset password.</p>";
        
        // Generate hash baru
        $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
        echo "<p>Update manual dengan query ini:<br>";
        echo "<code>UPDATE admin SET password = '$new_hash' WHERE username = 'admin';</code></p>";
    }
}

echo "<hr>";
echo "<h3>Aksi Cepat:</h3>";
echo "<ul>";
echo "<li><a href='reset-admin.php'>Reset Password Admin</a></li>";
echo "<li><a href='admin/login.php'>Ke Halaman Login</a></li>";
echo "<li><a href='index.php'>Ke Homepage</a></li>";
echo "</ul>";
?>
