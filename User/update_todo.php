<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int) $_POST['id'];
  $kegiatan = mysqli_real_escape_string($conn, $_POST['kegiatan']);
  $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
  $due_date = $_POST['due_date'];

  $query = "UPDATE todolist 
            SET kegiatan='$kegiatan', kategori='$kategori', due_date='$due_date' 
            WHERE id=$id AND user_id=" . $_SESSION['user_id'];

  if (mysqli_query($conn, $query)) {
    header("Location: mylist.php?status=updated");
    exit;
  } else {
    echo "Gagal update data: " . mysqli_error($conn);
  }
}
?>
