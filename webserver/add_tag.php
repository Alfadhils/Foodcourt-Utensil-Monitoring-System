<?php
require "connection.php";

$tag        = $_GET["tag"];

$query = "INSERT INTO tambah (tag) VALUES ('$tag')";
mysqli_query($koneksi, $query);
?>