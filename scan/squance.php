<?php

include('../database_connection.php');


// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function getNewNumber($koneksi) {
    $currentDate = date("Y-m-d");
    $currentMonth = date("Y-m");
    
    // Cek apakah sudah ada entri untuk bulan ini
    $sql = "SELECT * FROM lokasi_barang WHERE DATE_FORMAT(current_date, '%Y-%m') = '$currentMonth' ORDER BY squance DESC LIMIT 1";
    $result = mysqli_query($koneksi, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Jika ada, ambil urutan terakhir dan tambahkan 1
        $row = mysqli_fetch_assoc($result);
       		echo $newSequence = $row['squance'] + 1;
    } else {
        // Jika tidak ada, mulai dari 1
        	echo $newSequence = 1;
    }
    
    // Simpan urutan baru ke database
	/*
    $sql = "INSERT INTO lokasi_barang (current_date, sequence) VALUES ('$currentDate', $newSequence)";
    if (mysqli_query($koneksi, $sql)) {
        // Format nomor dengan 4 digit urut
        $formattedNumber = date("Ymd") . sprintf("%04d", $newSequence);
        return $formattedNumber;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
    }
    
    return null;
	*/
}


// Mendapatkan nomor baru
$newNumber = getNewNumber($koneksi);
if ($newNumber !== null) {
    echo "Nomor baru: " . $newNumber;
} else {
    echo "Gagal mendapatkan nomor baru.";
}

// Menutup koneksi
mysqli_close($koneksi);
?>
