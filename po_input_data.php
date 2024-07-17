<?php
//po_input_data.php

include('database_connection.php');
include('database_pg.php');

if (!isset($_SESSION["type"])) {
    header('location:login.php');
}


include('header.php');

$nama = $_GET['nama'];
$query = mysqli_query($koneksi,"SELECT * FROM po_list WHERE kode_po = '$nama'");
if (mysqli_num_rows($query) > 0) {
    // Jika PO sudah ada
        echo "<script>window.alert('Kode PO sudah terdaftar di database !')</script>";
        echo "<script>window.location='po_input.php'</script>";}
        else{



?>


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>View Data</strong></div>
            <div class="panel-body" align="center">
                <?php
                $nama = $_GET['nama'];
                $query = pg_query("SELECT 
                                        purchase_order.id as picking_id,
                                        purchase_order.name as kode_po,
                                        purchase_order.date_order,
                                        res_partner.name as vendor,
                                        purchase_order.create_date,
                                        purchase_order.id as id_po,
                                        purchase_order.picking_type_id
                                    FROM purchase_order, res_partner
                                    WHERE purchase_order.partner_id=res_partner.id AND 
                                        purchase_order.name='$nama'");
                $data = pg_fetch_array($query);

                if($data > 0) { ?>
                    <form action="simpan_po.php" method="post">
                        <div align="right">
                            <button type="submit" name="Simpan" class="btn btn-primary">Simpan</button>
                            <a href="po_input.php"><button type="button" name="Simpan" class="btn btn-success">Kembali</button></a>
                        </div>
                        <br />
                        <table width="100%" class="table table-bordered table-striped">
                            <tr>
                                <td width="152"><strong>Kode PO</strong> </td>
                                <input type="hidden" name="kode_po"  value="<?php echo $data['kode_po']; ?>" />
                                <td><?php echo $data['kode_po']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Order Date </strong></td><input type="hidden" name="date_order"  value="<?php echo $data['date_order']; ?>" />
                                <td><?php echo $data['date_order']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Vendor</strong> </td><input type="hidden" name="vendor"  value="<?php echo $data['vendor']; ?>" />
                                <td><?php echo $data['vendor']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Create Date</strong></td><input type="hidden" name="create_date"  value="<?php echo $data['create_date']; ?>" />
                                <td><?php echo $data['create_date']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>ID PO</strong></td><input type="hidden" name="id_po"  value="<?php echo $data['id_po']; ?>" />
                                <td><?php echo $data['id_po']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Picking Type ID</strong></td>
                                <td><?php echo $data['picking_type_id']; ?></td>
                                <input type="hidden" name="picking_type_id" value="<?php echo $data['picking_type_id']; ?>">

                                
                            </tr>
                        </table>
                        <hr />
                        <div align="left"><strong>Operations</strong></div>
                        <hr />
                        <table width="100%" class="table table-bordered table-striped">
                            <tr>
                                <td width="24%"><strong>Product</strong></td>
                                <td width="24%"><strong>Jumlah Label Cetak</strong></td>
                                <td width="36%"><strong>Quantity </strong></td>
                                <td width="10%"><strong>Product UOM</strong></td>
                                <td width="10%"><strong>Price Unit </strong></td>
                                <td width="10%"><strong>Scheduled Date</strong></td>
                            </tr>
                            <?php
                            $id = $data['picking_id'];
                            $q = pg_query("SELECT
                                                purchase_order_line.name as product,
                                                purchase_order_line.product_qty,
                                                product_uom.name as uom,
                                                purchase_order_line.price_unit,
                                                purchase_order_line.date_planned,
                                                purchase_order_line.id 
                                            FROM purchase_order_line, product_uom
                                            WHERE purchase_order_line.product_uom = product_uom.id AND
                                                purchase_order_line.order_id='$id'");
                            $x = 0;
                            while ($ar = pg_fetch_array($q)) { ?>
                                <tr>
                                    <td><?php echo $ar['product']; ?>
                                        <input type="hidden" name="product[]" value="<?php echo $ar['product']; ?>" />
                                    </td>
                                    <td>
                                        <!-- Gunakan ID unik untuk setiap elemen jumlah_qrcode -->
                                        <input type="number" name="jumlah_qrcode[]" id="jumlah_qrcode_<?php echo $x; ?>" class="jumlah_qrcode" value="" />
                                    </td>
                                    <script>
                                        $(document).ready(function(){
                                            $('#jumlah_qrcode_<?php echo $x; ?>').on('input', function() {
                                                this.value = this.value.replace(/[^0-9]/g, '');
                                            });
                                        });
                                    </script>
                                    <td><?php echo $ar['product_qty']; ?>
                                        <input type="hidden" name="product_qty[]" value="<?php echo $ar['product_qty']; ?>" />
                                    </td>
                                    <td><?php echo $ar['uom']; ?>
                                        <input type="hidden" name="uom[]" readonly="" value="<?php echo $ar['uom']; ?>" />
                                    </td>
                                    <td><?php echo $ar['price_unit']; ?>
                                        <input type="hidden" name="price_unit[]" value="<?php echo $ar['price_unit']; ?>" />
                                    </td>
                                    <td><?php echo $ar['date_planned']; ?>
                                        <input type="hidden" name="date_planned[]" value="<?php echo $ar['date_planned']; ?>" />
                                    </td>
                                </tr>
                            <?php $x++; } ?>
                        </table>
                    </form>
                <?php } else {
                    echo "<script>alert('Kode PO tidak ditemukan !'); window.location.href='po_input.php';</script>";
                } ?>
            </div>
        </div>
    </div>
</div>

<?php
}
include('footer.php');
?>
