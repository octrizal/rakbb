<?php
include('../database_connection.php');
session_start();
header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["proses"])) {
    if ($_POST["captcha_code"] != $_SESSION["captcha_code"]) {
        $response['error'] = true;
        $response['message'] = "Incorrect CAPTCHA code. Please try again.";
        echo json_encode($response);
        exit;
    }

    $namarak = $_POST["namarak"];
    $subrak = $_POST["subrak"];
    $user_id = $_POST["user_id"];

    $gudangDetailQuery = "SELECT id FROM gudang_detail WHERE nama_detail = '$namarak'";
    $gudangDetailResult = mysqli_query($koneksi, $gudangDetailQuery);

    if (!$gudangDetailResult) {
        $response['error'] = true;
        $response['message'] = "Error fetching gudang_detail ID: " . mysqli_error($koneksi);
        echo json_encode($response);
        exit;
    }

    $gudangDetailRow = mysqli_fetch_assoc($gudangDetailResult);
    $idGudangDetail = $gudangDetailRow['id'];

    $lokasiMasterQuery = "SELECT id_lokasi, id_detail FROM lokasi_master WHERE nama = '$subrak'";
    $lokasiMasterResult = mysqli_query($koneksi, $lokasiMasterQuery);

    if (!$lokasiMasterResult) {
        $response['error'] = true;
        $response['message'] = "Error fetching lokasi_master ID: " . mysqli_error($koneksi);
        echo json_encode($response);
        exit;
    }

    $lokasiMasterRow = mysqli_fetch_assoc($lokasiMasterResult);
    $idLokasiMaster = $lokasiMasterRow['id_lokasi'];
    $idDetailLokasiMaster = $lokasiMasterRow['id_detail'];

    $query = "INSERT INTO lokasi_barang (id_rak, id_lokasi, id_user) VALUES ('$idGudangDetail', '$idLokasiMaster', '$user_id')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $response['error'] = false;
    } else {
        $response['error'] = true;
        $response['message'] = "Gagal menyimpan data ke database: " . mysqli_error($koneksi);
    }

    echo json_encode($response);
}
?>
