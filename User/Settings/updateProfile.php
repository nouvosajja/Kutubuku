<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginU.php");
    exit;
}

require_once '../../db.php';

$user_id = $_SESSION['user_id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = isset($_POST['password']) ? $_POST['password'] : '';
$fotoName = null;

// Ambil data user sebelumnya
$queryOld = "SELECT images FROM users WHERE id = $user_id";
$resultOld = mysqli_query($conn, $queryOld);
$oldData = mysqli_fetch_assoc($resultOld);

// Handle upload gambar
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
$fileTmp = $_FILES['foto']['tmp_name'];
$ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
$fileName = uniqid('img_') . '.' . $ext;
$targetDir = "../../images/";
$targetFile = $targetDir . $fileName;

    // Simpan file
    if (move_uploaded_file($fileTmp, $targetFile)) {
        $fotoName = $fileName;
    } else {
        echo "Gagal upload gambar.";
        exit;
    }
}

// Jika password tidak kosong, update password juga
if (!empty($password)) {
    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users 
              SET nama = '$nama', email = '$email', password = '$passwordHashed'";

    if ($fotoName) {
        $query .= ", images = '$fotoName'";
    }

    $query .= " WHERE id = $user_id";
} else {
    $query = "UPDATE users 
              SET nama = '$nama', email = '$email'";

    if ($fotoName) {
        $query .= ", images = '$fotoName'";
    }

    $query .= " WHERE id = $user_id";
}

if (mysqli_query($conn, $query)) {
    // Update session jika nama atau foto berubah
    $_SESSION['nama'] = $nama;
    $_SESSION['email'] = $email;
    if ($fotoName) {
        $_SESSION['images'] = $fotoName;
    }

    // Redirect kembali ke profile
    header("Location: setting.php");
    exit;
} else {
    echo "Gagal update profil: " . mysqli_error($conn);
}
?>
