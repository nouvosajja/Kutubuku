<?php
require_once '../db.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM todolist";
$result = mysqli_query($conn, $query);

$rows = mysqli_num_rows($result);
$data = mysqli_fetch_all($result, MYSQLI_ASSOC);

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

    <title>DineRizz</title>
</head>

<body>
    <div class="container">
        <br><br>
        <h2>Data Persedian</h2>
        <a href="add_data.php">
            <button type="button" class="btn btn-primary">Add Task</button>
        </a>
        <!-- Table ToDoList -->
        <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Kegiatan</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Date</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($index = 0; $index < $rows; $index++) {
                    $no = $index + 1;
                ?> <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo $data[$index]['kegiatan'] ?></td>
                        <td><?php echo labelKategori($data[$index]['kategori']) ?></td>
                        <td><?php echo $data[$index]['due_date'] ?></td>
                        <td>
                            <a href="update_data.php<?php echo '?id=' . $data[$index]['id']; ?>">
                                <button type='button' class='btn btn-warning'>Edit</button>
                            </a>
                            <a href="Database/delete.php<?php echo '?id=' . $data[$index]['id']; ?>">
                                <button type='button' class='btn btn-danger'>Hapus</button>
                            </a>
                        </td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
    </div>
</body>

</html>