<?php
// process_edit.php

include('database_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $jumlah_qrcode = $_POST['jumlah_qrcode'];

    // Update jumlah QRCode dalam database untuk tabel po_list_item
    $query = "UPDATE po_list_item SET jumlah_qrcode = '$jumlah_qrcode' WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($koneksi);
    } else {
        // Ambil product yang terkait dengan po_list_item
        $product_query = "SELECT product FROM po_list_item WHERE id = '$id'";
        $product_result = mysqli_query($koneksi, $product_query);
        $product_row = mysqli_fetch_assoc($product_result);
        $product = $product_row['product'];

        // Update jumlah QRCode dalam database untuk tabel bahan_baku_item
        $update_bahan_baku_query = "UPDATE bahan_baku_item SET jumlah_qrcode = '$jumlah_qrcode' WHERE product = '$product'";
        $update_bahan_baku_result = mysqli_query($koneksi, $update_bahan_baku_query);

        if (!$update_bahan_baku_result) {
            echo "Error updating bahan_baku_item: " . mysqli_error($koneksi);
        } else {
            echo "Jumlah QRCode berhasil diperbarui!";
        }
    }
} else {
    echo "Akses tidak sah!";
}
?>
