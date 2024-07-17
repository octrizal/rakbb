<?php
// Koneksi ke database  --- > posgree
include "database_pg.php";

// Ambil data dari database berdasarkan input pencarian
$output = '';
if(isset($_POST["query"])){
    $search = pg_escape_string($_POST["query"]);
    $sql = "SELECT name FROM purchase_order WHERE name LIKE '%".$search."%'  limit 7";
    $result = pg_query($conn, $sql);
    if(pg_num_rows($result) > 0){
        while($row = pg_fetch_array($result)){
            $output .= '<li>'.$row["name"].'</li>';
        }
    } else{
        $output .= '<li>No result</li>';
    }
    echo $output;
}

// Tutup koneksi database
pg_close($conn);




?>