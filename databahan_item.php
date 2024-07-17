<?php
// user.php

include('database_connection.php');

if (!isset($_SESSION["type"])) {
    header('location:login.php');
}


include('header.php');
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>View Data</strong></div>
            <div class="panel-body" align="center">
                <?php
                // Pastikan variable $nama sudah didefinisikan sebelum digunakan
                $id_bpb= $_GET['id_bpb'];

                $query  = mysqli_query($koneksi, "SELECT * FROM bahan_baku where id_bpb='$id_bpb'");

                $data = mysqli_fetch_array($query);
                ?>


                <div align="right">
                    <a href="databahan.php"><button type="button" name="Kembali" class="btn btn-success">Kembali</button></a>
                </div>
                <hr />
                <table width="100%">
                    <tr>
                        <td width="186"><strong>Kode BPB</strong> </td>
                        <td width="10"><strong>:</strong></td>
                        <td width="1089"><?php echo "$data[bpb]"; ?>
                            <input type="hidden" name="kode_bpb"  value="<?php echo "$data[bpb]"; ?>" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><strong>Source Document </strong></td>
                        <td><strong>:</strong></td>
                        <td><?php echo "$data[origin]"; ?>
                            <input type="hidden" name="origin"  value="<?php echo "$data[origin]"; ?>" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><strong>Validation Date</strong> </td>
                        <td><strong>:</strong></td>
                        <td><?php echo "$data[date_validation]"; ?>
                            <input type="hidden" name="date_validation"  value="<?php echo "$data[date_validation]"; ?>" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><strong>Customer/Vendor</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php echo "$data[vendor]"; ?>
                            <input type="hidden" name="vendor"  value="<?php echo "$data[vendor]"; ?>" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><strong>Destenation Location </strong></td>
                        <td><strong>:</strong></td>
                        <td><input type="hidden" name="tujuan"  value="<?php echo "$data[tujuan]"; ?>" />
                            <?php echo "$data[tujuan]"; ?></td>
                    </tr>
                </table>
                <hr />
                <div align="left"><strong>Operations</strong></div>
                <hr />
                <table width="100%" class="table table-bordered table-striped">
                    <tr>
                        <td width="24%"><strong>Product</strong></td>
                        <td width="24%"><strong>Lot</strong></td>
                        <td width="22%"><strong>Unit Of Measure </strong></td>
                        <td width="18%"><strong>Category</strong></td>
                        <td width="10%"><strong>To do </strong></td>
                        <td width="9%"><strong>Done</strong></td>
                        <td width="17%"><strong>Action </strong></td>
                    </tr>
                    <?php
                    $id_bpb = $data['id_bpb'];
                    $q = mysqli_query($koneksi, "SELECT * FROM bahan_baku_item WHERE id_bpb='$id_bpb'");
                    while ($ar = mysqli_fetch_array($q)) {
                    ?>
                        <tr>
                            <td><?php echo $ar['product']; ?></td>
                            <td><?php echo $ar['lot']; ?></td>
                            <td><?php echo $ar['uom']; ?></td>
                            <td><?php echo $ar['kategori']; ?></td>
                            <td><?php echo $ar['todo']; ?></td>
                            <td><?php echo $ar['done']; ?></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm edit-done" data-id="<?php echo $ar['id']; ?>" data-done="<?php echo $ar['done']; ?>" data-lot="<?php echo $ar['lot']; ?>">Edit</button>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="editDoneModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="done">Done:</label>
                    <input type="text" class="form-control" id="done">
                    <input type="hidden" id="doneId">
                </div>
                <div class="form-group">
                    <label for="lot">Lot:</label>
                    <input type="text" class="form-control" id="lot">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveDone">Save</button>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

<script>
$(document).ready(function(){
    $('.edit-done').click(function(){
        var id = $(this).data('id');
        var done = $(this).data('done');
        var lot = $(this).data('lot'); // Mengambil nilai lot dari tombol edit
        $('#done').val(done);
        $('#lot').val(lot); // Menampilkan nilai lot pada input di modal
        $('#doneId').val(id);
        $('#editDoneModal').modal('show');
    });

    $('#saveDone').click(function(){
        var id = $('#doneId').val();
        var done = $('#done').val();
        var lot = $('#lot').val(); // Mengambil nilai lot yang diubah dari input di modal
        $.ajax({
            url: 'update_done.php',
            type: 'POST',
            data: { id: id, done: done, lot: lot }, // Mengirimkan nilai lot ke skrip pemrosesan
            success: function(response){
                location.reload();
            }
        });
    });
});
</script>
