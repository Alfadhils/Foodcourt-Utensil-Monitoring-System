<?php
include 'connection.php';

if(isset($_GET['action']) && $_GET['action'] == 'Add'){
    $tag = $_GET['tag'];

    $check_query = "SELECT * FROM availability WHERE tag = '$tag'";
    $result = mysqli_query($koneksi, $check_query);

    if(mysqli_num_rows($result) > 0) {
        echo "<script>alert('Tag already exists in the database');</script>";
        header("Location: update.php");
        exit();
    }

    $insert_query = "INSERT INTO availability SET tag='$tag'";
    mysqli_query($koneksi, $insert_query);

    $delete_query = "DELETE FROM tambah WHERE tag = '$tag'";
    mysqli_query($koneksi, $delete_query);

    header("Location: register.php");
    exit();
}
?>
