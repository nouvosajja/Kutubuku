<?php
require_once '../db.php';

// Ambil semua user
$userQuery = "SELECT id, nama FROM users";
$userResult = mysqli_query($conn, $userQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin User Page</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header class="top-bar">
        <div class="top-left">ADMIN</div>
        <div class="top-right"><img src="../images/logo3.png" alt="KutuBuku" class="logo" /></div>
    </header>

    <div class="admin-container">
        <nav class="sidebar">
            <a href="dashboard.php" class="nav-item">DASHBOARD</a>
            <a href="user.php" class="nav-item active">USER</a>
            <div class="logout">
                <form action="logout_admin.php" method="POST">
                    <button type="submit">🔲 Logout</button>
                </form>
            </div>

        </nav>

        <main class="main-content">
            <h2 class="page-title">USER</h2>

            <?php while ($user = mysqli_fetch_assoc($userResult)) : ?>
                <div class="card yellow">
                    <h4><?= htmlspecialchars($user['nama']) ?></h4>
                    <div class="task-grid">
                        <?php
                        $kategoriList = [
                            "Penting, Mendesak",
                            "Tidak Penting, Mendesak",
                            "Penting, Tidak Mendesak",
                            "Tidak Penting, Tidak Mendesak"
                        ];

                        foreach ($kategoriList as $kategori) {
                            $userId = $user['id'];
                            $taskQuery = "
                        SELECT kegiatan, due_date 
                        FROM todolist 
                        WHERE user_id = $userId AND kategori = '$kategori'
                        ORDER BY due_date
                    ";
                            $taskResult = mysqli_query($conn, $taskQuery);
                        ?>
                            <div class="task-box">
                                <div class="task-title"><?= $kategori ?></div>
                                <?php if (mysqli_num_rows($taskResult) > 0): ?>
                                    <?php while ($task = mysqli_fetch_assoc($taskResult)) :
                                        $tanggalWaktu = date('j F Y H.i', strtotime($task['due_date']));
                                    ?>
                                        <div class="task-detail">- <?= htmlspecialchars($task['kegiatan']) ?> <?= $tanggalWaktu ?></div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="task-detail">- Tidak ada tugas</div>
                                <?php endif; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </main>
    </div>
</body>

</html>