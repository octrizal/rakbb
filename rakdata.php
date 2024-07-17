<?php
//user.php

include('database_connection.php');

if(!isset($_SESSION["type"]))
{
	header('location:login.php');
}

if($_SESSION["type"] != 'master')
{
	header("location:index.php");
}

include('header.php');


?>
		<span id="alert_action"></span>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
                    <div class="panel-heading">
                    	<div class="row">
                        	<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="panel-title">Data Rak</h3>
                            </div>
							
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                            	<a href="koderak.php"><button type="button" name="kembali" class="btn btn-success btn-xs">Kembali</button></a>
                        	</div>
							
                        </div>
                       
                        <div class="clear:both"></div>
                   	</div>
                   	<div class="panel-body">
                   		<div class="row"><div class="col-sm-12 table-responsive">
                   			<table id="rak_data" class="table table-bordered table-striped">
                   				<thead>
									<tr>
										<th>No</th>
										<th>Nama Rak </th>
										<th>Keterangan</th>
										<th>Edit</th>
										<th>Data</th>
										
									</tr>
								</thead>
								<tbody>
	        <?php
	           
	            $no = 1;
				$id_gudang=$_GET['id_gudang'];
	            $query =mysqli_query($koneksi,"SELECT * FROM gudang_detail where id_gudang='$id_gudang' ORDER BY nama_detail ASC");
                while ($row=mysqli_fetch_array($query)) {
                    $id = $row['id'];
                   
	        ?>
	            <tr>
	                <td><?php echo $no++; ?></td>
	                <td><?php echo $row['nama_detail']; ?></td>
	                <td><?php echo $row['keterangan']; ?></td>
	                <td><a href="<?php echo "detail"; ?>"><button type="button" name="update" class="btn btn-warning btn-xs update">Update</button></td>
	                <td><a href="rakdata_detail.php?id_detail=<?php echo $id; ?>"><button type="button" name="detail" class="btn btn-danger btn-xs" >Detail</button></a></td>
	            </tr>
	        <?php } ?>
	    </tbody>
                   			</table>
                   		</div>
                   	</div>
               	</div>
           	</div>
        </div>
        <div id="rakModal" class="modal fade">
        	<div class="modal-dialog">
        		<form method="post" id="rak_form">
        			<div class="modal-content">
        			<div class="modal-header">
        				<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Add Data</h4>
        			</div>
        			<div class="modal-body">
        				<div class="form-group">
							<label>Enter Kode</label>
							<input type="text" name="kode" id="kode" class="form-control"  required />
							
						</div>
						<div class="form-group">
							<label>Nama Rak</label>
							<input type="text" name="nama" id="nama" class="form-control" required />
						</div>
						
        			</div>
        			<div class="modal-footer">
        				<input type="hidden" name="id" id="id" />
        				<input type="hidden" name="btn_action" id="btn_action" />
        				<input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
        				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        			</div>
        		</div>
        		</form>
				

        	</div>
        </div>
<script>

</script>

<?php
include('footer.php');
?>
