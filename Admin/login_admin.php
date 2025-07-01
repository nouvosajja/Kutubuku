<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Data login admin hardcoded
    $adminUsername = "admin";
    $adminPassword = "admin123";

    // Validasi
    if ($username === $adminUsername && $password === $adminPassword) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Username atau password salah!'); window.location.href='loginA.php';</script>";
        exit;
    }
} else {
    header("Location: loginA.php");
    exit;
}
