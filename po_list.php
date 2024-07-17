<?php
//po_list.php

include('database_connection.php');

if(!isset($_SESSION["type"]))
{
	header('location:login.php');
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
                            	<h3 class="panel-title">List Purchase Order</h3>
                            </div>
							<!--
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                            	<button type="button" name="add" id="add_button" data-toggle="modal" data-target="#koderakModal" class="btn btn-success btn-xs">Add</button>
                        	</div>
							-->
                        </div>
                       
                        <div class="clear:both"></div>
                   	</div>
                   	<div class="panel-body">
                   		<div class="row"><div class="col-sm-12 table-responsive">
                   			<table id="koderak_data" class="table table-bordered table-striped">
                   				<thead>
									<tr>
										<th>Kode PO</th>
										<th>Order Date</th>
										<th>Vendor</th>
										<th>Create Date</th>
										<!-- <th>Picking Type ID</th> -->
                                        <th>ID PO</th>
										<!-- <th>Edit</th> -->
										<th>Data</th>
										
									</tr>
								</thead>
                   			</table>
                   		</div>
                   	</div>
               	</div>
           	</div>
        </div>
        <!-- <div id="koderakModal" class="modal fade">
        	<div class="modal-dialog">
        		<form method="post" id="koderak_form">
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
        		</div> -->
        		</form>
				

        	</div>
        </div>
<script>
$(document).ready(function(){

	$('#add_button').click(function(){
		$('#koderak_form')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> Add Rak");
		$('#action').val("Add");
		$('#btn_action').val("Add");
	});

	var userdataTable = $('#koderak_data').DataTable({
    "processing": true,
    "serverSide": true,
    "order": [],
    "ajax": {
        url: "po_list_fetch.php",
        type: "POST"
    },
    "columnDefs": [
        { "targets": [4], "orderable": false } // Mengasumsikan kolom 'Data' ada pada indeks 4
    ],
    "pageLength": 25
});

	$(document).on('submit', '#koderak_form', function(event){
		event.preventDefault();
		$('#action').attr('disabled','disabled');
		var form_data = $(this).serialize();
		$.ajax({
			url:"po_list_action.php",
			method:"POST",
			data:form_data,
			success:function(data)
			{
				$('#koderak_form')[0].reset();
				$('#koderakModal').modal('hide');
				$('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
				$('#action').attr('disabled', false);
				userdataTable.ajax.reload();
			}
		})
	});

	// $(document).on('click', '.update', function(){
	// 	var id = $(this).attr("id");
	// 	var btn_action = 'fetch_single';
	// 	$.ajax({
	// 		url:"databahan_action.php",
	// 		method:"POST",
	// 		data:{id:id, btn_action:btn_action},
	// 		dataType:"json",
	// 		success:function(data)
	// 		{
	// 			$('#koderakModal').modal('show');
	// 			$('#kode').val(data.kode);
	// 			$('#nama').val(data.nama);
	// 			$('.modal-kode').html("<input type='text' name='kode' id='kode' class='form-control' readonly='' />");
	// 			$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit User");
	// 			$('#id').val(id);
	// 			$('#action').val('Edit');
	// 			$('#btn_action').val('Edit');
	// 		}
	// 	})
	// });

	$(document).on('click', '.delete', function(){
		var id = $(this).attr("id");
		var btn_action = "delete";
		if(confirm("Are you sure you want to delete?"))
		{
			$.ajax({
				url:"po_list_action.php",
				method:"POST",
				data:{id:id, btn_action:btn_action},
				success:function(data)
				{
					$('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
					userdataTable.ajax.reload();
				}
			})
		}
		else
		{
			return false;
		}
	});

});
</script>

<?php
include('footer.php');
?>
