<?php
include('database_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = mysqli_real_escape_string($koneksi, $_POST['product']);
    $query = "UPDATE bahan_baku_item SET status_rak = 1 WHERE product = '$product'";
    if (mysqli_query($koneksi, $query)) {
        echo "Status rak berhasil diperbarui.";
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($koneksi);
    }
} else {
    echo "Invalid request method.";
}
?>
