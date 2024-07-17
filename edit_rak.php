<?php
// Sertakan file koneksi database
include('database_connection.php');

// Pastikan pengguna sudah login dan memiliki hak akses yang sesuai
if (!isset($_SESSION["type"])) {
    header('location:login.php');
    exit; // Keluar dari skrip setelah mengarahkan pengguna ke halaman login
}

if ($_SESSION["type"] != 'master') {
    header("location:index.php");
    exit; // Keluar dari skrip setelah mengarahkan pengguna ke halaman yang sesuai
}

include('header.php');

// Proses form saat tombol "Update" ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["proses"])) {
    // Ambil data dari formulir
    $namarak = $_POST["namarak"];
    $subrak = $_POST["subrak"];
    $lot = $_POST["lot"];
    $kategori = $_POST["kategori"];
    $id = $_POST["id"];
    $id_item2 = $_POST['id_item2'];

    // Periksa apakah ada perubahan pada kolom 'lot'
    $queryDataRak = mysqli_query($koneksi, "SELECT lot, product FROM lokasi_barang WHERE id='$id'");
    $dataRak = mysqli_fetch_assoc($queryDataRak);
    $oldLot = $dataRak['lot'];
    $product = $dataRak['product'];

    // Update data ke tabel lokasi_barang
    $query = "UPDATE lokasi_barang SET id_rak='$namarak', id_lokasi='$subrak', lot='$lot', kategori='$kategori' WHERE id='$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Jika terjadi perubahan pada kolom 'lot'
        if ($oldLot != $lot) {
            // Update kolom 'lot' di tabel 'bahan_baku_item' yang memiliki hubungan dengan produk yang sama
            $updateLotQuery = "UPDATE bahan_baku_item SET lot='$lot' WHERE product='$product'";
            $updateLotResult = mysqli_query($koneksi, $updateLotQuery);

            if (!$updateLotResult) {
                echo "Gagal mengupdate kolom lot pada tabel bahan_baku_item: " . mysqli_error($koneksi);
                exit;
            }
        }

        // Update kategori pada tabel bahan_baku_item
        $updateKategoriQuery = "UPDATE bahan_baku_item SET kategori='$kategori' WHERE id='$id_item2'";
        $updateKategoriResult = mysqli_query($koneksi, $updateKategoriQuery);

        if ($updateKategoriResult) {
            // Tampilkan pesan sukses dan arahkan kembali ke halaman lokasi_barang
            echo "<script>alert('Data berhasil diupdate.'); window.location.href='lokasi_barang.php';</script>";
            exit; // Keluar dari skrip setelah menampilkan pesan sukses
        } else {
            echo "Gagal mengupdate kategori pada tabel bahan_baku_item: " . mysqli_error($koneksi);
        }
    } else {
        echo "Gagal mengupdate data ke database: " . mysqli_error($koneksi);
    }
}

// Mendapatkan id dari URL
$id = $_GET['id'];
// Query untuk mendapatkan data rak berdasarkan id
$queryDataRak = mysqli_query($koneksi, "SELECT * FROM lokasi_barang WHERE id='$id'");
$dataRak = mysqli_fetch_array($queryDataRak);

?>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Form Edit Data Rak</strong></div>
        <br>
        <div class="panel-body">
            <form action="#" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <table width="100%" border="0">
                        <tr>
                            <td>
                                <label for="namarak">Nama Rak:</label>
                                <select name="namarak" id="namarak" class="form-control" required>
                                    <?php
                                    // Query untuk mendapatkan nama rak dari tabel gudang_detail
                                    $namaRakQuery = "SELECT id, nama_detail FROM gudang_detail WHERE id IN (149156, 1149157)";
                                    $namaRakResult = mysqli_query($koneksi, $namaRakQuery);

                                    while ($row = mysqli_fetch_assoc($namaRakResult)) {
                                        $selected = ($row['id'] == $dataRak['id_rak']) ? 'selected' : '';
                                        echo "<option value='{$row['id']}' $selected>{$row['nama_detail']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <td>&nbsp;</td>
                        <tr>
                            <td>
                                <label for="subrak">Sub Rak:</label>
                                <select name="subrak" id="subrak" class="form-control" required>
                                    <!-- Opsi Sub Rak akan diperbarui secara dinamis oleh JavaScript -->
                                </select>
                            </td>
                        </tr>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                // Mendapatkan elemen Nama Rak
                                var namarakSelect = document.getElementById('namarak');
                                // Mendapatkan elemen Sub Rak
                                var subrakSelect = document.getElementById('subrak');

                                // Fungsi untuk memperbarui opsi Nama Rak dan Sub Rak sesuai dengan data yang tersimpan
                                function updateRakOptions() {
                                    // Mendapatkan nilai yang disimpan dalam database untuk Nama Rak dan Sub Rak
                                    var savedNamaRakId = <?php echo $dataRak['id_rak']; ?>;
                                    var savedSubRakId = <?php echo $dataRak['id_lokasi']; ?>;

                                    // Memperbarui opsi Nama Rak dengan nilai yang disimpan
                                    namarakSelect.value = savedNamaRakId;

                                    // Panggil fungsi untuk memperbarui opsi Sub Rak
                                    updateSubRakOptions(savedNamaRakId, savedSubRakId);
                                }

                                // Fungsi untuk memperbarui opsi Sub Rak berdasarkan Nama Rak yang dipilih
                                function updateSubRakOptions(selectedNamaRakId, savedSubRakId) {
                                    // Kosongkan opsi Sub Rak
                                    subrakSelect.innerHTML = '';

                                    // Mengambil data dari server dengan AJAX
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('GET', 'get_subrak_options.php?id_rak=' + encodeURIComponent(selectedNamaRakId), true); // Menggunakan id_rak sebagai parameter GET
                                    xhr.onload = function () {
                                        if (xhr.status === 200) {
                                            // Parse JSON response
                                            var options = JSON.parse(xhr.responseText);
                                            // Tambahkan opsi Sub Rak ke elemen select
                                            options.forEach(function (option) {
                                                var optionElem = document.createElement('option');
                                                optionElem.value = option.id_lokasi;
                                                optionElem.textContent = option.nama;
                                                subrakSelect.appendChild(optionElem);
                                            });
                                            // Memperbarui nilai opsi Sub Rak dengan nilai yang disimpan
                                            subrakSelect.value = savedSubRakId;
                                        }
                                    };
                                    xhr.send();
                                }

                                // Panggil fungsi pertama kali untuk menampilkan nilai yang sesuai
                                updateRakOptions();

                                // Tambahkan event listener untuk Nama Rak
                                namarakSelect.addEventListener('change', function () {
                                    // Panggil fungsi untuk memperbarui opsi Sub Rak
                                    updateSubRakOptions(namarakSelect.value);
                                });
                            });
                        </script>
                      <td>&nbsp;</td>
                        <tr>
                            <td>
                                <label for="namaitem">Nama Item:</label>
                                <!-- Field hanya baca -->
                                <input type="text" id="namaitem" name="namaitem" class="form-control" required readonly value="<?php echo $dataRak['product']; ?>">                            
                            </td>
                        </tr>
                        <td>&nbsp;</td>
                        <tr>
                            <td>
                                <!-- id_item2 untuk id bahan_baku_item -->
                                <input type="hidden" id="id_item2" name="id_item2" value="<?php echo $dataRak['id_item']; ?>">
                                <label for="kategori">Kategori:</label>
                                <select name="kategori" id="kategori" class="form-control" required>
                                    <option value="slow moving" <?php if($dataRak['kategori'] == 'slow moving') echo 'selected'; ?>>Slow Moving</option>
                                    <option value="medium moving" <?php if($dataRak['kategori'] == 'medium moving') echo 'selected'; ?>>Medium Moving</option>
                                    <option value="fast moving" <?php if($dataRak['kategori'] == 'fast moving') echo 'selected'; ?>>Fast Moving</option>
                                </select>                            
                            </td>
                        </tr>
                        <td>&nbsp;</td>
                        <tr>
                        <td>
                            <label for="lot">Lot:</label>
                            <input type="text" id="lot" name="lot" class="form-control" required value="<?php echo $dataRak['lot']; ?>" readonly>                            
                        </td>

                        </tr>
                        <td>&nbsp;</td>
                        <tr>
                            <td>
                                <input type="submit" name="proses" value="Update" class="btn btn-success" />&nbsp;
                                <a href="lokasi_barang.php"><button type="button" name="kembali" class="btn btn-primary">Kembali</button></a>                            
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mendapatkan elemen Nama Rak dan Sub Rak
        var namarakSelect = document.getElementById('namarak');
        var subrakSelect = document.getElementById('subrak');

        // Fungsi untuk memperbarui opsi Sub Rak berdasarkan pilihan Nama Rak
        function updateSubrakOptions() {
            // Mendapatkan nilai yang dipilih dari Nama Rak
            var selectedNamaRak = namarakSelect.value;
            // Kosongkan opsi Sub Rak
            subrakSelect.innerHTML = '';

            // Mengambil data dari server dengan AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_subrak_options.php?id_rak=' + encodeURIComponent(selectedNamaRak), true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Parse JSON response
                    var options = JSON.parse(xhr.responseText);
                    // Tambahkan opsi Sub Rak ke elemen select
                    options.forEach(function (option) {
                        var optionElem = document.createElement('option');
                        optionElem.value = option.id_lokasi;
                        optionElem.textContent = option.nama;
                        subrakSelect.appendChild(optionElem);
                    });
                }
            };
            xhr.send();
        }

        // Panggil fungsi pertama kali untuk menampilkan opsi yang sesuai
        updateSubrakOptions();

        // Tambahkan event listener untuk Nama Rak
        namarakSelect.addEventListener('change', function () {
            // Panggil fungsi untuk memperbarui opsi Sub Rak
            updateSubrakOptions();
        });
    });
</script>


<?php include('footer.php'); ?>
