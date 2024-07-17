<?php
// get_subrak.php

include('database_connection.php');

if(isset($_POST["rak_id"])){
    $rak_id = $_POST["rak_id"];
    $query = "SELECT rs.*, rak.nama as nama_rak 
              FROM rak_sub rs 
              INNER JOIN rak ON rs.id_rak = rak.id_rak
              WHERE rs.id_rak = :rak_id";
    $statement = $connect->prepare($query);
    $statement->bindParam(':rak_id', $rak_id);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}
?>
