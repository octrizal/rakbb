<?php
include('database_connection.php');

$query = "UPDATE lokasi_barang SET status_cetak = 1 WHERE status_cetak = 0";
$statement = $connect->prepare($query);
$result = $statement->execute();

if ($result) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>
