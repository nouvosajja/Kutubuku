<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../Login/loginU.php");
  exit;
}

require_once '../../db.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT nama, email, images FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="setting.css" />
</head>

<body>

  <div class="container-fluid">
    <div class="row">
      <!-- Main Content -->
      <div class="col-md-12 setting-full d-flex flex-column align-items-center justify-content-center">
        <div class="top-left-title">
          <i class="fas fa-user fa-lg"></i>
          <span class="fw-bold fs-5">Profil</span>
        </div>
        <a href="javascript:history.back()" class="btn-close top-right-close" aria-label="Close"></a>

      <!-- Edit Form -->
<form action="updateProfile.php" method="POST" enctype="multipart/form-data" class="w-100 px-4" style="max-width: 500px;">
  <!-- Profile Image -->
  <div class="text-center mb-4">
    <img id="previewImage"
      src="../../images/<?php echo htmlspecialchars($user['images']); ?>"
      alt="Profile Picture"
      class="rounded-circle mb-2"
      style="width: 160px; height: 160px; object-fit: cover;">

    <div>
      <label for="foto" class="btn btn-warning">Pilih Foto</label>
      <!-- HANYA INI YANG DIPAKAI DAN DI DALAM FORM -->
      <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">
    </div>
  </div>

  <div class="mb-4">
    <label for="nama" class="form-label">Nama</label>
    <input type="text" class="form-control" id="nama" name="nama"
      value="<?php echo htmlspecialchars($user['nama']); ?>" required />
  </div>

  <div class="mb-4">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email"
      value="<?php echo htmlspecialchars($user['email']); ?>" required />
  </div>

  <div class="mb-4">
    <label for="password" class="form-label">Password Baru</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" />
  </div>

  <div class="text-center">
    <button type="submit" class="submit-btn btn btn-danger w-100">Ganti</button>
  </div>
</form>


    </div>
  </div>
  </div>

  <script>
    const fileInput = document.getElementById("foto");
    const previewImage = document.getElementById("previewImage");

    fileInput.addEventListener("change", function(event) {
      const file = event.target.files[0];
      if (file) {
        previewImage.src = URL.createObjectURL(file);
      }
    });
  </script>


</body>

</html>