<?php
// update_data.php

include('database_connection.php');

if(isset($_POST["edit_id"]))
{
    $edit_id = $_POST["edit_id"];
    $edit_rak = $_POST["edit_rak"];
    $edit_subrak = $_POST["edit_subrak"];
    $edit_kategori = $_POST["edit_kategori"];
    // $edit_squance = $_POST["edit_squance"]; // Squance sebagai hidden input

    // Lakukan validasi data jika diperlukan

    // Query untuk update data
    $query = "
    UPDATE lokasi_barang 
    SET id_rak = :id_rak, 
        id_lokasi = :id_lokasi, 
        kategori = :kategori 
    WHERE id = :id
    ";

    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':id_rak'      => $edit_rak,
            ':id_lokasi'   => $edit_subrak,
            ':kategori'    => $edit_kategori,
            ':id'          => $edit_id
        )
    );

    if($statement)
    {
        echo 'Data berhasil diperbarui.';
    }
    else {
        echo 'Gagal memperbarui data.';
    }
}
?>
