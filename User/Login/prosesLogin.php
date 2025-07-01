<?php
session_start();
require_once '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek user dari database
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Simpan data ke session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['images'] = $user['images'];

        header("Location: ../home.php"); // arahkan ke halaman dashboard
        exit;
    } else {
        echo "<script>alert('Email atau password salah'); window.location.href='loginU.php';</script>";
    }
}
?>
