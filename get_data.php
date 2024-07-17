<?php
// get_data.php

include('database_connection.php');

if(isset($_POST["id"])) {
    $id = $_POST["id"];

    // Query untuk mengambil data berdasarkan id
    $query = "
    SELECT id, id_rak, id_lokasi, id_item, product, uom, kategori, qr_code, 
           create_date, lot, id_user, ket, no_po, no_bpb, status_rak, url, tanggal_penyimpanan, squance
    FROM lokasi_barang 
    WHERE id = :id
    ";

    $statement = $connect->prepare($query);
    $statement->execute(array(':id' => $id));
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Mengirimkan data dalam format JSON
    echo json_encode($result);
} else {
    // Jika tidak ada id yang dikirim, berikan respons kosong atau sesuai kebutuhan
    echo json_encode(array('error' => 'ID tidak tersedia.'));
}

?>
