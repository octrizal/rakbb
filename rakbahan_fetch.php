<?php
// rakbahan_fetch.php

include('database_connection.php');

$query = "SELECT * FROM rak ";

if (isset($_POST["search"]["value"])) {
    $query .= 'WHERE id_gudang LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR date_create LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR nama LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY id_rak DESC ';
}

if ($_POST["length"] != -1) {
    $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();

$filtered_rows = $statement->rowCount();

foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $row['nama'];
    $sub_array[] = $row['id_gudang'];
    $sub_array[] = $row['date_create'];
    $sub_array[] = $row['created_by'];
    $sub_array[] = $row['ket'];
    $sub_array[] = '<button type="button" name="edit" id="'.$row["id_rak"].'" class="btn btn-warning btn-xs edit">Edit</button>';
    $sub_array[] = '<button type="button" name="detail" id="'.$row["id_rak"].'" class="btn btn-info btn-xs detail">Detail</button>';
    $data[] = $sub_array;
}

$output = array(
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => get_total_all_records($connect),
    "recordsFiltered" => $filtered_rows,
    "data" => $data
);

echo json_encode($output);

function get_total_all_records($connect) {
    $statement = $connect->prepare("SELECT * FROM rak");
    $statement->execute();
    return $statement->rowCount();
}
?>
