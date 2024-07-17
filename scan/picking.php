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

$sql = mysqli_query($koneksi, "SELECT lokasi_barang.product, lokasi_barang.lot, lokasi_barang.status_rak, lokasi_barang.id_lokasi, rak_sub.nama 
                                FROM lokasi_barang 
                                JOIN rak_sub ON lokasi_barang.id_lokasi = rak_sub.id_rak_sub 
                                WHERE lokasi_barang.url='$urlParam'");
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["simpan"])) {
    $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : '';

    // Verifikasi kode keamanan captcha
    if ($_POST["captcha_code"] != $_SESSION["captcha_code"]) {
        header("Location: picking.php?url=$urlParam&error=Incorrect+CAPTCHA+code.+Please+try+again.");
        exit();
    }

    // Update status_rak menjadi 2
    $updateStatusQuery = "UPDATE lokasi_barang SET status_rak = 2 WHERE url = ?";
    $stmtUpdateStatus = mysqli_prepare($koneksi, $updateStatusQuery);
    mysqli_stmt_bind_param($stmtUpdateStatus, 's', $urlParam);
    $resultUpdateStatus = mysqli_stmt_execute($stmtUpdateStatus);

    if ($resultUpdateStatus) {
        // Relate id_lokasi to id_rak_sub and decrement terpakai
        $id_lokasi = $ar['id_lokasi'];
        $updateRakSubQuery = "UPDATE rak_sub SET terpakai = terpakai - 1 WHERE id_rak_sub = ?";
        $stmtUpdateRakSub = mysqli_prepare($koneksi, $updateRakSubQuery);
        mysqli_stmt_bind_param($stmtUpdateRakSub, 's', $id_lokasi);
        $resultUpdateRakSub = mysqli_stmt_execute($stmtUpdateRakSub);

        if ($resultUpdateRakSub) {
            echo "<script>alert('Berhasil Mengambil Bahan Baku'); window.location.href = 'sukses.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui rak_sub: " . mysqli_error($koneksi) . "');</script>";
        }

        mysqli_stmt_close($stmtUpdateRakSub);
    } else {
        echo "<script>alert('Gagal memperbarui status rak: " . mysqli_error($koneksi) . "');</script>";
    }

    mysqli_stmt_close($stmtUpdateStatus);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Picking Bahan Baku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <i><h3>Picking Bahan Baku</h3></i>
    </header>

    <div class="container">
        <div align="center"><img src="../img/company_logo.png"></div>
        <form method="post" action="picking.php?url=<?php echo $urlParam; ?>">
            <div class="form-group">
                <!-- <label for="id_lokasi">ID Lokasi:</label> -->
                <input type="hidden" id="id_lokasi" name="id_lokasi" value="<?php echo $ar['id_lokasi']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nama">Nama Rak Sub:</label>
                <input type="text" id="nama" name="nama" value="<?php echo $ar['nama'];?>" readonly>
            </div>
            <div class="form-group">
                <label for="product">Nama Bahan Baku:</label>
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
            <button type="submit" name="simpan" class="btn btn-success">Ambil</button>
        </form>
    </div>

    <footer>
        <p><i>&copy; 2024 IT PT. Indocipta Wisesa.</i></p>
    </footer>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Check if there's an error parameter in the URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('error')) {
            alert(urlParams.get('error')); // Display the error message in a pop-up
        }
    });
    </script>
</body>
</html>
