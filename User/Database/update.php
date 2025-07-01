<?php
    require_once '../db.php';

    $id = $_GET['id'];

    $kegiatan = $_POST['kegiatan'];
    $kategori = $_POST['kategori'];
    $due_date = $_POST['due_date'];

    $query = "UPDATE todolist 
        SET kegiatan='$kegiatan', 
            kategori='$kategori', 
            due_date='$due_date' 
        WHERE id='$id'";

    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "<script>alert('Data berhasil diupdate'); window.location.href='Admin/read.php';</script>";
    } else {
        echo "<script>alert('Data gagal diupdate'); window.location.href='Admin/read.php';</script>";
    }
?>