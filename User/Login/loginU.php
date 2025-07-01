<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KutuBuku Login</title>
  <link rel="stylesheet" href="loginU.css" />
</head>
<body>
  <div class="login-page">
    <div class="logo-container">
      <img src="../../images/logo2.png" alt="Logo" class="logo" />
    </div>

    <div class="login-box">
      <h2>LOGIN</h2>
<form action="prosesLogin.php" method="POST">
  <label for="email">EMAIL</label>
  <input type="email" id="email" name="email" placeholder="Enter your email" required />

  <label for="password">PASSWORD</label>
  <input type="password" id="password" name="password" placeholder="Enter your password" required />

  <button type="submit">LOGIN</button>
</form>
      <p style="margin-top: 20px;">Belum punya akun? 
        <a href="../Register/registerU.php">Daftar terlebih dahulu</a>
      </p>

    </div>
  </div>
</body>
</html>

<!-- required -->