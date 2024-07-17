<?php
// generateitem.php

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
                $id_bpb = mysqli_real_escape_string($koneksi, $_GET['bpb']);
                $query  = mysqli_query($koneksi, "SELECT * FROM bahan_baku WHERE id_bpb='$id_bpb'");
                $data = mysqli_fetch_array($query);
                ?>

                <div align="right">
                    <a href="generatelabel.php"><button type="button" name="Kembali" class="btn btn-success">Kembali</button></a>
                </div>
                <hr />
                <table width="100%">
                    <tr>
                        <td width="186"><strong>Kode BPB</strong></td>
                        <td width="10"><strong>:</strong></td>
                        <td width="1089"><?php echo $data['bpb']; ?>
                            <input type="hidden" name="kode_bpb" value="<?php echo $data['bpb']; ?>" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><strong>Source Document</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php echo $data['origin']; ?>
                            <input type="hidden" name="origin" value="<?php echo $data['origin']; ?>" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><strong>Validation Date</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php echo $data['date_validation']; ?>
                            <input type="hidden" name="date_validation" value="<?php echo $data['date_validation']; ?>" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><strong>Customer/Vendor</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php echo $data['vendor']; ?>
                            <input type="hidden" name="vendor" value="<?php echo $data['vendor']; ?>" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><strong>Destination Location</strong></td>
                        <td><strong>:</strong></td>
                        <td><input type="hidden" name="tujuan" value="<?php echo $data['tujuan']; ?>" />
                            <?php echo $data['tujuan']; ?></td>
                    </tr>
                </table>
                <hr />
                <div align="left"><strong>Operations</strong></div>
                <hr />
                <table width="100%" class="table table-bordered table-striped">
                    <tr>
                        <td width="24%"><strong>Product</strong></td>
                        <td width="24%"><strong>Jumlah Label Cetak</strong></td>
                    </tr>
                    <?php
                    $q = mysqli_query($koneksi, "SELECT * FROM bahan_baku_item WHERE id_bpb='$id_bpb'");
                    while ($ar = mysqli_fetch_array($q)) {
                        $disabled = '';
                        $message = '';
                        if (empty($ar['jumlah_qrcode'])) {
                            $disabled = 'disabled';
                            $message = '' . $data['origin'] . ' belum terinput di Purchase Order';
                        }
                    ?>
                    <tr>
                        <td><?php echo $ar['product']; ?></td>
                        <td>
                            <div class="input-group">
                                <input type="number" class="form-control jumlah-qrcode" name="jumlah_qrcode[]" value="<?php echo $ar['jumlah_qrcode']; ?>" <?php echo $ar['status_rak'] ? 'disabled' : ''; ?> <?php echo $disabled; ?>>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary generate" data-product="<?php echo $ar['product']; ?>" <?php echo $ar['status_rak'] || $disabled ? 'disabled' : ''; ?>>Generate</button>
                                </span>
                            </div>
                            <?php if ($disabled) { ?>
                                <span class="help-block" style="color: red;"><?php echo $message; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

<script>
$(document).ready(function(){
    $('.generate').click(function(){
        var product = $(this).data('product');
        var jumlah_qrcode = $(this).closest('tr').find('.jumlah-qrcode').val();
        var btnGenerate = $(this);

        var bpb = '<?php echo isset($_GET['bpb']) ? $_GET['bpb'] : ''; ?>';

        $.ajax({
            url: 'insert_lokasi_barang.php?bpb=' + bpb,
            type: 'POST',
            data: { product: product, jumlah_qrcode: jumlah_qrcode },
            success: function(response){
                alert(response);
                $.ajax({
                    url: 'update_status_rak.php',
                    type: 'POST',
                    data: { product: product },
                    success: function(response){
                        alert("Status rak berhasil diperbarui.");
                        btnGenerate.addClass('disabled').prop('disabled', true);
                        btnGenerate.closest('tr').find('.jumlah-qrcode').prop('disabled', true);
                    },
                    error: function(xhr, status, error){
                        alert("Terjadi kesalahan: " + xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error){
                alert("Error: " + xhr.responseText);
            }
        });
    });
});
</script>
