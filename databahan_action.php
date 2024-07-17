<?php

//category_action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
		$query = "
		INSERT INTO bahan_baku (bpb, origin, date_validation, vendor, tujuan) 

		VALUES (:bpb,:origin,:date_validation, :vendor, :tujuan)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':bpb'	=> $_POST["bpb"],
				':origin' => $_POST["origin"],
				':date_validation' => $_POST["date_validation"],
				':vendor'	=> $_POST["vendor"],
				':tujuan'	=> $_POST["tujuan"],
				
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
		$query = "SELECT * FROM bahan_baku WHERE id = :id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':id'	=>	$_POST["id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['origin'] = $row['origin'];
			$output['bpb'] = $row['bpb'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE bahan_baku SET bpb = :bpb, origin = :origin WHERE id = :id

		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':bpb'	=>	$_POST["bpb"],
				':origin'	=>	$_POST["origin"],
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
		
		$query = "DELETE FROM bahan_baku WHERE id=:id";
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