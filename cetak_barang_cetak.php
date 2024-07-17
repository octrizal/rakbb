<?php
include('database_connection.php');

if (!isset($_SESSION["type"])) {
    header('location:login.php');
    exit;
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
                        <h3 class="panel-title">List Label Barang - Sudah Dicetak</h3>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                       
                    </div>
                </div>
                <div class="clear:both"></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 table-responsive">
					<div align="right">
					 <button type="button" id="view_belum_cetak" class="btn btn-primary btn-xs">Label Belum Dicetak</button>
					 </div>
					 <hr />
                        <table id="lokasi_barang_data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Product</th>
                                    <th>Lot</th>
                                    <th>BPB</th>
                                    <th>PO</th>
                                    <th>URL</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    var lokasiBarangTable = $('#lokasi_barang_data').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax":{
            url:"cetak_barang_fetch.php",
            type:"POST",
            data: {status_filter: 'CETAK'}
        },
        "columnDefs":[
            {
                "targets":[0],
                "visible": false,
                "searchable": false
            },
            {
                "targets":[6],
                "render": function(data, type, row) {
                    var statusValue = row[6] == 'CETAK' ? 0 : 1;
                    var statusLabel = row[6] == 'CETAK' ? 'BATAL' : 'CETAK';
                    var btnClass = row[6] == 'CETAK' ? 'btn-warning' : 'btn-info';
                    return '<button type="button" class="btn btn-sm change-status ' + btnClass + '" data-id="' + row[0] + '" data-status="' + statusValue + '">' + statusLabel + '</button>';
                },
                "orderable":false
            }
        ],
        "pageLength": 25
    });

    $('#lokasi_barang_data').on('click', '.change-status', function(){
        var id = $(this).data("id");
        var newStatus = $(this).data("status");

        $.ajax({
            url:"update_status_cetak.php",
            method:"POST",
            data:{id:id, status:newStatus},
            dataType: "json",
            success:function(response)
            {
                if(response.status === 'success') {
                    lokasiBarangTable.ajax.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan: " + error);
            }
        });
    });

    $('#view_belum_cetak').click(function(){
        window.location.href = "cetak_barang.php";
    });
});
</script>

<?php
include('footer.php');
?>
