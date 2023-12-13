<?php 
 
$host = "localhost";
$user = "root";
$password = "";
$database = "arduino_sensor";
 
$koneksi = mysqli_connect($host,$user,$password,$database);
 
if($koneksi->connect_error){
	die("Failed Connection");
}
 
?>