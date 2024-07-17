<?php
// simpan_bahan.php

// Memastikan file database_connection.php sudah termasuk atau disertakan dengan benar.
include('database_connection.php');

// Mengambil nilai dari form menggunakan metode POST.
$kode_bpb1 = $_POST['kode_bpb1'];
$Source_Document = $_POST['origin'];
$Validation_Date = $_POST['date_validation'];
$Customer_Vendor = $_POST['vendor'];
$Destination_Location = $_POST['tujuan'];
$id_bpb = $_POST['id_bpb'];
$id_user = $_POST['id_user'];

// Menjalankan query untuk menyimpan data ke tabel bahan_baku.
$query_bahan_baku = "INSERT INTO bahan_baku VALUES ('', '$kode_bpb1', '$Source_Document', '$Validation_Date', '$Customer_Vendor', '$Destination_Location', '$tgl_waktu', '$id_user', '$id_bpb')";
$result_bahan_baku = mysqli_query($koneksi, $query_bahan_baku);

// Memeriksa apakah query berhasil dijalankan.
if (!$result_bahan_baku) {
    echo "Error: " . $query_bahan_baku . "<br>" . mysqli_error($koneksi);
} else {
    // Jika query bahan_baku berhasil, lanjutkan dengan menyimpan item bahan baku.
    $kode_bpb = $_POST['kode_bpb'];
    $product = $_POST['product'];
    $uom = $_POST['uom'];
    $lot = $_POST['lot'];
    $kategori = $_POST['kategori'];
    $todo = $_POST['todo'];
    $done = $_POST['done'];
    $jumlah = $_POST['jum_item'];
    $id_picking = $_POST['picking_id'];

    // Melakukan iterasi untuk menyimpan setiap item bahan baku.
    for ($x = 0; $x < $jumlah; $x++) {
        $kode_bpb1 = $kode_bpb[$x];
        $product1 = $product[$x];
        $uom1 = $uom[$x];
        $lot1 = $lot[$x];
        $kategori1 = $kategori[$x];
        $todo1 = $todo[$x];
        $done1 = $done[$x];
        $id_picking1 = $id_picking[$x];

        // Mendapatkan nilai jumlah_qrcode dari po_list_item yang sesuai dengan product1.
        $query_po_list_item = "SELECT jumlah_qrcode FROM po_list_item WHERE product = '$product1' order by id desc";
        $result_po_list_item = mysqli_query($koneksi, $query_po_list_item);

        // Memeriksa apakah query po_list_item berhasil dijalankan.
        if (!$result_po_list_item) {
            echo "Error: " . $query_po_list_item . "<br>" . mysqli_error($koneksi);
        } else {
            $row = mysqli_fetch_assoc($result_po_list_item);
            $jumlah_qrcode = $row['jumlah_qrcode'];

            // Menjalankan query untuk menyimpan item bahan baku ke dalam bahan_baku_item.
            $query_item = "INSERT INTO bahan_baku_item (bpb, product, jumlah_qrcode, uom, lot, kategori, todo, done, id_bpb, status_rak) 
                           VALUES ('$kode_bpb1', '$product1', '$jumlah_qrcode', '$uom1', '$lot1', '$kategori1', '$todo1', '$done1', '$id_picking1', '0')";
            $result_item = mysqli_query($koneksi, $query_item);

            // Memeriksa apakah query item berhasil dijalankan.
            if (!$result_item) {
                echo "Error: " . $query_item . "<br>" . mysqli_error($koneksi);
            }
        }
    }

    // Setelah semua item disimpan, arahkan pengguna kembali ke halaman smginput.php.
    echo "<script>window.alert('List bahan baku ditambahkan !')</script>";
    echo "<script>window.location='smginput.php'</script>";
}

?>
