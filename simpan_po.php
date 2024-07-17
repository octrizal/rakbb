<?php

// simpan_po.php

include('database_connection.php');

// Ambil data dari form po_list
$name = $_POST['kode_po'];
$date_order = $_POST['date_order'];
$vendor = $_POST['vendor'];
$create_date = $_POST['create_date'];
$picking_type_id = $_POST['picking_type_id']; // Ambil nilai picking_type_id
$id_po = $_POST['id_po']; // Ambil nilai id_po dari formulir HTML

// Insert data ke tabel 'po_list'
$query = mysqli_query($koneksi, "INSERT INTO po_list VALUES ('', '$name', '$date_order', '$vendor', '$create_date', '', '', '','$picking_type_id', '$id_po')");

// Cek apakah query berhasil dijalankan
if (!$query) {
    echo "<script>window.alert('Error: " . mysqli_error($koneksi) . "')</script>";
    echo "<script>window.location='po_input.php'</script>";
    exit(); // Keluar dari skrip jika terjadi kesalahan
}

// Ambil data produk dari form po_list_item
$products = $_POST['product'];
$jumlah_qrcodes = $_POST['jumlah_qrcode']; // Ambil nilai jumlah_qrcode yang berbeda untuk setiap item
$product_qty = $_POST['product_qty'];
$product_uom = $_POST['uom'];
$price_unit = $_POST['price_unit'];
$date_planned = $_POST['date_planned'];

// Hitung jumlah produk
$jumlah = count($products);

// Lakukan pengulangan untuk setiap produk
for ($x = 0; $x < $jumlah; $x++) {
    $product = $products[$x];
    $jumlah_qrcode1 = $jumlah_qrcodes[$x]; // Ambil nilai jumlah_qrcode untuk setiap item
    $product_qty1 = $product_qty[$x];
    $product_uom1 = $product_uom[$x];
    $price_unit1 = $price_unit[$x];
    $date_planned1 = $date_planned[$x];

    // Insert data produk ke tabel 'po_list_item'
    $query_item = "INSERT INTO po_list_item VALUES('', '$product', '$jumlah_qrcode1', '$product_qty1', '$product_uom1', '$price_unit1', '$date_planned1', '$id_po')";
    $result_item = mysqli_query($koneksi, $query_item);

    // Cek apakah query produk berhasil dijalankan
    if (!$result_item) {
        echo "<script>window.alert('Error: " . mysqli_error($koneksi) . "')</script>";
        echo "<script>window.location='po_input.php'</script>";
        exit(); // Keluar dari skrip jika terjadi kesalahan
    }

    // Update jumlah_qrcode di tabel bahan_baku_item berdasarkan product
    $query_update_bahan_baku = "UPDATE bahan_baku_item SET jumlah_qrcode = '$jumlah_qrcode1' WHERE product = '$product'";
    $result_update_bahan_baku = mysqli_query($koneksi, $query_update_bahan_baku);

    // Cek apakah query update berhasil dijalankan
    if (!$result_update_bahan_baku) {
        echo "<script>window.alert('Error: " . mysqli_error($koneksi) . "')</script>";
        echo "<script>window.location='po_input.php'</script>";
        exit(); // Keluar dari skrip jika terjadi kesalahan
    }
}

// Notifikasi berhasil
echo "<script>window.alert('Data Telah Ditambahkan!')</script>";
echo "<script>window.location='po_input.php'</script>";

?>
