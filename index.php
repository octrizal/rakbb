<?php
//index.php
include('database_connection.php');
include('function.php');

// Redirect to login page if session type is not set
if(!isset($_SESSION["type"])) {
    header("location:login.php");
}

include('header.php');
?>

<br />

<div class="row">
    <?php if($_SESSION['type'] == 'master' || $_SESSION['type'] == 'user') { ?>
        <div class="col-md-4 col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>SMG Input</strong></div>
                <div class="panel-body" align="center">
					<img src="img/insert.png" width="150px" />
                    <h4><a href="smginput.php" class="btn btn-success">Input BPB</a></h4>
                </div>
            </div>
        </div>
  
    
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Lokasi Bahan Baku</strong></div>
            <div class="panel-body" align="center">
				<img src="img/lokasi.png" width="150px" />
                <h4><a href="lokasi_barang.php" class="btn btn-primary">View Data</a></h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Rak Bahan Baku</strong></div>
            <div class="panel-body" align="center">
			<img src="img/datarak.png" width="150px" />
                <h4><a href="rakbahan.php" class="btn btn-danger">Data Rak</a></h4>
            </div>
        </div>
    </div>
	  <?php } ?>
</div>

<hr />

<?php if($_SESSION['type'] == 'master' || $_SESSION['type'] == 'po') { ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            <h3 class="panel-title">List Purchase Order</h3>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table id="koderak_data" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode PO</th>
                                        <th>Order Date</th>
                                        <th>Vendor</th>
                                        <th>Create Date</th>
                                        <!-- <th>Picking Type ID</th> -->
                                        <th>ID PO</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>

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

<?php include('footer.php'); ?>
