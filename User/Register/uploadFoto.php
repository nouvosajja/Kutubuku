<?php
session_start();

// Simpan data sebelumnya
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['nama'] = $_POST['nama'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT); // supaya aman
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Upload Foto | KutuBuku</title>
  <link rel="stylesheet" href="../Register/registerU.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <div class="container">
    <div class="card">
      <img src="../../images/logo2.png" alt="Logo" class="logo2"/>
      <h3 class="form-title">PILIH FOTO</h3>
      <!-- Tambahkan form di sini -->
      <form action="prosesRegister.php" method="POST" enctype="multipart/form-data">
        <div class="circle-img">
  <img id="preview" src="#" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" />
  <label for="file-upload">
    <i class="fa fa-camera"></i>
  </label>
  <input type="file" name="foto" id="file-upload" accept="image/*" required hidden />
</div>

        <button class="btn" type="submit">DAFTAR</button>
      </form>
    </div>
  </div>

<script src="reg.js"></script>
</body>
</html>