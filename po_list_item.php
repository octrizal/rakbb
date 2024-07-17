<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('.edit-btn').click(function(){
            var id = $(this).data('id');
            var jumlah = $(this).data('jumlah');
            $('#editId').val(id);
            $('#jumlahQRCode').val(jumlah);
            $('#editModal').modal('show');
        });

        $('#editForm').submit(function(e){
            e.preventDefault();
            var id = $('#editId').val();
            var jumlah = $('#jumlahQRCode').val();
            $.ajax({
                url: 'process_edit.php',
                method: 'POST',
                data: {id: id, jumlah_qrcode: jumlah},
                success: function(response){
                    alert(response); // Tampilkan pesan dari process_edit.php
                    window.location.reload(); // Muat ulang halaman setelah pembaruan
                }
            });
        });
    });
</script>

<?php
//po_list_item.php

include('database_connection.php');

// Periksa apakah pengguna sudah login dan memiliki akses yang sesuai
if (!isset($_SESSION["type"])) {
    header('location:login.php');
}


include('header.php');

// Ambil data PO berdasarkan id_po yang dikirimkan melalui parameter GET
$id_po = $_GET['id_po'];
$query = mysqli_query($koneksi, "SELECT * FROM po_list WHERE id_po='$id_po'");
$data = mysqli_fetch_array($query);
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>View Data</strong></div>
            <div class="panel-body" align="center">
                <div align="right">
                    <a href="po_list.php"><button type="button" name="Kembali" class="btn btn-success">Kembali</button></a>
                </div>
                <br />
                <table width="100%" class="table table-bordered table-striped">
                    <tr>
                        <td width="186"><strong>Kode PO</strong></td>
                        <td width="10">&nbsp;</td>
                        <td width="1089"><?php echo $data['kode_po']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Order Date</strong></td>
                        <td>&nbsp;</td>
                        <td><?php echo $data['order_date']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Vendor</strong></td>
                        <td>&nbsp;</td>
                        <td><?php echo $data['vendor']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Create Date</strong></td>
                        <td>&nbsp;</td>
                        <td><?php echo $data['create_date']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>ID PO</strong></td>
                        <td>&nbsp;</td>
                        <td><?php echo $data['id_po']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Picking Type ID</strong></td>
                        <td>&nbsp;</td>
                        <td><?php echo $data['picking_type_id']; ?></td>
                    </tr>
                </table>
                <hr />
                <div align="left"><strong>Operations</strong></div>
                <hr />
                <table width="100%" class="table table-bordered table-striped">
                    <tr>
                        <td width="24%"><strong>Product</strong></td>
                        <td width="24%"><strong>Jumlah Label Cetak</strong></td>
                        <td width="10%"><strong>QTY</strong></td>
                        <td width="36%"><strong>Unit Of Measure</strong></td>
                        <td width="10%"><strong>Unit Price</strong></td>
                        <td width="10%"><strong>Scheduled Date</strong></td>
                        <td width="10%"><strong>Edit Qty QRCode</strong></td>
                    </tr>
                    <?php
                    // Ambil data item PO berdasarkan id_po
                    $q = mysqli_query($koneksi, "SELECT * FROM po_list_item WHERE id_po='$id_po'");
                    if (!$q) {
                        echo "Error: " . mysqli_error($koneksi);
                    } else {
                        while ($ar = mysqli_fetch_array($q)) {
                            echo "<tr>";
                            echo "<td>" . $ar['product'] . "</td>";
                            echo "<td>" . $ar['jumlah_qrcode'] . "</td>";
                            echo "<td>" . $ar['qty'] . "</td>";
                            echo "<td>" . $ar['uom'] . "</td>";
                            echo "<td>" . $ar['unit_price'] . "</td>";
                            echo "<td>" . $ar['scheduled_date'] . "</td>";
                            echo "<td><button class='btn btn-primary edit-btn' data-id='" . $ar['id'] . "' data-jumlah='" . $ar['jumlah_qrcode'] . "'>Update</button></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Jumlah QRCode</h4>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editId" name="id">
                    <div class="form-group">
                        <label for="jumlahQRCode">Jumlah QRCode:</label>
                        <input type="text" class="form-control" id="jumlahQRCode" name="jumlahQRCode">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>
