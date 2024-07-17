<?php
// insert_lokasi_barang.php
include('database_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = mysqli_real_escape_string($koneksi, $_POST['product']);
    $jumlah_qrcode = intval($_POST['jumlah_qrcode']);  // Ensure it's an integer

    // Fetch necessary details from the database
    $query = "
    SELECT 
        bbi.kategori, 
        po.uom, 
        b.origin, 
        b.bpb,
        bbi.status_rak,   -- Tambahkan kolom status_rak dari bahan_baku_item
        lb.url   -- Tambahkan kolom url dari lokasi_barang
    FROM 
        bahan_baku_item bbi 
    JOIN 
        po_list_item po ON bbi.product = po.product 
    JOIN 
        bahan_baku b ON bbi.id_bpb = b.id_bpb 
    LEFT JOIN 
        lokasi_barang lb ON bbi.product = lb.product 
    WHERE 
        bbi.product = '$product' AND 
        bbi.id_bpb = '" . $_GET['bpb'] . "'";


    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_array($result);

    if ($data) {
        $kategori = $data['kategori'];
        $uom = $data['uom'];
        $origin = $data['origin'];
        $bpb = $data['bpb'];
        $create_date = date('Y-m-d H:i:s');
        $status_rak = $data['status_rak'];
        $url = $data['url'];
		$datenow=date('dmy');

        for ($i = 0; $i < $jumlah_qrcode; $i++) {
            // Insert into lokasi_barang
			
			
			
			
			//aturan squance
			// Ambil nomor urut tertinggi untuk bulan tahun sekarang
			$currentMonth = date('Y-m');
			$tanggal=date('Y-m-d');
			$query = "SELECT MAX(squance) as max_order_number FROM lokasi_barang WHERE DATE_FORMAT(tanggal_penyimpanan, '%Y-%m') = '$currentMonth'";
			$result = mysqli_query($koneksi, $query);
			
			if ($result) {
				$row = mysqli_fetch_assoc($result);
				$maxOrderNumber = $row['max_order_number'];
			
				// Jika belum ada order di bulan ini, mulai dari 1
				if ($maxOrderNumber === null) {
					$newOrderNumber = 1;
				} else {
					$newOrderNumber = $maxOrderNumber + 1;
				}
            }
			
			
			//membuat url random dan lot number dihasilkan dari tanggal simpan + nomor squance per bulan ini
			//seleksi jumlah query dalam lokasi
			$lot="$datenow$newOrderNumber";
			$hs = md5($newOrderNumber);
			$seq = '' . substr($hs, 0, 4);
			
			
			$hash = md5($lot);
    		// Mengambil 10 karakter pertama dari hash sebagai URL acak
   			$url = "$seq$lot" . substr($hash, 0, 10);
			
			//simpdan ke tabel lokasi_barang
			
            $insert_query = "
                INSERT INTO lokasi_barang 
                (product, uom, kategori, qr_code, create_date,lot, no_po, no_bpb, status_rak, url, tanggal_penyimpanan, squance ) 
                VALUES 
                ('$product', '$uom', '$kategori', '$jumlah_qrcode', '$create_date','$lot', '$origin', '$bpb', '$status_rak', '$url', '$tanggal', '$newOrderNumber')";

            if (!mysqli_query($koneksi, $insert_query)) {
                echo "Error: " . mysqli_error($koneksi);
                exit;
            }
        }
        echo "Data successfully inserted.";
    } else {
        echo "Error fetching data.";
    }
}
?>
