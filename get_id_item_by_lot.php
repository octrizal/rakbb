<?php
// get_id_item_by_lot.php

include('database_connection.php');

if(isset($_POST["lot"])) {
    $lot = $_POST["lot"];
    
    $query = "SELECT id_item FROM lokasi_barang WHERE lot = :lot";
    
    $statement = $connect->prepare($query);
    $statement->bindParam(':lot', $lot, PDO::PARAM_STR);
    $statement->execute();
    
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($result); // Mengembalikan hasil dalam format JSON
}
?>
