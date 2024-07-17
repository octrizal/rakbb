<?php
// Sertakan file koneksi ke database dan inisialisasi sesi jika diperlukan
include('database_connection.php');

// Periksa apakah permintaan dikirim menggunakan metode POST
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan data yang dikirim benar-benar aman
    $product = mysqli_real_escape_string($koneksi, $_POST['product']);
    $jumlah_qrcode = mysqli_real_escape_string($koneksi, $_POST['jumlah_qrcode']);

    // Lakukan validasi dan update ke database sesuai kebutuhan
    // Contoh:
    $update_query = "UPDATE po_list_item SET jumlah_qrcode = '$jumlah_qrcode' WHERE product = '$product'";
    $result = mysqli_query($koneksi, $update_query);
    if ($result) {
        // Berhasil memperbarui nilai jumlah QR Code
        echo "success";
    } else {
        // Gagal memperbarui, berikan pesan kesalahan jika diperlukan
        echo "Gagal memperbarui nilai jumlah QR Code.";
    }
}
?>
