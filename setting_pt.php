<?php
// setting_pt.php
include('database_connection.php');
include('header.php');


// Query untuk mengambil data dari tabel tb_identitas
$queryDataIdentitas = mysqli_query($koneksi, "SELECT * FROM tb_identitas");
$dataIdentitas = mysqli_fetch_assoc($queryDataIdentitas);

// Memeriksa apakah data identitas ditemukan
if (!$dataIdentitas) {
    // Jika tidak ada data yang ditemukan, tampilkan pesan kesalahan
    echo "Data identitas tidak ditemukan.";
    exit(); 
}
?>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Form Setting PT</strong></div>
        <div class="panel-body">
            <form action="update_identitas.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <table width="100%" border="0">
                        <tr>
                            <td width="38%">&nbsp;
                                <input type="hidden" name="id" value="<?php echo $dataIdentitas['id']; ?>">
                                <label for="nama_usaha">Nama Usaha:</label>
                                <input type="text" name="nama_usaha" class="form-control" value="<?php echo isset($dataIdentitas['nama_usaha']) ? $dataIdentitas['nama_usaha'] : ''; ?>">
                            </td>
                            <td style="display: none;"></td>
                            <td width="62%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <br>
                                <label for="alamat">Alamat:</label>
                                <input type="text" name="alamat" class="form-control" value="<?php echo isset($dataIdentitas['alamat']) ? $dataIdentitas['alamat'] : ''; ?>">
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <br>
                                <label for="telp">Telepon:</label>
                                <input type="text" name="telp" class="form-control" value="<?php echo isset($dataIdentitas['telp']) ? $dataIdentitas['telp'] : ''; ?>">
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <br>
                                <label for="logo">Logo:</label>
                                <input type="file" name="logo" class="form-control-file">
                                <input type="hidden" name="logo_path" value="<?php echo isset($dataIdentitas['logo']) ? $dataIdentitas['logo'] : ''; ?>">
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <br>
                                <input type="submit" name="submit" value="Update" class="btn btn-primary">
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>
