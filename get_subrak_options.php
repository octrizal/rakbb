<?php
include('database_connection.php');

// Ambil ID Rak dari permintaan GET
$id_rak = $_GET['id_rak'];

// Kueri untuk mendapatkan pilihan sub rak berdasarkan ID Rak
$query = "SELECT id_lokasi, nama FROM lokasi_master WHERE id_detail = '$id_rak'";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error fetching subrak options: " . mysqli_error($koneksi));
}

$options = array();
while ($row = mysqli_fetch_assoc($result)) {
    $options[] = $row; // Ambil id_lokasi dan nama sub rak
}

echo json_encode($options); // Kembalikan sebagai respons JSON
?>
