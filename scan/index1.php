<?php
include('../database_connection.php');
include('head.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["proses"])) {
    $namarak = $_POST["namarak"];
    $subrak = $_POST["subrak"];
    $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : '';

    if ($_POST["captcha_code"] != $_SESSION["captcha_code"]) {
        echo "<script>alert('Incorrect CAPTCHA code. Please try again.');</script>";
        exit();
    }

    $gudangDetailQuery = "SELECT id FROM gudang_detail WHERE nama_detail = '$namarak'";
    $gudangDetailResult = mysqli_query($koneksi, $gudangDetailQuery);

    if (!$gudangDetailResult) {
        die("Error fetching gudang_detail ID: " . mysqli_error($koneksi));
    }

    $gudangDetailRow = mysqli_fetch_assoc($gudangDetailResult);
    $idGudangDetail = $gudangDetailRow['id'];

    $lokasiMasterQuery = "SELECT id_lokasi, id_detail FROM lokasi_master WHERE nama = '$subrak'";
    $lokasiMasterResult = mysqli_query($koneksi, $lokasiMasterQuery);

    if (!$lokasiMasterResult) {
        die("Error fetching lokasi_master ID: " . mysqli_error($koneksi));
    }

    $lokasiMasterRow = mysqli_fetch_assoc($lokasiMasterResult);
    $idLokasiMaster = $lokasiMasterRow['id_lokasi'];
    $idDetailLokasiMaster = $lokasiMasterRow['id_detail'];

    $urlParam = $_GET['url'];
    $query = "UPDATE lokasi_barang 
              SET id_rak = ?, id_lokasi = ?, id_user = ?, status_rak = 1
              WHERE url = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'iiis', $idGudangDetail, $idLokasiMaster, $user_id, $urlParam);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $updateStatusQuery = "UPDATE lokasi_barang SET status_rak = 1 WHERE url = ?";
        $stmtUpdateStatus = mysqli_prepare($koneksi, $updateStatusQuery);
        mysqli_stmt_bind_param($stmtUpdateStatus, 's', $urlParam);
        $resultUpdateStatus = mysqli_stmt_execute($stmtUpdateStatus);

        if ($resultUpdateStatus) {
            echo "<script>alert('Data berhasil diperbarui di database.');</script>";
        } else {
            echo "<script>alert('Gagal memperbarui status_rak di database: " . mysqli_error($koneksi) . "');</script>";
        }

        mysqli_stmt_close($stmtUpdateStatus);
    } else {
        echo "<script>alert('Gagal memperbarui data di database: " . mysqli_error($koneksi) . "');</script>";
    }

    mysqli_stmt_close($stmt);
}

$optionsQuery = "SELECT nama_detail FROM gudang_detail WHERE id_gudang = 1149150";
$optionsResult = mysqli_query($koneksi, $optionsQuery);

if (!$optionsResult) {
    die("Error fetching options: " . mysqli_error($koneksi));
}

$urlParam = $_GET['url'];
$sql = mysqli_query($koneksi, "SELECT * FROM lokasi_barang WHERE url='$urlParam'");
$ar = mysqli_fetch_array($sql);
?>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><i>Scan Bahan Baku</i></div>
        <br>
        <div class="panel-body">
            <form id="rakForm" method="POST" action="">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="namarak">Nama Rak:</label>
                            <select name="namarak" id="namarak" class="form-control" required onchange="updateSubRakOptions()">
                                <option value="">Pilih Nama Rak</option>
                                <?php
                                while ($row = mysqli_fetch_assoc($optionsResult)) {
                                    echo "<option value='{$row['nama_detail']}'>{$row['nama_detail']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6" style="display: none;">
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="subrak">Sub Rak:</label>
                            <select name="subrak" id="subrak" class="form-control" required></select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="namaitem">Nama Bahan Baku:</label>
                            <input type="text" name="item_bb" class="form-control" value="<?php echo $ar['product'];?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="namaitem">No Batch:</label>
                            <input type="text" name="item_bb" class="form-control" value="<?php echo $ar['lot'];?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <!-- <img src="captcha.php" alt="CAPTCHA Image"> -->
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="captcha_code">Enter Kode:</label>
                            <input type="text" id="captcha_code" name="captcha_code" class="form-control" required>
                        </div>
                    </div>
                    <br />
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button type="submit" name="proses" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        updateSubRakOptions(); // Panggil fungsi ini saat halaman dimuat pertama kali
    });

    function updateSubRakOptions() {
        var namarak = document.getElementById("namarak").value;
        var subrak = document.getElementById("subrak");
        subrak.innerHTML = "";

        if (namarak !== "") {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_subrak_options.php?namarak=" + namarak, true);
            xhr.onload = function () {
                if (xhr.status == 200) {
                    var options = JSON.parse(xhr.responseText);
                    options.forEach(function(option) {
                        var opt = document.createElement("option");
                        opt.value = option;
                        opt.text = option;
                        subrak.add(opt);
                    });
                }
            };
            xhr.send();
        }
    }
</script>

<?php include "footer.php"; ?>
