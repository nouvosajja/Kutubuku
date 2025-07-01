<?php
require_once '../db.php';

$query = "SELECT * FROM todolist";
$result = mysqli_query($conn, $query);

$rows = mysqli_num_rows($result);
$data = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fungsi untuk menampilkan label kategori manusiawi
function labelKategori($kode)
{
    switch ($kode) {
        case 'penting_mendesak':
            return 'Penting, Mendesak';
        case 'penting_tidakMendesak':
            return 'Penting, Tidak Mendesak';
        case 'tidakPenting_mendesak':
            return 'Tidak Penting, Mendesak';
        case 'tidakPenting_tidakMendesak':
            return 'Tidak Penting, Tidak Mendesak';
        default:
            return 'Tidak Diketahui';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <title>User To Do List</title>
</head>

<body>
    <div class="container">
        <br><br>
        <h2>Daftar Tugas</h2>

        <?php
        if ($rows > 0) {
            foreach ($data as $item) {
        ?>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['kegiatan']) ?></h5>
                        <p class="card-text">Kategori: <?= labelKategori($item['kategori']) ?></p>
                        <p class="card-text">
                            <small class="text-muted">
                                Deadline: <?= date('d M Y, H:i', strtotime($item['due_date'])) ?>
                            </small>
                        </p>
                        <a href="edit_data.php?id=<?= $item['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete_data.php?id=<?= $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</a>
                    </div>
                </div>

        <?php
            }
        } else {
            echo "<p class='text-muted'>Belum ada data tugas.</p>";
        }
        ?>
    </div>
</body>

</html>
