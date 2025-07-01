<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginU.php");
    exit;
}
require_once '../db.php';

// Inisialisasi data
$user_id = (int) $_SESSION['user_id'];
$today = date('Y-m-d');
$now = date('Y-m-d H:i:s');

// Ambil task hari ini (yang belum lewat)
$todos = [];
$query = "SELECT * FROM todolist WHERE user_id = $user_id AND DATE(due_date) = '$today' AND due_date >= NOW() ORDER BY due_date ASC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $todos[] = $row;
    }
}

// Fungsi ambil tugas berdasarkan kategori
function getTasksByCategory($conn, $user_id, $kategori)
{
    $kategori = mysqli_real_escape_string($conn, $kategori);
$query = "SELECT * FROM todolist 
          WHERE user_id = $user_id 
          AND kategori = '$kategori' 
          AND due_date >= NOW()";    $result = mysqli_query($conn, $query);
    $tasks = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
        }
    }
    return $tasks;
}

// Data per kategori Eisenhower Matrix
$tasks_matrix = [
    "Penting, Mendesak" => getTasksByCategory($conn, $user_id, "Penting, Mendesak"),
    "Penting, Tidak Mendesak" => getTasksByCategory($conn, $user_id, "Penting, Tidak Mendesak"),
    "Tidak Penting, Mendesak" => getTasksByCategory($conn, $user_id, "Tidak Penting, Mendesak"),
    "Tidak Penting, Tidak Mendesak" => getTasksByCategory($conn, $user_id, "Tidak Penting, Tidak Mendesak")
];

// Warna per kategori
$priority_colors = [
    "Penting, Mendesak" => "#a52a2a",
    "Penting, Tidak Mendesak" => "#FF9100",
    "Tidak Penting, Mendesak" => "#e3e369",
    "Tidak Penting, Tidak Mendesak" => "#6CA64C"
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

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
                <a href="home.php" class="btn btn-warning nav-btn active">Home</a>
                <a href="mylist.php" class="btn btn-success nav-btn">My List</a>
                <a href="category.php" class="btn btn-success nav-btn">Category</a>
                <a href="priority.php" class="btn btn-success nav-btn">Priority</a>
                <div class="mt-auto">
                    <a href="Settings/setting.php" class="d-flex align-items-center gap-2 text-dark text-decoration-none">
                        <p class="fw-bold mb-0">Setting</p>
                        <i class="fas fa-cog fa-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content">
                <!-- Today List & Calendar Toggle -->
                <div class="row align-items-start mb-3">
                    <div class="col-12 rounded-box">
                        <h5 class="fw-bold">Today List</h5>
                        <?php foreach ($todos as $todo): ?>
                            <div class="todo-item">
                                <div class="row align-items-center px-4 todo-text">
                                    <div class="col text-start fw-bold"><?php echo htmlspecialchars($todo['kegiatan']); ?></div>
                                    <div class="col text-center text-capitalize"><?php echo htmlspecialchars($todo['kategori']); ?></div>
                                    <div class="col text-end"><?php echo date('d M Y, H:i', strtotime($todo['due_date'])); ?></div>
                                </div>
                                <hr class="mx-4 mt-2 mb-0">
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="col-1 d-flex justify-content-end">
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
                    </div>
                </div>
                <!-- Category + Priority -->
                <div class="row d-flex justify-content-between gap-2">
                    <div class="col-md-5 rounded-box-category">
                        <h5 class="fw-bold small-heading">Category</h5>
                        <div class="category-grid">
                            <?php foreach ($tasks_matrix as $kategori => $list): ?>
                                <div class="category-box">
                                    <div class="row align-items-center px-3 pt-2">
                                        <div class="col text-start fw-bold category-label-small"><?= htmlspecialchars($kategori) ?></div>
                                        <div class="col text-end"><?= count($list) ?> tugas</div>
                                    </div>
                                    <hr class="mx-3 mt-2 mb-0">
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>

                    <div class="col-md-6 position-relative rounded-box-priority">
                        <h5 class="fw-bold">Priority</h5>
                        <?php foreach ($tasks_matrix as $kategori => $list): ?>
                            <?php foreach ($list as $task): ?>
                                <div class="priority-bar mb-3" style="background-color: <?= $priority_colors[$kategori] ?>; border-radius: 12px; color: white;">
                                    <div class="row align-items-center px-4 pt-2">
                                        <div class="col text-start fw-bold"><?= htmlspecialchars($task['kegiatan']) ?></div>
                                        <div class="col text-center text-capitalize"><?= htmlspecialchars($kategori) ?></div>
                                        <div class="col text-end"><?= date('d M Y, H:i', strtotime($task['due_date'])) ?></div>
                                    </div>
                                    <hr class="mx-4 mt-1 mb-0" style="border-color: white;">
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>

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
                <form action="add_todo.php" method="POST">
                    <div class="modal-body">
                        <label class="fw-bold mb-1">Nama Kegiatan</label>
                        <input type="text" class="form-control mb-3" name="kegiatan" required>

                        <label class="fw-bold mb-1">Kategori</label>
                        <select class="form-select mb-3" name="kategori" required>
                            <option selected disabled>Pilih kategori</option>
                            <option value="Penting, Mendesak">Penting, Mendesak</option>
                            <option value="Penting, Tidak Mendesak">Penting, Tidak Mendesak</option>
                            <option value="Tidak Penting, Mendesak">Tidak Penting, Mendesak</option>
                            <option value="Tidak Penting, Tidak Mendesak">Tidak Penting, Tidak Mendesak</option>
                        </select>

                        <label class="fw-bold mb-1">Waktu</label>
                        <input type="datetime-local" class="form-control" name="due_date" required>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-success w-100">Masukkan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const tasksByDate = <?php echo json_encode($allTasks); ?>;
</script>
    <script src="calender.js"></script>
    
<script>
  const upcomingTasks = <?php echo json_encode($todos); ?>;

  function checkReminder() {
    const now = new Date().getTime();

    upcomingTasks.forEach(task => {
      const taskTime = new Date(task.due_date).getTime();
      const diffMinutes = Math.floor((taskTime - now) / 60000);

      if (diffMinutes === 5 && !task.reminded) {
        // Tampilkan notifikasi
        alert(`⏰ Reminder!\n"${task.kegiatan}" akan dimulai dalam 5 menit!`);

        // Tandai sudah diingatkan agar tidak berulang
        task.reminded = true;
      }
    });
  }

  // Cek setiap 30 detik
  setInterval(checkReminder, 30000);
</script>

</body>

</html>