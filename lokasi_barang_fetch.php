<?php
// databahan_fetch.php

include('database_connection.php');

$query = '';
$output = array();

$query = "
SELECT lb.*, r.nama as rak_nama, rs.nama as sub_rak_nama, bbi.lot as lot_bahan_baku
FROM lokasi_barang lb 
JOIN rak r ON lb.id_rak = r.id_rak 
LEFT JOIN rak_sub rs ON lb.id_lokasi = rs.id_rak_sub 
LEFT JOIN bahan_baku_item bbi ON lb.id_item = bbi.id
WHERE lb.status_rak = 1 
";


if (isset($_POST["search"]["value"])) {
    $query .= 'AND (lb.id_rak LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR lb.id_lokasi LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR lb.product LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR lb.lot LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR lb.no_bpb LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR lb.no_po LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR lb.kategori LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR r.nama LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR rs.nama LIKE "%' . $_POST["search"]["value"] . '%")';
    $query .= 'OR lb.id LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY lb.id_rak DESC ';
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
    $sub_array[] = $row['id_rak']; // Add ID
    $sub_array[] = $row['rak_nama'];
    $sub_array[] = $row['sub_rak_nama']; // Menggunakan nilai nama dari rak_sub
    $sub_array[] = $row['product'];
    // Tambahkan button untuk Lot dari lokasi_barang
    $sub_array[] = '<button type="button" class="btn btn-info btn-xs view_lot" data-id="'.$row["id_item"].'">'.$row["lot"].'</button>';
    $sub_array[] = $row['no_bpb'];
    $sub_array[] = $row['no_po'];
    $sub_array[] = $row['kategori'];
    $sub_array[] = '<button type="button" name="edit" id="'.$row["id"].'" class="btn btn-warning btn-xs edit">Edit</button>';
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
    $statement = $connect->prepare("SELECT * FROM lokasi_barang WHERE status_rak = 1");
    $statement->execute();
    return $statement->rowCount();
}
?>
