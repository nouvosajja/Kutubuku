<?php
session_start();
require_once '../db.php'; // Pastikan path ke db.php benar

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginU.php");
    exit;
}

// Ambil data dari form
$user_id = $_SESSION['user_id'];
$kegiatan = $_POST['kegiatan'];
$kategori = $_POST['kategori'];
$due_date = $_POST['due_date'];

// Insert ke database
$query = "INSERT INTO todolist (user_id, kegiatan, kategori, due_date) 
          VALUES ('$user_id', '$kegiatan', '$kategori', '$due_date')";

if (mysqli_query($conn, $query)) {
    header("Location: home.php"); // Redirect ke home setelah berhasil
    exit;
} else {
    echo "Gagal menambahkan todo: " . mysqli_error($conn);
}
?>
