<?php
// update_identitas.php

include('database_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Memeriksa apakah file logo diunggah dengan benar
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Folder tujuan untuk menyimpan file logo

        // Membuat folder jika belum ada
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Mendapatkan nama file logo yang diunggah
        $logoName = $_FILES['logo']['name'];

        // Mendapatkan path sementara file logo yang diunggah
        $tmpLogoPath = $_FILES['logo']['tmp_name'];

        // Membuat path baru untuk file logo di folder uploads
        $newLogoPath = $uploadDir . $logoName;

        // Menyalin file logo ke folder uploads
        if (move_uploaded_file($tmpLogoPath, $newLogoPath)) {
            // Proses update identitas lainnya jika diperlukan
            $id = $_POST['id'];
            $nama_usaha = $_POST['nama_usaha'];
            $alamat = $_POST['alamat'];
            $telp = $_POST['telp'];

            // Simpan nama file (tanpa path) ke dalam database
            $fileNameInDatabase = $logoName; // Sesuaikan dengan struktur database Anda

            // Melakukan pembaruan data identitas ke dalam tabel tb_identitas
            $update_query = "UPDATE tb_identitas SET nama_usaha='$nama_usaha', alamat='$alamat', telp='$telp', logo='$fileNameInDatabase' WHERE id='$id'";
            $result = mysqli_query($koneksi, $update_query);

            if ($result) {
                // Jika pembaruan berhasil, arahkan kembali ke halaman setting_pt.php
                header("Location: setting_pt.php");
                exit(); // Penting untuk menghentikan eksekusi script setelah header()
            } else {
                // Jika terjadi kesalahan, tampilkan pesan kesalahan
                echo "Error updating record: " . mysqli_error($koneksi);
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file logo.";
        }
    } else {
        echo "Maaf, file logo tidak valid atau tidak diunggah.";
    }
}
?>
