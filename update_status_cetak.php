<?php
include('database_connection.php');

if(isset($_POST["id"]) && isset($_POST["status"])) {
    $query = "
    UPDATE lokasi_barang 
    SET status_cetak = :status 
    WHERE id = :id
    ";
    
    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':status'  => $_POST["status"],
            ':id'      => $_POST["id"]
        )
    );
    
    if($statement->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat memperbarui status.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak valid.']);
}
?>
