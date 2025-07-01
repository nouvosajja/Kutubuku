<?php
    require_once '../db.php';

    $id = $_GET['id'];

    $query = "DeLETE FROM todolist WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "<script>alert('Data berhasil dihapus'); window.location.href='Admin/read.php';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus'); window.location.href='Admin/read.php';</script>";
    }
?>