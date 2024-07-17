<?php
//form_update_rak.php

include('database_connection.php');

if (!isset($_SESSION["type"])) {
    header('location:login.php');
}

if ($_SESSION["type"] != 'master') {
    header("location:index.php");
}

include('header.php');

// Mendapatkan id rak dari URL
// Query untuk mendapatkan data rak berdasarkan id
$queryDataRak = mysqli_query($koneksi, "SELECT * FROM lokasi_barang ");
$dataRak = mysqli_fetch_array($queryDataRak);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["proses"])) {
    // Ambil data dari formulir
    $namarak = $_POST["namarak"];
    $subrak = $_POST["subrak"];
    $qrcode = $_POST["qrcode"];
    $lot = $_POST["lot"];

    // Update data rak ke database
    $query = "UPDATE lokasi_barang 
              SET id_rak='$namarak', 
                  id_lokasi='$subrak', 
                  qr_code='$qrcode', 
                  lot='$lot' 
              WHERE id='$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Tampilkan pesan sukses dan redirect ke halaman lokasi_barang.php
        echo "<script>alert('Data berhasil diupdate.'); window.location.href='lokasi_barang.php';</script>";
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }
}

$optionsQuery = "SELECT nama_detail FROM gudang_detail WHERE id_gudang = 1149150";
$optionsResult = mysqli_query($koneksi, $optionsQuery);

if (!$optionsResult) {
    die("Error fetching options: " . mysqli_error($koneksi));
}

$subRakOptionsQuery = "SELECT nama FROM lokasi_master WHERE id_detail = 149156";
$subRakOptionsResult = mysqli_query($koneksi, $subRakOptionsQuery);

if (!$subRakOptionsResult) {
    die("Error fetching Sub Rak options: " . mysqli_error($koneksi));
}

$subRakOptions = array();
while ($subRakRow = mysqli_fetch_assoc($subRakOptionsResult)) {
    $subRakOptions[] = $subRakRow['nama'];
}
?>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Form Edit Data Rak</strong></div>
        <div class="panel-body">
            <form action="#" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <label for="namarak">Nama Rak:</label>
                    <select name="namarak" id="namarak" class="form-control" required>
                        <?php
                        while ($row = mysqli_fetch_assoc($optionsResult)) {
                            $selected = ($row['nama_detail'] == $dataRak['id_rak']) ? 'selected' : '';
                            echo "<option value='{$row['nama_detail']}' $selected>{$row['nama_detail']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="subrak">Sub Rak:</label>
                    <select name="subrak" id="subrak" class="form-control" required>
                        <?php
                        foreach ($subRakOptions as $option) {
                            $selected = ($option == $dataRak['id_lokasi']) ? 'selected' : '';
                            echo "<option value='$option' $selected>$option</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="qrcode">QR CODE:</label>
                    <input type="text" id="qrcode" name="qrcode" class="form-control" required value="<?php echo $dataRak['qr_code']; ?>">
                </div>

                <div class="form-group">
                    <label for="lot">Lot:</label>
                    <input type="text" id="lot" name="lot" class="form-control" required value="<?php echo $dataRak['lot']; ?>">
                </div>

                <div class="form-group">
                    <input type="submit" name="proses" value="Update" class="btn btn-success" />
                    <a href="lokasi_barang.php" class="btn btn-primary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
