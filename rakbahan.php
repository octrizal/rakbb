<?php
// rakbahan.php

include('database_connection.php');

if (!isset($_SESSION["type"])) {
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
                        <h3 class="panel-title">Master Gudang</h3>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                        <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#rakModal" class="btn btn-success btn-xs">Tambah Data</button>
                    </div>
                </div>
                <div class="clear:both"></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table id="koderak_data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kode Gudang</th>
                                    <th>Date Create</th>
                                    <th>Create By</th>
                                    <th>Keterangan</th>
                                    <th>Edit</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for adding new data -->
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
                        <label>Nama Rak</label>
                        <input type="text" name="nama" id="nama" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Kode Gudang</label>
                        <input type="text" name="id_gudang" value="147" id="id_gudang" class="form-control" required readonly />
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="ket" id="ket" class="form-control" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_rak" id="id_rak" />
                    <input type="hidden" name="btn_action" id="btn_action" value="Add" />
                    <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>





<script>
$(document).ready(function() {
    $('#add_button').click(function() {
        $('#rak_form')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add Rak");
        $('#action').val("Add");
        $('#btn_action').val("Add");
    });

    var userdataTable = $('#koderak_data').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: "rakbahan_fetch.php",
            type: "POST"
        },
        "columnDefs": [
            {
                "targets": [2, 3, 4, 5], // Disable ordering for Date Create, Create By, Keterangan, and Detail columns
                "orderable": false
            }
        ],
        "pageLength": 25
    });

    $(document).on('submit', '#rak_form', function(event) {
        event.preventDefault();
        $('#action').attr('disabled', 'disabled');
        var form_data = $(this).serialize();
        $.ajax({
            url: "rakbahan_action.php",
            method: "POST",
            data: form_data,
            success: function(data) {
                $('#rak_form')[0].reset();
                $('#rakModal').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-success">' + data + '</div>');
                $('#action').attr('disabled', false);
                userdataTable.ajax.reload();
            }
        });
    });

    $(document).on('click', '.detail', function() {
        var id_rak = $(this).attr("id");
        window.location.href = "rakbahan_detail.php?id_rak=" + id_rak;
    });
});

$(document).on('click', '.edit', function() {
    var id_rak = $(this).attr("id");
    $.ajax({
        url: "get_rak_data.php",
        method: "POST",
        data: {id_rak: id_rak},
        dataType: "json",
        success: function(data) {
            $('#rakModal').modal('show');
            $('#nama').val(data.nama);
            $('#id_gudang').val(data.id_gudang);
            $('#ket').val(data.ket);
            $('#id_rak').val(id_rak);
            $('.modal-title').html("<i class='fa fa-pencil'></i> Edit Data");
            $('#action').val("Edit");
            $('#btn_action').val("Edit");
        }
    });
});

</script>

<?php include('footer.php'); ?>
