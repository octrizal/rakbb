<?php
// update_done.php

include('database_connection.php');

if(isset($_POST["id"])) {
    $id = $_POST["id"];
    $done = $_POST["done"];
    $lot = $_POST["lot"]; // Mengambil nilai lot yang dikirimkan

    // Perbarui nilai "done" dan "lot" di database
    $query = "UPDATE bahan_baku_item SET done = '$done', lot = '$lot' WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);
    if ($result && $result_lokasi) {
        // Jika berhasil, kirimkan respons berhasil ke klien
        echo "Data berhasil diperbarui";
    } else {
        // Jika salah satu query gagal, kirimkan respons gagal ke klien
        echo "Terjadi kesalahan saat memperbarui data";
    } 

    // Buat dan jalankan query SQL untuk melakukan update nilai 'lot' pada baris dengan product yang sesuai di tabel 'lokasi_barang'
    $query_lokasi = "UPDATE lokasi_barang 
                     INNER JOIN bahan_baku_item ON lokasi_barang.product = bahan_baku_item.product
                     SET lokasi_barang.lot = '$lot'
                     WHERE bahan_baku_item.id = '$id'";
    $result_lokasi = mysqli_query($koneksi, $query_lokasi);

    // Periksa apakah kedua query berhasil dieksekusi
    if ($result && $result_lokasi) {
        // Jika berhasil, kirimkan respons berhasil ke klien
        echo "Data berhasil diperbarui";
    } else {
        // Jika salah satu query gagal, kirimkan respons gagal ke klien
        echo "Terjadi kesalahan saat memperbarui data";
    }
} else {
    // Jika request bukan POST, kirimkan respons error ke klien
    echo "Metode request tidak valid";
}
?>
