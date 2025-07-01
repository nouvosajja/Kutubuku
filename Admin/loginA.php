<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login - KutuBuku</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <div class="header">
        <h2>ADMIN</h2>
        <div class="logo">
            <img src="../images/logo3.png" alt="KutuBuku Logo" />
        </div>
    </div>

    <div class="login-box">
        <h3>LOGIN</h3>
        <form action="login_admin.php" method="POST">
            <div class="form-group">
                <label for="email">EMAIL</label>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="password">PASSWORD</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="login-btn">LOGIN</button>
        </form>
    </div>

</body>

</html>