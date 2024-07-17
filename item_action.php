<?php

//category_action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
		$query = "
		INSERT INTO item (bpb, product, uom, kategori, todo, done) 

		VALUES (:bpb, :product, :uom, :kategori, :todo, :done)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':bpb'	=> $_POST["bpb"],
				':product' => $_POST["product"],
				':uom' => $_POST["uom"],
				':kategori'	=> $_POST["kategori"],
				':todo'	=> $_POST["todo"],
				':done'	=> $_POST["done"],
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
		$query = "SELECT * FROM item WHERE id = :id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':id'	=>	$_POST["id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['product'] = $row['product'];
			$output['bpb'] = $row['bpb'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE item SET bpb = :bpb, product = :product WHERE id = :id

		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':bpb'	=>	$_POST["bpb"],
				':product'	=>	$_POST["product"],
				':id'	=>	$_POST["id"]
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
		
		$query = "DELETE FROM item WHERE id=:id";
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