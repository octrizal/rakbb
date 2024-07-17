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
                            	<h3 class="panel-title">Rak Detail</h3>
                            </div>
							
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                            	<!--<a href="#"><button type="button" name="kembali" class="btn btn-success btn-xs">Kembali</button></a>-->
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
										<th>Detail Rak </th>
										<th>Kapasitas</th>
										<th>Terpakai</th>
										<th>Data</th>
										
									</tr>
								</thead>
								<tbody>
	        <?php
	           
	            $no = 1;
				$id_detail=$_GET['id_detail'];
	            $query =mysqli_query($koneksi,"SELECT * FROM lokasi_master where id_detail='$id_detail' ORDER BY nama ASC");
                while ($row=mysqli_fetch_array($query)) {
                    $id_detail = $row['id_detail'];
                   
	        ?>
	            <tr>
	                <td><?php echo $no++; ?></td>
	                <td><?php echo $row['nama']; ?></td>
	                <td><?php echo $row['kapasitas']; ?></td>
	                <td><?php echo $row['terpakai']; ?></td>
	                <td><button type="button" name="detail" class="btn btn-danger btn-xs" >Update</button></td>
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
