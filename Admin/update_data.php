<?php
require_once '../db.php';

$id = $_GET['id'];

$query = "SELECT * FROM todolist WHERE id='$id'";
$result = mysqli_query($conn, $query);

$rows = mysqli_num_rows($result);
$data = mysqli_fetch_assoc($result);

if ($rows == 0) {
    echo "<script>alert('Data tidak ditemukan'); window.location.href='Admin/read.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

    <title>Document</title>
</head>

<body>
    <div class="container">
        <br><br>
        <h2>Update data</h2>
        <br><br>
        <form action="Admin/Database/update.php?id=<?php echo $data['id'] ?>" method="post">
            <div class="mb-3">
                <label for="kegiatan" class="form-label">Nama Kegiatan</label>
                <input type="text" class="form-control" id="kegiatan" placeholder="Rapat" name="kegiatan" value="<?php echo $data['kegiatan']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori Kegiatan</label>
                <select class="form-select" aria-label="Default select example" id="kategori" name="kategori" value="<?php echo $data['kategori']; ?>" required>
                    <option selected>Pilih Kategori</option>
                    <option value="penting_mendesak" <?php if ($data['kategori'] == 'penting_mendesak') echo "selected" ?>>Penting, Mendesak</option>
                    <option value="penting_tidakMendesak" <?php if ($data['kategori'] == 'penting_tidakMendesak') echo "selected" ?>>Penting, Tidak Mendesak</option>
                    <option value="tidakPenting_mendesak" <?php if ($data['kategori'] == 'tidakPenting_mendesak') echo "selected" ?>>Tidak Penting, Mendesak</option>
                    <option value="tidakPenting_tidakMendesak" <?php if ($data['kategori'] == 'tidakPenting_tidakMendesak') echo "selected" ?>>Tidak Penting, Tidak Mendesak</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="due_date" class="form-label">Tanggal dan Waktu Deadline</label>
                <input type="datetime-local" class="form-control" id="due_date" name="due_date"
                    value="<?php echo date('Y-m-d\TH:i', strtotime($data['due_date'])); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Tambah</button>
        </form>
    </div>
</body>

</html>