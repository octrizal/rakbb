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


?>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Form Data Rak </strong></div>
        <br>
        <div class="panel-body">
           
                 <form method="POST" action="">
        <label for="gudang">Pilih Gudang:</label>
        <select id="gudang" name="gudang">
            <?php
            // Koneksi ke database
          $koneksi = mysqli_connect("localhost","root","","inventori_bahan_baku");

            // Cek koneksi
            if (!$koneksi) {
                die("Koneksi gagal: " . mysqli_connect_error());
            }

            // Ambil data gudang
            $sql = "SELECT id, nama_detail FROM gudang_detail";
            $result = mysqli_query($koneksi, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row["id"] . "'>" . $row["nama_detail"] . "</option>";
                }
            } else {
                echo "<option value=''>Tidak ada gudang tersedia</option>";
            }

            mysqli_close($koneksi);
            ?>
        </select>
        <button type="submit">Tampilkan Lokasi</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['gudang'])) {
        $gudang_id = $_POST['gudang'];

        // Koneksi ulang ke database
        $koneksi = mysqli_connect("localhost","root","","inventori_bahan_baku");

        if (!$koneksi) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }

        // Query untuk mengambil data lokasi berdasarkan gudang detail
        $sql = "SELECT lm.id_lokasi, lm.nama
                FROM lokasi_master lm
                JOIN gudang_detail gd ON lm.id_detail = gd.id
                WHERE gd.id = $gudang_id";

        $result = mysqli_query($koneksi, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<form method='POST' action='proses.php'>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<input type='radio' name='lokasi' value='" . $row["id_lokasi"] . "'>" . $row["nama"] . "<br>";
            }
            echo "<button type='submit'>Pilih Lokasi</button>";
            echo "</form>";
        } else {
            echo "Tidak ada data lokasi untuk gudang terpilih.";
        }

        mysqli_close($koneksi);
    }
	
	
	// Fungsi untuk mendekripsi URL
function dekripsiURL($encoded_url) {
    // Misalnya, kita bisa menggunakan base64_decode untuk mendekripsi URL
    return base64_decode($encoded_url);
}

if (isset($_GET['url'])) {
    // Mendekripsi URL yang diterima
    $halaman_rahasia = dekripsiURL($_GET['url']);

    // Redirect ke halaman rahasia
    header("Location: $halaman_rahasia");
    exit();
} else {
    // Jika tidak ada URL yang diterima, lakukan tindakan lain (misalnya, menampilkan pesan error)
    echo "Halaman tidak ditemukan.";
}

    ?>
	
        </div>
    </div>
</div>
