<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register | KutuBuku</title>
    <link rel="stylesheet" href="../Register/registerU.css" />
</head>

<body>
    <div class="row-container">
        <div class="left">
            <img src="../../images/logo2.png" alt="Logo" class="logo" />
            <p class="welcome-text">HELLO,<br>NEW USER!</p>
        </div>
        <div class="right">
            <div class="form-container">
                <h3 class="form-title">REGISTER</h3>
                <div class="Text">
                    <form method="POST" action="uploadFoto.php">
                        <label>NAMA</label>
<input type="text" name="nama" placeholder="Masukkan nama" required />

                        <label>EMAIL</label>
<input type="email" name="email" placeholder="Masukkan email" required />

                        <label>PASSWORD</label>
<input type="password" name="password" placeholder="Masukkan password" required />

                        
    <button class="btn" type="submit">LANJUT</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</body>

</html>