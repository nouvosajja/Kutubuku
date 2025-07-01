<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: loginA.php");
    exit;
}

require_once '../db.php';

// Ambil nama user
$userQuery = "SELECT nama FROM users";
$userResult = mysqli_query($conn, $userQuery);

$users = [];
while ($row = mysqli_fetch_assoc($userResult)) {
    $users[] = $row['nama'];
}

// Ambil total task per user
$taskQuery = "
    SELECT users.nama, COUNT(todolist.id) AS total_tasks
    FROM users
    LEFT JOIN todolist ON users.id = todolist.user_id
    GROUP BY users.id
";
$taskResult = mysqli_query($conn, $taskQuery);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="wrapper">
        <header class="top-bar">
            <div class="top-left">ADMIN</div>
            <div class="top-right"><img src="../images/logo3.png" alt="KutuBuku" class="logo" /></div>
        </header>

        <div class="admin-container">
            <aside class="sidebar">
                <a href="dashboard.php" class="nav-item active">DASHBOARD</a>
                <a href="user.php" class="nav-item">USER</a>
                <div class="logout">
                    <form action="logout_admin.php" method="POST">
                        <button type="submit">🔲 Logout</button>
                    </form>
                </div>

            </aside>

            <main class="main-content">
                <h2 class="page-title">DASHBOARD ADMIN</h2>
                <div class="card-container">
                    <div class="card dashboard">
                        <div class="card-header">
                            <h4>TOTAL USER</h4>
                            <span class="card-count"><?= count($users) ?></span>
                        </div>
                        <ul>
                            <?php foreach ($users as $user): ?>
                                <li><?= htmlspecialchars($user) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="card dashboard">
                        <h4>TOTAL TASK</h4>
                        <ul>
                            <?php while ($row = mysqli_fetch_assoc($taskResult)) : ?>
                                <li><?= $row['nama'] ?> - <?= $row['total_tasks'] ?> TASK</li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>