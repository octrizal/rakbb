<?php
// Koneksi ke database  --- > posgree
include "database_pg.php";

// Ambil data dari database berdasarkan input pencarian
$output = '';
if(isset($_POST["query"])){
    $search = pg_escape_string($_POST["query"]);
    $sql = "SELECT name FROM stock_picking WHERE picking_type_id=1 AND state='done' AND name LIKE '%".$search."%'  limit 7";
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



// Koneksi ke database ---> mysqli
/*
$conn = mysqli_connect("localhost", "root", "", "coa_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ambil data dari database berdasarkan input pencarian
$output = '';
if(isset($_POST["query"])){
    $search = mysqli_real_escape_string($conn, $_POST["query"]);
    $sql = "SELECT productionlot FROM coa_list WHERE productionlot LIKE '%".$search."%'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $output .= '<li>'.$row["productionlot"].'</li>';
        }
    } else{
        $output .= '<li>No result</li>';
    }
    echo $output;
}

// Tutup koneksi database
mysqli_close($conn);
*/
?>