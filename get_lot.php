<?php
// get_lot.php

include('database_connection.php');

if (isset($_POST["id_item"])) {
    $id_item = $_POST["id_item"];
    $query = "
    SELECT lot 
    FROM bahan_baku_item 
    WHERE id = :id_item
    ";
    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':id_item' => $id_item
        )
    );
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    echo json_encode($result);
}
?>
