<?php

//databahan_fetch.php

include('database_connection.php');

$query = '';

$output = array();

$query .= "SELECT * FROM bahan_baku ";

if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE origin LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR bpb LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR origin LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR vendor LIKE "%'.$_POST["search"]["value"].'%" ';
}

if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id DESC ';
}

if($_POST["length"] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

$filtered_rows = $statement->rowCount();
//$no=1;
foreach($result as $row)
{
	
	$sub_array = array();
	//$sub_array[] = $no++;
	$sub_array[] = $row['origin'];
	$sub_array[] = $row['bpb'];
	$sub_array[] = $row['date_validation'];
	$sub_array[] = $row['vendor'];
	$sub_array[] = $row['tujuan'];
	// $sub_array[] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<a href=databahan_item.php?id_bpb='.$row["id_bpb"].'><button type="button" class="btn btn-danger btn-xs" >Detail</button></a>';
	//$sub_array[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"    			=> 	$data
);
echo json_encode($output);

function get_total_all_records($connect)
{
	$statement = $connect->prepare("SELECT * FROM bahan_baku");
	$statement->execute();
	return $statement->rowCount();
}

?>