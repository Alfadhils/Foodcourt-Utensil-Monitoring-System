<?php
require "connection.php";

$tag        = $_GET["tag"];

$query = "CALL get_status('$tag')";
$result = mysqli_query($koneksi, $query);

$row = mysqli_fetch_assoc($result);

$response = [];

if ($row) {
    $response['rfid_tag'] = $tag;
    $response['availability_status'] = $row["availability_status"];
} else {
    $response['error'] = "No availability status found for RFID Tag: $tag";
}

mysqli_close($koneksi);

$output = json_encode($response);

echo $output;
?>