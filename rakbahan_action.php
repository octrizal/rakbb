<?php
// rakbahan_action.php

include('database_connection.php');

if (isset($_POST['btn_action'])) {
    if ($_POST['btn_action'] == 'Add') {
        // Mendapatkan data user berdasarkan user_id
        $user_query = "SELECT user_email FROM user_details WHERE user_id = :user_id";
        $user_statement = $connect->prepare($user_query);
        $user_statement->execute(array(':user_id' => $_SESSION["user_id"]));
        $user_result = $user_statement->fetch();

        // Insert data rak dengan user_email sebagai created_by
        $query = "
        INSERT INTO rak (nama, id_gudang, date_create, created_by, ket) 
        VALUES (:nama, :id_gudang, :date_create, :created_by, :ket)
        ";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':nama'        => $_POST['nama'],
                ':id_gudang'   => $_POST['id_gudang'],
                ':date_create' => date("Y-m-d H:i:s"),
                ':created_by'  => $user_result['user_email'], // Menggunakan user_email sebagai created_by
                ':ket'         => $_POST['ket']
            )
        );
        $result = $statement->fetchAll();
        if (isset($result)) {
            echo 'Data Inserted';
        }
    }

    if (isset($_POST['btn_action'])) {
        if ($_POST['btn_action'] == 'Add') {
            // Logika untuk penambahan data
        } elseif ($_POST['btn_action'] == 'Edit') {
            // Logika untuk update data
            $query = "
                UPDATE rak 
                SET nama = :nama, id_gudang = :id_gudang, ket = :ket
                WHERE id_rak = :id_rak
            ";
            $statement = $connect->prepare($query);
            $statement->execute(
                array(
                    ':nama'        => $_POST['nama'],
                    ':id_gudang'   => $_POST['id_gudang'],
                    ':ket'         => $_POST['ket'],
                    ':id_rak'      => $_POST['id_rak']
                )
            );
            echo 'Data Updated';
        }
    }
    
}
?>
