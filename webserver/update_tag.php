<?php
require "connection.php";

$tag        = $_GET["tag"];

$query = "CALL update_availability('$tag')";
mysqli_query($koneksi, $query);
?>