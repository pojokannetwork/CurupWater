<?php
session_start();
require_once __DIR__ . '/includes/Admin.php';

Admin::logout();
header("Location: login.php");
exit();
?>
