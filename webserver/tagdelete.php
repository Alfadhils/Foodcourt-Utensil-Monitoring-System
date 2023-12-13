<?php
include 'connection.php';
$No  = $_GET['No'];
$query="DELETE from availability where No='$No'";
mysqli_query($koneksi, $query);
header("location:tagdata.php");
?>