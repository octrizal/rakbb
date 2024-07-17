<?php
// get_rak_data.php

include('database_connection.php');

if(isset($_POST["id_rak"])) {
    $query = "SELECT * FROM rak WHERE id_rak = :id_rak";
    $statement = $connect->prepare($query);
    $statement->execute(array(':id_rak' => $_POST["id_rak"]));
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
}

?>