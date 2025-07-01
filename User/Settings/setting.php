<?php session_start(); ?>

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
      <div class="col-md-10 setting-full d-flex flex-column align-items-center justify-content-start py-5">
        <a href="../home.php" class="btn-close position-absolute top-0 end-0 m-4" aria-label="Close"></a>

        <div class="profile-pic large mb-3">
  <img src="../../images/<?php echo $_SESSION['images']; ?>" 
       alt="Foto Profil" 
       class="rounded-circle" 
       style="width: 160px; height: 160px; object-fit: cover;" />
</div>
<h4 class="fw-bold mb-4"><?php echo $_SESSION['nama']; ?></h4>


        <div class="setting-option mb-3 d-flex align-items-center px-4 py-3 rounded-4">
          <a href="profile.php" class="text-dark text-decoration-none d-flex align-items-center">
            <i class="fas fa-user me-3"></i>
            <span class="fw-bold translate" data-key="profile">Profil</span>
          </a>
        </div>

        <div class="setting-option mb-3 d-flex align-items-center px-4 py-3 rounded-4">
          <a href="lang.php" class="text-dark text-decoration-none d-flex align-items-center">
            <i class="fas fa-language me-3"></i>
            <span class="fw-bold translate" data-key="language">Bahasa</span>
          </a>
        </div>

        <div class="mt-auto w-100 text-end pe-5 pb-3">
  <a href="logout.php" class="text-dark text-decoration-none fw-bold">
    <i class="fas fa-sign-out-alt me-2"></i>Logout
  </a>
</div>

      </div>
    </div>
  </div>

  <script src="lang.js"></script>

</body>

</html>