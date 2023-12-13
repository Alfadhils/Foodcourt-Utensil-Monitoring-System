<?php
if (isset($_POST['tag']) && !empty($_POST['tag'])) {
    include 'connection.php';

    $tagToDelete = mysqli_real_escape_string($koneksi, $_POST['tag']);

    $deleteQuery = "DELETE FROM tambah WHERE tag = '$tagToDelete'";
    $result = mysqli_query($koneksi, $deleteQuery);

    if ($result) {
        echo "Entry deleted successfully.";
        header("location: register.php");
    } else {
        echo "Error deleting entry: " . mysqli_error($koneksi);
    }
} else {
    echo "Invalid request. Please provide a valid tag for deletion.";
}
?>
