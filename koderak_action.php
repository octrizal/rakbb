<?php

//category_action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
		$query = "
		INSERT INTO gudang_master (kode,nama,create) 
		VALUES (:kode,:nama,:create)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':kode'	=> $_POST["kode"],
				':nama' => $_POST["nama"],
				':create' => '$tgl_skg'
				
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
		$query = "SELECT * FROM gudang_master WHERE id = :id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':id'	=>	$_POST["id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['nama'] = $row['nama'];
			$output['kode'] = $row['kode'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE gudang_master 
		set kode = :kode, 
		nama = :nama  
		WHERE id = :id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':kode'	=>	$_POST["kode"],
				':nama'	=>	$_POST["nama"],
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
		
		$query = "DELETE FROM gudang_master WHERE id=:id";
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