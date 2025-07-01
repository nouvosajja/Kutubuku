<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginU.php");
    exit;
}
require_once '../db.php';

$user_id = $_SESSION['user_id'];

function getTasksByCategory($conn, $user_id, $kategori)
{
    $kategori = mysqli_real_escape_string($conn, $kategori);
    $query = "SELECT * FROM todolist 
              WHERE user_id = $user_id 
              AND kategori = '$kategori' 
              AND due_date >= NOW()";
    $result = mysqli_query($conn, $query);
    $tasks = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
        }
    }
    return $tasks;
}

$tasks_prioritas = [
    [
        "label" => "Penting, Mendesak",
        "warna" => "#a52a2a",
        "data" => getTasksByCategory($conn, $user_id, "Penting, Mendesak")
    ],
    [
        "label" => "Penting, Tidak Mendesak",
        "warna" => "#FF9100",
        "data" => getTasksByCategory($conn, $user_id, "Penting, Tidak Mendesak")
    ],
    [
        "label" => "Tidak Penting, Mendesak",
        "warna" => "#e3e369",
        "data" => getTasksByCategory($conn, $user_id, "Tidak Penting, Mendesak")
    ],
    [
        "label" => "Tidak Penting, Tidak Mendesak",
        "warna" => "#6CA64C",
        "data" => getTasksByCategory($conn, $user_id, "Tidak Penting, Tidak Mendesak")
    ],
];

// Ambil semua task untuk calendar (kelompokkan per tanggal)
$allTasks = [];
$queryAll = "SELECT kegiatan, kategori, due_date FROM todolist WHERE user_id = $user_id";
$resultAll = mysqli_query($conn, $queryAll);
if ($resultAll) {
    while ($row = mysqli_fetch_assoc($resultAll)) {
        $date = date('Y-m-d', strtotime($row['due_date']));
        $allTasks[$date][] = $row; // Masukkan ke dalam array per tanggal
    }
}
$allTasksJSON = json_encode($allTasks);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>To-Do Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-flex flex-column align-items-center text-center">
                <div class="logo-container">
                    <img src="../images/logo.png" alt="Logo" class="logo" />
                </div>
                <div class="profile-pic">
                    <img src="../images/<?php echo $_SESSION['images']; ?>"
                        alt="Profile Picture"
                        class="rounded-circle"
                        width="120"
                        height="120" />
                </div>
                <h6 class="fw-bold"><?php echo $_SESSION['nama']; ?></h6>
                <p class="email"><?php echo $_SESSION['email']; ?></p>
                <a href="home.php" class="btn btn-success nav-btn ">Home</a>
                <a href="mylist.php" class="btn btn-success nav-btn">My List</a>
                <a href="category.php" class="btn btn-success nav-btn">Category</a>
                <a href="priority.php" class="btn btn-warning nav-btn active">Priority</a>
                <div class="mt-auto">
                    <a href="Settings/setting.php" class="d-flex align-items-center gap-2 text-dark text-decoration-none">
                        <p class="fw-bold mb-0">Setting</p>
                        <i class="fas fa-cog fa-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content position-relative">
                <div class="rounded-box-priority-full">
                    <h5 class="fw-bold">Priority</h5>

                    <?php foreach ($tasks_prioritas as $prioritas): ?>
                        <?php foreach ($prioritas['data'] as $task): ?>
                            <div class="priority-bar" style="background-color: <?= $prioritas['warna'] ?>; border-radius: 12px; color: white; margin-bottom: 10px;">
                                <div class="row align-items-center px-4 pt-3">
                                    <div class="col text-start fw-bold"><?= htmlspecialchars($task['kegiatan']) ?></div>
                                    <div class="col text-center text-capitalize"><?= htmlspecialchars($task['kategori']) ?></div>
                                    <div class="col text-end"><?= date('d M Y, H:i', strtotime($task['due_date'])) ?></div>
                                </div>
                                <hr class="mx-4 mt-2 mb-0" style="border-color: white;">
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>

                <div class="calendar-toggle-container">
                    <div class="triangle" id="toggleCalendar"></div>
                    <div class="wrapper" id="calendarWrapper" style="display: none;">
                        <header>
                            <p class="current-date"></p>
                            <div class="icons">
                                <span id="prev" class="material-symbols-rounded">
                                    <</span>
                                        <span id="next" class="material-symbols-rounded">></span>
                            </div>
                        </header>
                        <div class="calendar">
                            <ul class="weeks">
                                <li>Sun</li>
                                <li>Mon</li>
                                <li>Tue</li>
                                <li>Wed</li>
                                <li>Thu</li>
                                <li>Fri</li>
                                <li>Sat</li>
                            </ul>
                            <ul class="days"></ul>
                        </div>
                    </div>
                </div>
                <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addTaskModal">+</button>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="addTaskModalLabel">To do List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label class="fw-bold mb-1">Nama Kegiatan</label>
                    <input type="text" class="form-control mb-3">
                    <label class="fw-bold mb-1">Kategori</label>
                    <select class="form-select mb-3">
                        <option selected disabled>Pilih kategori</option>

                        <option>Penting, Mendesak</option>
                        <option>Penting, Tidak Mendesak</option>
                        <option>Tidak Penting, Mendesak</option>
                        <option>Tidak Penting, Tidak Mendesak</option>
                    </select>
                    <label class="fw-bold mb-1">Waktu</label>
                    <input type="datetime-local" class="form-control">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal">Masukkan</button>
                </div>
            </div>
        </div>
    </div>

<script>
  const tasksByDate = <?php echo json_encode($allTasks); ?>;
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="calender.js"></script>
</body>

</html>