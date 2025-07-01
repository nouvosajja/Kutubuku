<?php
date_default_timezone_set("Asia/Jakarta"); // <-- wajib ini biar waktu sesuai WIB
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginU.php");
    exit;
}
require_once '../db.php';

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// Ambil daftar kegiatan hari ini
// Ambil daftar kegiatan hari ini yang belum lewat waktunya
$queryToday = "SELECT * FROM todolist 
               WHERE user_id = $user_id 
               AND DATE(due_date) = '$today'
               AND due_date >= NOW()
               ORDER BY due_date ASC";

$resultToday = mysqli_query($conn, $queryToday);
$todosToday = [];
if ($resultToday) {
    while ($row = mysqli_fetch_assoc($resultToday)) {
        $todosToday[] = $row;
    }
}

// Ambil daftar kegiatan setelah hari ini (besok dan seterusnya)
$queryNext = "SELECT * FROM todolist 
              WHERE user_id = $user_id 
              AND DATE(due_date) > '$today'
              ORDER BY due_date ASC";
$resultNext = mysqli_query($conn, $queryNext);
$todosNext = [];
if ($resultNext) {
    while ($row = mysqli_fetch_assoc($resultNext)) {
        $todosNext[] = $row;
    }
}

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
                <a href="home.php" class="btn btn-success nav-btn">Home</a>
                <a href="mylist.php" class="btn btn-warning nav-btn active">My List</a>
                <a href="category.php" class="btn btn-success nav-btn">Category</a>
                <a href="priority.php" class="btn btn-success nav-btn">Priority</a>
                <div class="mt-auto">
                    <a href="Settings/setting.php" class="d-flex align-items-center gap-2 text-dark text-decoration-none">
                        <p class="fw-bold mb-0 translate" data-key="setting">Pengaturan</p>
                        <i class="fas fa-cog fa-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content">

                <!-- Today List -->
                <div class="row align-items-start mb-3">
                    <div class="col-12 rounded-box">
                        <h5 class="fw-bold translate" data-key="todayList">Daftar Hari Ini</h5>
                        <?php if (empty($todosToday)): ?>
                            <p class="text-center text-muted translate" data-key="noTaskToday">Tidak ada kegiatan hari ini.</p>
                        <?php else: ?>
                            <?php foreach ($todosToday as $todo): ?>
                                <div class="todo-item">
                                    <div class="row align-items-center px-4 todo-text">
                                        <div class="col text-start fw-bold"><?= htmlspecialchars($todo['kegiatan']); ?></div>
                                        <div class="col text-center text-capitalize translate" data-key="<?= htmlspecialchars($todo['kategori']); ?>">
                                            <?= htmlspecialchars($todo['kategori']); ?>
                                        </div>
                                        <div class="col text-end"><?= date('d M Y, H:i', strtotime($todo['due_date'])); ?></div>
                                        <div class="col-auto">
                                            <button class="btn edit-btn"
                                                style="width: 36px; height: 36px;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editTaskModal"
                                                data-id="<?= $todo['id'] ?>"
                                                data-kegiatan="<?= htmlspecialchars($todo['kegiatan']) ?>"
                                                data-kategori="<?= $todo['kategori'] ?>"
                                                data-duedate="<?= date('Y-m-d\TH:i', strtotime($todo['due_date'])) ?>">
                                                <i class="fas fa-pen text-dark"></i>
                                            </button>

                                        </div>
                                    </div>
                                    <hr class="mx-4 mt-2 mb-0">
                                </div>
                            <?php endforeach; ?>

                        <?php endif; ?>
                    </div>

                    <!-- Calendar Toggle -->
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

                <!-- Whats next -->
                <div class="col-12 rounded-box">
                    <h5 class="fw-bold translate" data-key="whatsNext">Kegiatan Selanjutnya</h5>
                    <?php if (empty($todosNext)): ?>
                        <p class="text-center text-muted translate" data-key="noUpcomingTasks">Tidak ada kegiatan mendatang.</p>
                    <?php else: ?>
                        <?php foreach ($todosNext as $todo): ?>
                            <div class="todo-item-next">
                                <div class="row align-items-center px-4 pt-3">
                                    <div class="col text-start fw-bold"><?= htmlspecialchars($todo['kegiatan']); ?></div>
                                    <div class="col text-center text-capitalize translate" data-key="<?= htmlspecialchars($todo['kategori']); ?>">
                                        <?= htmlspecialchars($todo['kategori']); ?>
                                    </div>
                                    <div class="col text-end"><?= date('d M Y, H:i', strtotime($todo['due_date'])); ?></div>
                                    <div class="col-auto">
                                        <button class="btn edit-btn"
                                            style="width: 36px; height: 36px;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editTaskModal"
                                            data-id="<?= $todo['id'] ?>"
                                            data-kegiatan="<?= htmlspecialchars($todo['kegiatan']) ?>"
                                            data-kategori="<?= $todo['kategori'] ?>"
                                            data-duedate="<?= date('Y-m-d\TH:i', strtotime($todo['due_date'])) ?>">
                                            <i class="fas fa-pen text-dark"></i>
                                        </button>

                                    </div>
                                </div>
                                <hr class="mx-4 mt-2 mb-0">
                            </div>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>


            </div>
            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addTaskModal">+</button>

        </div>

    </div>

    <!-- Modal Edit-->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold translate" id="editTaskModalLabel" data-key="editTask">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="update_todo.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">

                        <label class="fw-bold mb-1 translate" data-key="enterActivity">Nama Kegiatan</label>
                        <input type="text" class="form-control mb-3" name="kegiatan" id="edit-kegiatan" required>

                        <label class="fw-bold mb-1 translate" data-key="category">Kategori</label>
                        <select class="form-select mb-3" name="kategori" id="edit-kategori" required>
                            <option value="Penting, Mendesak" class="translate" data-key="Penting, Mendesak">Penting, Mendesak</option>
                            <option value="Penting, Tidak Mendesak" class="translate" data-key="Penting, Tidak Mendesak">Penting, Tidak Mendesak</option>
                            <option value="Tidak Penting, Mendesak" class="translate" data-key="Tidak Penting, Mendesak">Tidak Penting, Mendesak</option>
                            <option value="Tidak Penting, Tidak Mendesak" class="translate" data-key="Tidak Penting, Tidak Mendesak">Tidak Penting, Tidak Mendesak</option>
                        </select>

                        <label class="fw-bold mb-1 translate" data-key="time">Waktu</label>
                        <input type="datetime-local" class="form-control" name="due_date" id="edit-duedate" required>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-success w-100 translate" data-key="saveChanges">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal Task-->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold translate" id="addTaskModalLabel" data-key="add">To do List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label class="fw-bold mb-1 translate" data-key="enterActivity">Nama Kegiatan</label>
                    <input type="text" class="form-control mb-3">
                    <label class="fw-bold mb-1 translate" data-key="category">Kategori</label>
                    <select class="form-select mb-3">
                        <option selected disabled class="translate" data-key="selectCategory">Pilih kategori</option>
                        <option class="translate" data-key="Penting, Mendesak">Penting, Mendesak</option>
                        <option class="translate" data-key="Penting, Tidak Mendesak">Penting, Tidak Mendesak</option>
                        <option class="translate" data-key="Tidak Penting, Mendesak">Tidak Penting, Mendesak</option>
                        <option class="translate" data-key="Tidak Penting, Tidak Mendesak">Tidak Penting, Tidak Mendesak</option>
                    </select>
                    <label class="fw-bold mb-1 translate" data-key="time">Waktu</label>
                    <input type="datetime-local" class="form-control">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-success w-100 translate" data-key="submit">Masukkan</button>
                </div>
            </div>
        </div>
    </div>

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
    <script>
        // Auto refresh tiap 1 menit (60000 ms)
        setInterval(() => {
            window.location.reload();
        }, 60000);
    </script>

    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('edit-id').value = this.dataset.id;
                document.getElementById('edit-kegiatan').value = this.dataset.kegiatan;
                document.getElementById('edit-kategori').value = this.dataset.kategori;
                document.getElementById('edit-duedate').value = this.dataset.duedate;
            });
        });
    </script>
    <script>
        const tasksByDate = <?php echo json_encode($allTasks); ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="calender.js"></script>
    <script src="Settings/lang.js"></script>
</body>

</html>