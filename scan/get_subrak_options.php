<?php
include('../database_connection.php');

$namarak = $_GET['namarak'];
$options = array();

// Query untuk mendapatkan id_rak berdasarkan nama rak yang diberikan
$idRakQuery = "SELECT id_rak FROM rak WHERE nama = '$namarak'";
$idRakResult = mysqli_query($koneksi, $idRakQuery);

if ($idRakResult) {
    $idRakRow = mysqli_fetch_assoc($idRakResult);
    $idRak = $idRakRow['id_rak'];

    // Query untuk mendapatkan nama subrak berdasarkan id_rak yang diberikan
    $subRakOptionsQuery = "SELECT nama FROM rak_sub WHERE id_rak = $idRak";

    $subRakOptionsResult = mysqli_query($koneksi, $subRakOptionsQuery);

    if ($subRakOptionsResult) {
        while ($subRakRow = mysqli_fetch_assoc($subRakOptionsResult)) {
            $options[] = $subRakRow['nama'];
        }
    }
}

echo json_encode($options);
?>
