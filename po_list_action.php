<?php

//category_action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
		$query = "
		INSERT INTO po_list (kode_po, order_date, vendor, create_date, id_po) 

		VALUES (:kode_po,:order_date,:vendor, :create_date, :id_po)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':kode_po'	=> $_POST["kode_po"],
				':order_date' => $_POST["order_date"],
				':vendor' => $_POST["vendor"],
				':create_date'	=> $_POST["create_date"],
				':id_po'	=> $_POST["id_po"],
				
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Category Name Added';
		}
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "SELECT * FROM po_list WHERE id = :id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':id'	=>	$_POST["id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['order_date'] = $row['order_date'];
			$output['kode_po'] = $row['kode_po'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE po_list SET kode_po = :kode_po, order_date = :order_date WHERE id = :id

		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':kode_po'	=>	$_POST["kode_po"],
				':order_date'	=>	$_POST["order_date"],
				':id_po'	=>	$_POST["id_po"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Data Rak Edited';
		}
	}
	if($_POST['btn_action'] == 'delete')
	{
		$status = 'hapus';
		
		$query = "DELETE FROM po_list WHERE id=:id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				
				':id' => $_POST["id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Data Telah di' . $status;
		}
	}
}

?>