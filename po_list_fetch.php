<?php
include('database_connection.php');

$query = '';
$output = array();

$query .= "SELECT kode_po, order_date, vendor, create_date, picking_type_id, id_po, id FROM po_list"; // Menambahkan picking_type_id ke SELECT

if(isset($_POST["search"]["value"])) {
    $query .= ' WHERE kode_po LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= ' OR vendor LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= ' OR id_po LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= ' OR order_date LIKE "%'.$_POST["search"]["value"].'%" ';
    // Tidak mencari picking_type_id karena tidak ada kolom dengan nama tersebut
}

if(isset($_POST["order"])) {
    $query .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
} else {
    $query .= ' ORDER BY id DESC ';
}

if($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

$data = array();
$filtered_rows = $statement->rowCount();

foreach($result as $row) {
    $sub_array = array();
    $sub_array[] = $row['kode_po'];
    $sub_array[] = $row['order_date'];
    $sub_array[] = $row['vendor'];
    $sub_array[] = $row['create_date'];
    // $sub_array[] = $row['picking_type_id']; // Menambahkan picking_type_id ke data yang ditampilkan
    $sub_array[] = $row['id_po'];
    $sub_array[] = '<a href="po_list_item.php?id_po='.$row["id_po"].'"><button type="button" class="btn btn-danger btn-xs">Detail</button></a>';
    $data[] = $sub_array;
}

$output = array(
    "draw"              => intval($_POST["draw"]),
    "recordsTotal"      => $filtered_rows,
    "recordsFiltered"   => get_total_all_records($connect),
    "data"              => $data
);
echo json_encode($output);

function get_total_all_records($connect) {
    $statement = $connect->prepare("SELECT * FROM po_list");
    $statement->execute();
    return $statement->rowCount();
}
?>
