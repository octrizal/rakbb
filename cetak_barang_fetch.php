<?php
include('database_connection.php');

$query = '';
$output = array();

$query .= "SELECT id, product, lot, no_bpb, no_po, url, status_cetak FROM lokasi_barang ";

if(isset($_POST["search"]["value"])) {
    $query .= 'WHERE (product LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR lot LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR no_bpb LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR no_po LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR url LIKE "%'.$_POST["search"]["value"].'%" ) ';
}

if(isset($_POST["status_filter"])) {
    $status_filter = $_POST["status_filter"] == 'BELUM CETAK' ? 0 : 1;
    $query .= 'AND status_cetak = '.$status_filter.' ';
}

if(isset($_POST["order"])) {
    $query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
} else {
    $query .= 'ORDER BY id DESC ';
}

if($_POST["length"] != -1) {
    $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();

foreach($result as $row) {
    $sub_array = array();
    $sub_array[] = $row["id"];
    $sub_array[] = $row["product"];
    $sub_array[] = $row["lot"];
    $sub_array[] = $row["no_bpb"];
    $sub_array[] = $row["no_po"];
    $sub_array[] = $row["url"];
    $sub_array[] = $row["status_cetak"] == 1 ? 'CETAK' : 'BELUM CETAK';
    $sub_array[] = $row["status_cetak"] == 1 ? 'CETAK' : 'BELUM CETAK';
    $data[] = $sub_array;
}

$output = array(
    "draw"            => intval($_POST["draw"]),
    "recordsTotal"    => $filtered_rows,
    "recordsFiltered" => get_total_all_records($status_filter),
    "data"            => $data
);

echo json_encode($output);

function get_total_all_records($status_filter) {
    global $connect;
    $statement = $connect->prepare("SELECT * FROM lokasi_barang WHERE status_cetak = :status_filter");
    $statement->execute(['status_filter' => $status_filter]);
    return $statement->rowCount();
}
?>