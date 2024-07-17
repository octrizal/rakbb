<?php
// smginput_data.php

include('database_connection.php');
include('database_pg.php');

if (!isset($_SESSION["type"])) {
    header('location:login.php');
}


include('header.php');

$nama = $_GET['nama'];
$query = mysqli_query($koneksi,"SELECT * FROM bahan_baku WHERE bpb = '$nama'");
if (mysqli_num_rows($query) > 0) {
    // Jika BPB sudah ada
        echo "<script>window.alert('Kode BPB sudah terdaftar di database !')</script>";
        echo "<script>window.location='smginput.php'</script>";}
        else{
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>View Data</strong></div>
            <div class="panel-body" align="center">
                <?php
                $nama = $_GET['nama'];
                $query  = pg_query("SELECT stock_picking.id as picking_id,
                                   stock_picking.name as bpb, 
                                   stock_picking.origin, 
                                   stock_picking.date_validation, 
                                   stock_picking.location_dest_id as tujuan,
                                   res_partner.name as vendor
                                FROM stock_picking
                                JOIN res_partner ON stock_picking.x_partner_parent_id = res_partner.id
                                WHERE stock_picking.name='$nama' AND 
                                      stock_picking.location_dest_id='13' AND 
                                      stock_picking.state='done' AND
                                      stock_picking.picking_type_id='1'");
                $data = pg_fetch_array($query);
                
                if($data) {
                ?>

                <form action="simpan_bahan.php" method="post">
                    <div align="right">
                        <button type="submit" name="Simpan" class="btn btn-primary">Simpan</button>
                        <a href="smginput.php"><button type="button" name="Simpan" class="btn btn-success">Kembali</button></a>
                    </div>
                    <hr />
                    <table width="100%">
                        <tr>
                            <td width="189"><strong>Kode BPB</strong> </td>
                            <td width="20"><strong>:</strong></td>
                            <td width="1075"><?php echo $data['bpb']; ?>
                                <input type="hidden" name="kode_bpb1"  value="<?php echo $data['bpb']; ?>" />
                                <input type="hidden" name="id_bpb"  value="<?php echo $data['picking_id']; ?>" />
                                <input type="hidden" name="id_user"  value="<?php echo $_SESSION["user_id"]; ?>" /></td>
                                </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                        <tr>
                            <td><strong>Source Document </strong></td>
                            <td><strong>:</strong></td>
                            <td><?php echo $data['origin']; ?>
                                <input type="hidden" name="origin"  value="<?php echo $data['origin']; ?>" /></td>
                                </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                        <tr>
                            <td><strong>Validation Date</strong> </td>
                            <td><strong>:</strong></td>
                            <td><?php echo $data['date_validation']; ?>
                                <input type="hidden" name="date_validation"  value="<?php echo $data['date_validation']; ?>" /></td>
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
                                <input type="hidden" name="vendor"  value="<?php echo $data['vendor']; ?>" /></td>
                                </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                        <tr>
                            <td><strong>Destination Location </strong></td>
                            <td><strong>:</strong></td>
                            <td><?php
                                if ($data['tujuan']) {
                                    echo "SMG/Input";
                                    $tujuan='SMG/Input';
                                }
                                ?>
                                <input type="hidden" name="tujuan"  value="<?php echo $tujuan; ?>" /></td>
                        </tr>
                    </table>
                    <hr />
                    <div align="left"><strong>Operations</strong></div>
                    <hr />
                    <table width="100%" class="table table-bordered table-striped">
                        <tr>
                            <td width="25%"><strong>Product</strong></td>
                            <td width="20%"><strong>Unit Of Measure </strong></td>
                            <td width="15%"><strong>Lot </strong></td>
                            <td width="15%"><strong>Kategori</strong></td>
                            <td width="13%"><strong>To do </strong></td>
                            <td width="12%"><strong>Done</strong></td>
                        </tr>
                        <?php
                        $q = pg_query("SELECT stock_pack_operation.id as operation_id,
                                          stock_pack_operation.product_qty as qty,
                                          stock_pack_operation.qty_done as done,
                                          product_product.default_code as kode_brg,
                                          product_product.name_template as nama,
                                          product_uom.name as uom
                                   FROM stock_pack_operation
                                   JOIN product_product ON product_product.id = stock_pack_operation.product_id
                                   JOIN product_uom ON stock_pack_operation.product_uom_id = product_uom.id
                                   WHERE stock_pack_operation.picking_id = '{$data['picking_id']}'");
                        $jumlah_item = pg_num_rows($q);
                        while ($ar = pg_fetch_array($q)) {
                            $operation_id = $ar['operation_id'];
                            $lot_query = pg_query("SELECT lot_name 
                                                   FROM stock_pack_operation_lot 
                                                   WHERE operation_id = '$operation_id'");
        $jum= pg_num_rows($lot_query);
        $lot_names = [''];

if ($jum >= 1){
        $counter =1;
        while ($lot_data = pg_fetch_array($lot_query)) {
            $lot_names[] = $counter . ": " . $lot_data['lot_name'];
            $counter++;
        }
        $lot_names_str = implode(' Lot ', $lot_names);
    }
    else{
        while ($lot_data = pg_fetch_array($lot_query)) {
            $lot_names[] = ": " . $lot_data['lot_name'];
        }
        $lot_names_str = implode(' Lot ', $lot_names);
    }
        
    ?>
                        <tr>
                            <input name="picking_id[]" type="hidden" value="<?php echo $data['picking_id']; ?>" />
                            <input type="hidden" name="jum_item" value="<?php echo $jumlah_item; ?>">
                            <input type="hidden" name="kode_bpb[]"  value="<?php echo $data['bpb']; ?>" />
                            <td>
                                <?php echo "[$ar[kode_brg]] $ar[nama]"; ?>
                                <input name="product[]" type="hidden" value="<?php echo "[$ar[kode_brg]] $ar[nama]"; ?>"/>        
                            </td>
                            <td>
                                <?php echo $ar['uom']; ?>
                                <input type="hidden" name="uom[]"  value="<?php echo $ar['uom']; ?>"/>
                            </td>
                            <td>
                                <?php echo $lot_names_str; ?>
                                <input type="hidden" name="lot[]" value="<?php echo $lot_names_str; ?>" class="form-control" />
                            </td>
                            <td>
                                <select name="kategori[]" class="form-control">
                                    <option value="Medium Moving">Medium Moving</option>
                                    <option value="Fast Moving">Fast Moving</option>
                                    <option value="Slow Moving">Slow Moving</option>
                                </select>
                            </td>
                            <td><input type="text" name="todo[]" readonly="" value="<?php echo $ar['qty']; ?>" class="form-control" /></td>
                            <td>
                                <label>
                                    <input type="text" name="done[]" value="<?php echo $ar['done']; ?>" class="form-control"/>
                                </label>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </form>

                <?php 
                    } else {
                        echo "<script>alert('Kode BPB tidak ditemukan !'); window.location.href='smginput.php';</script>";
                    } 
                ?>
            </div>
        </div>
    </div>
</div>

<?php
        }
include('footer.php');
?>
