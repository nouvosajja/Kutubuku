<?php
session_start();
require_once '../db.php';

$kegiatan = $_POST['kegiatan'];
$kategori = $_POST['kategori'];
$due_date = $_POST['due_date'];
$user_id = $_SESSION['user_id']; // Ambil dari session saat login

$query = "INSERT INTO todolist (kegiatan, kategori, due_date, user_id) 
          VALUES ('$kegiatan', '$kategori', '$due_date', '$user_id')";

mysqli_query($conn, $query);

echo "<script>alert('Data berhasil ditambahkan'); window.location.href='../../User/dashboard.php';</script>";

?>