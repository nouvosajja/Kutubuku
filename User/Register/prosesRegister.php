<?php
session_start();
require_once '../../db.php';

$nama = $_SESSION['nama'];
$email = $_SESSION['email'];
$password = $_SESSION['password'];

// Upload foto
$fotoName = $_FILES['foto']['name'];
$tmpName = $_FILES['foto']['tmp_name'];
$targetDir = '../../images/';
$targetPath = $targetDir . $fotoName;

if (move_uploaded_file($tmpName, $targetPath)) {
    // Simpan ke database
    $query = "INSERT INTO users (nama, email, password, images) VALUES ('$nama', '$email', '$password', '$fotoName')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Register berhasil'); window.location.href='../Login/loginU.php';</script>";
    } else {
        echo "Gagal menyimpan ke database: " . mysqli_error($conn);
    }
} else {
    echo "Gagal upload foto.";
}
?>
