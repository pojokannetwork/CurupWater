<?php
// Script untuk reset password admin
require_once 'config/database.php';

// Password baru yang akan digunakan
$new_password = 'admin123';
$hashed = password_hash($new_password, PASSWORD_DEFAULT);

echo "=== RESET PASSWORD ADMIN ===\n\n";

// Cek apakah tabel admin ada
$check = $conn->query("SHOW TABLES LIKE 'admin'");
if ($check->num_rows == 0) {
    echo "ERROR: Tabel 'admin' tidak ditemukan!\n";
    echo "Jalankan database.sql terlebih dahulu.\n";
    exit;
}

// Cek data admin yang ada
$result = $conn->query("SELECT id, username, email FROM admin");
echo "Data Admin Saat Ini:\n";
echo "====================\n";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . "\n";
        echo "Username: " . $row['username'] . "\n";
        echo "Email: " . $row['email'] . "\n\n";
    }
} else {
    echo "Tidak ada data admin!\n";
    echo "Membuat admin baru...\n\n";
    
    $sql = "INSERT INTO admin (username, password, email) VALUES ('admin', ?, 'admin@curupwater.com')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $hashed);
    
    if ($stmt->execute()) {
        echo "SUCCESS: Admin baru berhasil dibuat!\n";
    } else {
        echo "ERROR: Gagal membuat admin!\n";
    }
}

// Update password admin
echo "\nUpdate Password...\n";
$sql = "UPDATE admin SET password = ? WHERE username = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hashed);

if ($stmt->execute()) {
    echo "SUCCESS: Password berhasil di-reset!\n\n";
    echo "Gunakan kredensial berikut:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n\n";
    echo "Hash password: " . $hashed . "\n";
} else {
    echo "ERROR: Gagal reset password!\n";
}

$conn->close();
?>
