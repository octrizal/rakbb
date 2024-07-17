<?php
//lokasi_barang.php

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
                        <h3 class="panel-title">List Lokasi Barang</h3>
                    </div>
                </div>
                <div class="clear:both"></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                    <table id="lokasi_barang_data" class="table table-bordered table-striped">
                    <thead>
    <tr>
        <th>ID</th> <!-- Changed from Squance to ID -->
        <th>Rak</th>
        <th>Sub Rak</th>
        <th>Item</th>
        <th>Lot</th>
        <th>BPB</th>
        <th>PO</th>
        <th>Kategori</th>
        <th>Edit</th>
    </tr>
</thead>
</table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan id_item -->
<div id="view_lot_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details Lot From Odoo</h4>
            </div>
            <div class="modal-body">
                <p id="item_detail"></p>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Data</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="edit_form" action="update_data.php">
                    <div class="form-group">
                        <label for="edit_product">Produk:</label>
                        <input type="text" class="form-control" id="edit_product" name="edit_product" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_lot">Lot:</label>
                        <input type="text" class="form-control" id="edit_lot" name="edit_lot" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_rak">Rak:</label>
                        <select class="form-control" id="edit_rak" name="edit_rak">
                            <?php
                            $query_rak = "SELECT * FROM rak";
                            $statement_rak = $connect->prepare($query_rak);
                            $statement_rak->execute();
                            $result_rak = $statement_rak->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result_rak as $row_rak) {
                                echo '<option value="' . $row_rak["id_rak"] . '">' . $row_rak["nama"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_subrak">Sub Rak:</label>
                        <select class="form-control" id="edit_subrak" name="edit_subrak">
                            <!-- Options for sub rak will be filled dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_kategori">Kategori:</label>
                        <select class="form-control" id="edit_kategori" name="edit_kategori">
                            <?php
                            // Ambil enum values dari field kategori
                            $query_enum = "SHOW COLUMNS FROM lokasi_barang LIKE 'kategori'";
                            $statement_enum = $connect->prepare($query_enum);
                            $statement_enum->execute();
                            $enum_column = $statement_enum->fetch(PDO::FETCH_ASSOC);
                            preg_match_all("/'(.*?)'/", $enum_column['Type'], $enum_array);
                            $enum_values = $enum_array[1];

                            // Generate options based on enum values
                            foreach ($enum_values as $enum_value) {
                                echo '<option value="' . $enum_value . '">' . $enum_value . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" id="edit_id" name="edit_id">
                    <!-- Input squance sebagai hidden field -->
                    <input type="hidden" id="edit_squance" name="edit_squance">
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
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
            url:"lokasi_barang_fetch.php",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[0], // Squance column
                "visible": false, // Hide squance column
                "searchable": false
            },
            {
                "targets":[8], // Edit button column
                "orderable":false
            }
        ],
        "pageLength": 25
    });

    // Event listener untuk button "Lot"
    $(document).on('click', '.view_lot', function(){
        var id_item = $(this).data('id');
        $.ajax({
            url: "get_lot.php",
            method: "POST",
            data: { id_item: id_item },
            dataType: "json",
            success: function(data) {
                $('#item_detail').text('Lot: ' + data.lot);
                $('#view_lot_modal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    // Fungsi untuk menampilkan data awal di form modal saat tombol edit ditekan
    $(document).on('click', '.edit', function() {
        var id = $(this).attr("id"); // Mengambil id dari button edit yang diklik
        $.ajax({
            url: "get_data.php",
            method: "POST",
            data: { id: id }, // Mengirimkan id sebagai parameter
            dataType: "json",
            success: function(data) {
                // Mengisi nilai dari data yang diterima ke dalam form modal
                $('#edit_id').val(data.id);
                $('#edit_rak').val(data.id_rak);
                
                // Mengisi sub rak
                var selectedSubRak = data.id_lokasi ? data.id_lokasi : ''; // Nilai sub rak yang dipilih
                $.ajax({
                    url: "get_subrak.php",
                    method: "POST",
                    data: { rak_id: data.id_rak },
                    dataType: "json",
                    success: function(subrakData) {
                        var options = '<option value="">Pilih Sub Rak</option>';
                        for (var i = 0; i < subrakData.length; i++) {
                            options += '<option value="' + subrakData[i].id_rak_sub + '">' + subrakData[i].nama + '</option>';
                        }
                        $('#edit_subrak').html(options);
                        $('#edit_subrak').val(selectedSubRak); // Tetapkan kembali nilai yang dipilih sebelumnya
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error); // Menampilkan pesan error jika terjadi masalah
                    }
                });

                // Mengisi kategori
                var enumValues = <?php echo json_encode($enum_values); ?>; // Mengambil enum values dari PHP
                var kategoriOptions = '<option value="">Pilih Kategori</option>';
                for (var i = 0; i < enumValues.length; i++) {
                    kategoriOptions += '<option value="' + enumValues[i] + '">' + enumValues[i] + '</option>';
                }
                $('#edit_kategori').html(kategoriOptions);
                $('#edit_kategori').val(data.kategori); // Memilih kategori yang sesuai dengan data yang diterima

                // Mengisi nilai lainnya
                $('#edit_item').val(data.id_item);
                $('#edit_product').val(data.product);
                $('#edit_uom').val(data.uom);
                $('#edit_qr_code').val(data.qr_code);
                $('#edit_create_date').val(data.create_date);
                $('#edit_lot').val(data.lot);
                $('#edit_id_user').val(data.id_user);
                $('#edit_ket').val(data.ket);
                $('#edit_no_po').val(data.no_po);
                $('#edit_no_bpb').val(data.no_bpb);
                $('#edit_status_rak').val(data.status_rak);
                $('#edit_url').val(data.url);
                $('#edit_tanggal_penyimpanan').val(data.tanggal_penyimpanan);
                $('#edit_squance').val(data.squance);

                $('#edit_modal').modal('show'); // Menampilkan form modal setelah data terisi
            },
            error: function(xhr, status, error) {
                console.error('Error:', error); // Menampilkan pesan error jika terjadi masalah
            }
        });
    });

    // Fungsi untuk meng-handle submit form update
    $('#edit_form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: "update_data.php",
            method: "POST",
            data: $(this).serialize(), // Mengirimkan semua data dari form
            success: function(data) {
                $('#edit_modal').modal('hide');
                alert(data); // Menampilkan pesan dari server (misalnya, "Data berhasil diperbarui.")
                lokasiBarangTable.ajax.reload(); // Me-refresh tabel setelah pembaruan data
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error); // Tampilkan pesan error jika terjadi masalah
            }
        });
    });

});
</script>


<?php
include('footer.php');
?>
