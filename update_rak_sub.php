<?php
// update_rak_sub.php

include('database_connection.php');

if (isset($_POST['edit_action']) && $_POST['edit_action'] == 'Edit') {
    // Menerima data dari form edit
    $id_rak_sub = $_POST['edit_id_rak_sub'];
    $nama = $_POST['edit_nama'];
    $kapasitas = $_POST['edit_kapasitas'];
    $ket = $_POST['edit_ket'];

    try {
        // Query update
        $query = "
            UPDATE rak_sub 
            SET nama = :nama, kapasitas = :kapasitas, ket = :ket
            WHERE id_rak_sub = :id_rak_sub
        ";
        
        // Persiapkan statement
        $statement = $connect->prepare($query);
        
        // Bind parameter
        $statement->bindParam(':nama', $nama);
        $statement->bindParam(':kapasitas', $kapasitas);
        $statement->bindParam(':ket', $ket);
        $statement->bindParam(':id_rak_sub', $id_rak_sub);
        
        // Eksekusi statement
        if ($statement->execute()) {
            // Jika berhasil update
            echo 'Data berhasil diupdate.';
        } else {
            // Jika gagal update
            echo 'Gagal melakukan update data.';
        }
    } catch (PDOException $e) {
        // Tangani error PDO jika terjadi
        echo 'Error: ' . $e->getMessage();
    }
}
?>
