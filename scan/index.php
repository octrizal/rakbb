<?php
include('../database_connection.php');

// Check if URL parameter is set
if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo "<div class='notice-container'>
            <div class='notice'>
                 Halaman tidak ditemukan.
            </div>
          </div>
          <style>
              .notice-container {
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  height: 100vh;
              }
              .notice {
                  font-size: 50px;
                  font-weight: bold;
              }
          </style>";
    exit();
}

$urlParam = $_GET['url'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["proses"])) {
    $namarak = $_POST["namarak"];
    $subrak = $_POST["subrak"];
    $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : '';

    // Verifikasi kode keamanan captcha
    if ($_POST["captcha_code"] != $_SESSION["captcha_code"]) {
        header("Location: index.php?url=$urlParam&error=Incorrect+CAPTCHA+code.+Please+try+again.");
        exit();
    }

    // Fetch the ID of the subrak from rak_sub
    $subRakQuery = "SELECT id_rak_sub FROM rak_sub WHERE nama = '$subrak'";
    $subRakResult = mysqli_query($koneksi, $subRakQuery);

    if (!$subRakResult) {
        die("Error fetching subrak ID: " . mysqli_error($koneksi));
    }

    $subRakRow = mysqli_fetch_assoc($subRakResult);
    $idSubRak = $subRakRow['id_rak_sub'];

    // Fetch id_rak from the rak table based on namarak
    $rakQuery = "SELECT id_rak FROM rak WHERE nama = '$namarak'";
    $rakResult = mysqli_query($koneksi, $rakQuery);

    if (!$rakResult) {
        die("Error fetching rak ID: " . mysqli_error($koneksi));
    }

    $rakRow = mysqli_fetch_assoc($rakResult);
    $idRak = $rakRow['id_rak'];

    // Prepare the update statement
    $query = "UPDATE lokasi_barang AS lb
    INNER JOIN bahan_baku_item AS bbi ON lb.no_bpb = bbi.bpb AND lb.product = bbi.product
    SET lb.id_rak = ?, lb.id_lokasi = ?, lb.id_user = ?, lb.status_rak = 1, lb.tanggal_penyimpanan = CURDATE(),
        lb.id_item = bbi.id
    WHERE lb.url = ?";
$stmt = mysqli_prepare($koneksi, $query);

if ($stmt === false) {
die("Error preparing statement: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, 'iiis', $idRak, $idSubRak, $user_id, $urlParam);
$result = mysqli_stmt_execute($stmt);

if ($result === false) {
die("Error executing statement: " . mysqli_error($koneksi));
}

mysqli_stmt_close($stmt);

    
    // Update status_rak sudah terisi = 1 and increment terpakai in rak_sub table
    if ($result) {
        $updateStatusQuery = "UPDATE lokasi_barang SET status_rak = 1 WHERE url = ?";
        $stmtUpdateStatus = mysqli_prepare($koneksi, $updateStatusQuery);
        mysqli_stmt_bind_param($stmtUpdateStatus, 's', $urlParam);
        $resultUpdateStatus = mysqli_stmt_execute($stmtUpdateStatus);

        if ($resultUpdateStatus) {
            // Increment terpakai in rak_sub table
            $incrementTerpakaiQuery = "UPDATE rak_sub SET terpakai = terpakai + 1 WHERE id_rak_sub = ?";
            $stmtIncrementTerpakai = mysqli_prepare($koneksi, $incrementTerpakaiQuery);
            mysqli_stmt_bind_param($stmtIncrementTerpakai, 'i', $idSubRak);
            $resultIncrementTerpakai = mysqli_stmt_execute($stmtIncrementTerpakai);

            if ($resultIncrementTerpakai) {
                echo "<script>alert('Data berhasil diperbarui di database.');</script>";
            } else {
                echo "<script>alert('Gagal memperbarui terpakai di database: " . mysqli_error($koneksi) . "');</script>";
            }

            mysqli_stmt_close($stmtIncrementTerpakai);
        } else {
            echo "<script>alert('Gagal memperbarui status_rak di database: " . mysqli_error($koneksi) . "');</script>";
        }

        mysqli_stmt_close($stmtUpdateStatus);
    } else {
        echo "<script>alert('Gagal memperbarui data di database: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Query untuk mengambil opsi "Nama Rak" dari tabel rak
$optionsQuery = "SELECT DISTINCT nama FROM rak";
$optionsResult = mysqli_query($koneksi, $optionsQuery);

if (!$optionsResult) {
    die("Error fetching options: " . mysqli_error($koneksi));
}

$sql = mysqli_query($koneksi, "SELECT product, lot, status_rak FROM lokasi_barang WHERE url='$urlParam'");
$ar = mysqli_fetch_assoc($sql);

if (!$ar) {
    echo "<div class='notice-container'>
            <div class='notice'>
                 Halaman tidak ditemukan.
            </div>
          </div>
          <style>
              .notice-container {
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  height: 100vh;
              }
              .notice {
                  font-size: 50px;
                  font-weight: bold;
              }
          </style>";
    exit();
}

if ($ar['status_rak'] == 1) {
    // Jika status_rak sudah 1, tampilkan pesan barang sudah tidak bisa diakses
    echo "<div style='text-align: center; margin-top: 300px;'>
            <h2 style='color: red; font-size: 50px;'>Barang sudah tidak bisa diakses!</h2>
            <p style='font-size: 50px;'>Barang sudah dimasukkan ke dalam rak.</p>
            <form action='picking.php' method='get'>
                <input type='hidden' name='url' value='$urlParam'>
                <button type='submit' class='btn btn-danger' style='font-size: 30px;'>Ambil Barang</button>
            </form>
          </div>";
    exit(); // Keluar dari script
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Bahan Baku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <i><h3>Scan Bahan Baku</h3></i>
    </header>

    <div class="container">
        <div align="center"><img src="../img/company_logo.png"></div>
        <form method="post" action="index.php?url=<?php echo $urlParam; ?>">
            <div class="form-group">
                <label for="namarak">Nama Rak:</label>
                <select name="namarak" id="namarak" required onChange="updateSubRakOptions()">
                    <option value="">Pilih Nama Rak</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($optionsResult)) {
                        echo "<option value='{$row['nama']}'>{$row['nama']}</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" id="user_id" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
            <div class="form-group">
                <label for="subrak">Sub Rak:</label>
                <select name="subrak" id="subrak"></select>
            </div>
            <div class="form-group">
                <label for="namaitem">Nama Bahan Baku:</label>
                <input type="text" id="product" name="product" value="<?php echo $ar['product'];?>" readonly>
            </div>
            <div class="form-group">
                <label for="lot">No Batch:</label>
                <input type="text" id="lot" name="lot" value="<?php echo $ar['lot'];?>" readonly>
            </div>
            <div class="form-group">
                <label for="captcha_code">Enter Kode:</label>
                <img src="captcha.php" alt="CAPTCHA" hidden>
                <input type="text" id="captcha_code" name="captcha_code" required>
            </div>
            <button type="submit" name="proses" class="btn btn-success">Simpan</button>
        </form>
    </div>

    <footer>
        <p><i>&copy; 2024 IT PT. Indocipta Wisesa.</i></p>
    </footer>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        updateSubRakOptions(); // Panggil fungsi ini saat halaman dimuat pertama kali

        // Check if there's an error parameter in the URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('error')) {
            alert(urlParams.get('error')); // Display the error message in a pop-up
        }
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
</body>
</html>
