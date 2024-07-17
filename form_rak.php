<?php
//form_rak.php

include('database_connection.php');

if (!isset($_SESSION["type"])) {
    header('location:login.php');
}

if ($_SESSION["type"] != 'master') {
    header("location:index.php");
}

include('header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["proses"])) {
    // Ambil data dari formulir
    $namarak = $_POST["namarak"];
    $subrak = $_POST["subrak"];
    $qrcode = $_POST["qrcode"];
    $id_item = $_POST["id_item"];
    $user_id = $_POST["user_id"];

    // Ambil ID dari gudang_detail
    $gudangDetailQuery = "SELECT id FROM gudang_detail WHERE nama_detail = '$namarak'";
    $gudangDetailResult = mysqli_query($koneksi, $gudangDetailQuery);

    if (!$gudangDetailResult) {
        die("Error fetching gudang_detail ID: " . mysqli_error($koneksi));
    }

    $gudangDetailRow = mysqli_fetch_assoc($gudangDetailResult);
    $idGudangDetail = $gudangDetailRow['id'];

    // Ambil ID dari lokasi_master
    $lokasiMasterQuery = "SELECT id_lokasi, id_detail FROM lokasi_master WHERE nama = '$subrak'";
    $lokasiMasterResult = mysqli_query($koneksi, $lokasiMasterQuery);

    if (!$lokasiMasterResult) {
        die("Error fetching lokasi_master ID: " . mysqli_error($koneksi));
    }

    $lokasiMasterRow = mysqli_fetch_assoc($lokasiMasterResult);
    $idLokasiMaster = $lokasiMasterRow['id_lokasi'];
    $idDetailLokasiMaster = $lokasiMasterRow['id_detail'];

    // Ambil data produk dari bahan_baku_item
    $produkQuery = "SELECT product, uom, kategori, lot FROM bahan_baku_item WHERE id = '$id_item'";
    $produkResult = mysqli_query($koneksi, $produkQuery);

    if (!$produkResult) {
        die("Error fetching product data: " . mysqli_error($koneksi));
    }

    $produkRow = mysqli_fetch_assoc($produkResult);
    $produk = $produkRow['product'];
    $uom = $produkRow['uom'];
    $kategori = $produkRow['kategori'];
    $lot = $produkRow['lot']; // Ambil nilai Lot dari tabel bahan_baku_item

    // Simpan data ke database
    $query = "INSERT INTO lokasi_barang (id_rak, id_lokasi, id_item, product, uom, kategori, qr_code, create_date, lot, id_user, no_po, no_bpb  ) VALUES ('$idGudangDetail', '$idLokasiMaster', '$id_item', '$produk', '$uom', '$kategori', '$qrcode', NOW(), '$lot',  '$user_id', '$no_po', '$no_bpb' )";
    $result = mysqli_query($koneksi, $query);

    //update data status_rak kedalam database
    mysqli_query($koneksi, "UPDATE bahan_baku_item SET status_rak='1' WHERE id = '$id_item'");

    if ($result) {
        // Tampilkan popup
        echo "<script>alert('Data berhasil disimpan ke database.'); window.location.href='lokasi_barang.php';</script>";
    } else {
        echo "Gagal menyimpan data ke database: " . mysqli_error($koneksi);
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

// Tambahkan query untuk mendapatkan opsi "Nama Item" dari tabel "bahan_baku_item"
$namaItemOptionsQuery = "SELECT DISTINCT product FROM bahan_baku_item";
$namaItemOptionsResult = mysqli_query($koneksi, $namaItemOptionsQuery);

if (!$namaItemOptionsResult) {
    die("Error fetching Nama Item options: " . mysqli_error($koneksi));
}

$namaItemOptions = array();
while ($namaItemRow = mysqli_fetch_assoc($namaItemOptionsResult)) {
    $namaItemOptions[] = $namaItemRow['product'];
}
?>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Form Data Rak </strong></div>
        <br>
        <div class="panel-body">
            <form action="#" method="post">
                <div class="form-group">
                    <table width="100%" border="0">
                        <tr>
                            <td width="38%">&nbsp;
                            <script>
    document.addEventListener("DOMContentLoaded", function () {
        updateSubRakOptions(); // Panggil fungsi ini saat halaman dimuat pertama kali
    });

    function updateSubRakOptions() {
        var namarak = document.getElementById("namarak");
        var subrak = document.getElementById("subrak");

        // Kosongkan pilihan sub rak
        subrak.innerHTML = "";

        // Ambil pilihan Nama Rak yang dipilih
        var selectedNamarak = namarak.value;

        // Tambahkan opsi Sub Rak berdasarkan Nama Rak yang dipilih
        switch (selectedNamarak) {
            case "1P":
                <?php
                foreach ($subRakOptions as $option) {
                    echo "subrak.options.add(new Option('$option', '$option'));";
                }
                ?>
                break;
            case "1U":
                <?php
                // Ambil opsi Sub Rak berdasarkan id_detail = 1149157
                $subRakOptionsQuery = "SELECT nama FROM lokasi_master WHERE id_detail = 1149157";
                $subRakOptionsResult = mysqli_query($koneksi, $subRakOptionsQuery);

                if (!$subRakOptionsResult) {
                    die("Error fetching Sub Rak options: " . mysqli_error($koneksi));
                }

                while ($subRakRow = mysqli_fetch_assoc($subRakOptionsResult)) {
                    echo "subrak.options.add(new Option('{$subRakRow['nama']}', '{$subRakRow['nama']}'));";
                }
                ?>
                break;
            // Tambahkan case untuk Nama Rak lain jika diperlukan
        }
    }
</script>


                                <label for="namarak">Nama Rak:</label>
                                <select name="namarak" id="namarak" class="form-control" required onchange="updateSubRakOptions()">
                                    <?php
                                    while ($row = mysqli_fetch_assoc($optionsResult)) {
                                        echo "<option value='{$row['nama_detail']}'>{$row['nama_detail']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td style="display: none;">
                                <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                            </td>

                            <td width="62%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <label for="subrak">Sub Rak:</label>
                                <select name="subrak" id="subrak" class="form-control" required></select>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <label for="namaitem">Nama Item:</label>
                                <select name="id_item" id="id_item" class="form-control" required>
                                    <?php
                                    $dt = mysqli_query($koneksi, "SELECT * FROM bahan_baku_item WHERE status_rak='0'");
                                    while ($ar = mysqli_fetch_array($dt)) {
                                        echo "<option value={$ar['id']}>{$ar['product']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <label for="qrcode">QR CODE:</label>
                                <input type="text" id="qrcode" name="qrcode" class="form-control" required size="50%">
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <!-- Hapus bagian input untuk Lot -->
                        <!-- <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <label for="lot">Lot:</label>
                                <input type="text" id="lot" name="lot" class="form-control" required>
                            </td>
                            <td>&nbsp;</td>
                        </tr> -->
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" name="proses" value="Simpan" class="btn btn-success" />&nbsp;
                                <a href="lokasi_barang.php"><button type="button" name="kembali" class="btn btn-primary">Kembali</button></a>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
