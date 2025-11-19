<?php
session_start();

// Check if already logged in
if (isset($_SESSION['admin_id'])) {
    // Redirect based on role
    if (isset($_SESSION['admin_role'])) {
        if ($_SESSION['admin_role'] === 'super_admin') {
            header('Location: pages/app-dashboard.php');
        } elseif ($_SESSION['admin_role'] === 'app_admin') {
            header('Location: pages/app-dashboard.php');
        } else {
            header('Location: index.php');
        }
    } else {
        header('Location: index.php');
    }
    exit;
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_role'] = $admin['role'];
            $_SESSION['admin_logged_in'] = true;
            
            // Redirect based on role
            if ($admin['role'] === 'super_admin' || $admin['role'] === 'app_admin') {
                header('Location: pages/app-dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Curup Water</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="../assets/img/logo.svg" alt="Curup Water" class="login-logo">
                <h1>CURUP WATER</h1>
                <p>Admin Panel Login</p>
                <small style="color: #666; display: block; margin-top: 0.5rem;">
                    <i class="fas fa-info-circle"></i> Login otomatis ke dashboard sesuai role Anda
                </small>
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" class="login-form">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            <div class="login-footer">
                <a href="../index.php"><i class="fas fa-home"></i> Kembali ke Website</a>
            </div>
        </div>
    </div>
</body>
</html>
